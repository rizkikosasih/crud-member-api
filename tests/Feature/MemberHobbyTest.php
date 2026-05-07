<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\{User, Member, Hobby};
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class MemberHobbyTest extends TestCase
{
    use RefreshDatabase;

    protected JWTGuard $guard;

    protected function setUp(): void
    {
        parent::setUp();
        $this->guard = auth('api');
    }

    // =========================
    // HELPERS
    // =========================

    private function createAdminToken(): string
    {
        $user = User::factory()->create();
        $user->assignRole('admin');

        return $this->guard->login($user);
    }

    private function createUserToken(): string
    {
        $user = User::factory()->create();
        return $this->guard->login($user);
    }

    private function authHeader(string $token): array
    {
        return ['Authorization' => "Bearer {$token}"];
    }

    // =========================
    // INDEX HOBBIES
    // =========================

    public function test_member_hobbies_index_success()
    {
        $member = Member::factory()->create();
        $hobbies = Hobby::factory()->count(3)->create();

        $member->hobbies()->attach($hobbies->pluck('id'));

        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson(
            "/api/members/{$member->id}/hobbies",
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_member_hobbies_index_forbidden()
    {
        $member = Member::factory()->create();
        $token = $this->createUserToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson(
            "/api/members/{$member->id}/hobbies",
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // ATTACH HOBBIES
    // =========================

    public function test_attach_hobbies_success()
    {
        $member = Member::factory()->create();
        $hobbies = Hobby::factory()->count(2)->create();

        $token = $this->createAdminToken();

        $payload = [
            'hobby_ids' => $hobbies->pluck('id')->toArray(),
        ];

        $response = $this->withHeaders($this->authHeader($token))->postJson(
            "/api/members/{$member->id}/hobbies",
            $payload,
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertTrue($member->fresh()->hobbies()->count() >= 2);
    }

    public function test_attach_hobbies_forbidden()
    {
        $member = Member::factory()->create();
        $hobbies = Hobby::factory()->count(2)->create();

        $token = $this->createUserToken();

        $payload = [
            'hobby_ids' => $hobbies->pluck('id')->toArray(),
        ];

        $response = $this->withHeaders($this->authHeader($token))->postJson(
            "/api/members/{$member->id}/hobbies",
            $payload,
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // SYNC HOBBIES
    // =========================

    public function test_sync_hobbies_success()
    {
        $member = Member::factory()->create();
        $initial = Hobby::factory()->count(3)->create();

        $member->hobbies()->attach($initial->pluck('id'));

        $newHobbies = Hobby::factory()->count(2)->create();

        $token = $this->createAdminToken();

        $payload = [
            'hobby_ids' => $newHobbies->pluck('id')->toArray(),
        ];

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/members/{$member->id}/hobbies",
            $payload,
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertCount(2, $member->fresh()->hobbies);
    }

    public function test_sync_hobbies_forbidden()
    {
        $member = Member::factory()->create();
        $token = $this->createUserToken();

        $payload = [
            'hobby_ids' => Hobby::factory()->count(2)->create()->pluck('id')->toArray(),
        ];

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/members/{$member->id}/hobbies",
            $payload,
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // DETACH HOBBY
    // =========================

    public function test_detach_hobby_success()
    {
        $member = Member::factory()->create();
        $hobby = Hobby::factory()->create();

        $member->hobbies()->attach($hobby->id);

        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->deleteJson(
            "/api/members/{$member->id}/hobbies/{$hobby->id}",
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseMissing('member_hobby', [
            'member_id' => $member->id,
            'hobby_id' => $hobby->id,
        ]);
    }

    public function test_detach_hobby_forbidden()
    {
        $member = Member::factory()->create();
        $hobby = Hobby::factory()->create();

        $member->hobbies()->attach($hobby->id);

        $token = $this->createUserToken();

        $response = $this->withHeaders($this->authHeader($token))->deleteJson(
            "/api/members/{$member->id}/hobbies/{$hobby->id}",
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }
}
