<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Member;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MemberTest extends TestCase
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

    private function authHeader(string $token): array
    {
        return ['Authorization' => "Bearer {$token}"];
    }

    // =========================
    // LIST MEMBERS
    // =========================

    public function test_list_members()
    {
        Member::factory(2)->create();

        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/members');

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    // =========================
    // CREATE MEMBER
    // =========================

    public function test_create_member()
    {
        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->postJson('/api/members', [
            'name' => 'Member A',
            'email' => 'member@mail.com',
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure(['status', 'message', 'data'])
            ->assertJsonPath('data.name', 'Member A');
    }

    public function test_create_member_validation()
    {
        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->postJson('/api/members', [
            'name' => 'Member A',
            'email' => 'invalid-email',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    public function test_create_member_required_name()
    {
        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->postJson('/api/members', [
            'email' => 'member@mail.com',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['name']);
    }

    // =========================
    // UPDATE MEMBER (PUT)
    // =========================

    public function test_update_member()
    {
        $token = $this->createAdminToken();

        $member = Member::factory()->create();

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/members/{$member->id}",
            [
                'name' => 'Updated Member',
                'email' => 'updated@mail.com',
                'phone' => '08123456789',
                'is_active' => true,
            ],
        );

        $response->assertStatus(200)->assertJsonPath('data.name', 'Updated Member');
    }

    public function test_update_member_email_must_be_unique()
    {
        $token = $this->createAdminToken();

        Member::factory()->create([
            'email' => 'existing@mail.com',
        ]);

        $member = Member::factory()->create();

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/members/{$member->id}",
            [
                'name' => 'Updated',
                'email' => 'existing@mail.com',
            ],
        );

        $response->assertStatus(422)->assertJsonValidationErrors(['email']);
    }

    // =========================
    // PATCH MEMBER
    // =========================

    public function test_patch_member()
    {
        $token = $this->createAdminToken();

        $member = Member::factory()->create([
            'name' => 'Old Name',
        ]);

        $response = $this->withHeaders($this->authHeader($token))->patchJson(
            "/api/members/{$member->id}",
            [
                'name' => 'New Name',
            ],
        );

        $response->assertStatus(200)->assertJsonPath('data.name', 'New Name');
    }

    // =========================
    // DELETE MEMBER
    // =========================

    public function test_delete_member()
    {
        $token = $this->createAdminToken();

        $member = Member::factory()->create();

        $response = $this->withHeaders($this->authHeader($token))->deleteJson(
            "/api/members/{$member->id}",
        );

        $response->assertStatus(200);

        $this->assertSoftDeleted('members', [
            'id' => $member->id,
        ]);
    }

    // =========================
    // RESTORE MEMBER
    // =========================

    public function test_restore_member()
    {
        $token = $this->createAdminToken();

        $member = Member::factory()->create();
        $member->delete();

        $response = $this->withHeaders($this->authHeader($token))->patchJson(
            "/api/members/{$member->id}/restore",
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('members', [
            'id' => $member->id,
            'deleted_at' => null,
        ]);
    }
}
