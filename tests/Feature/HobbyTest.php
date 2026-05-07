<?php

namespace Tests\Feature;

use App\Models\Hobby;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class HobbyTest extends TestCase
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
    // INDEX
    // =========================

    public function test_index_success_with_permission()
    {
        Hobby::factory()->count(3)->create();

        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/hobbies');

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_index_forbidden_without_permission()
    {
        $token = $this->createUserToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/hobbies');

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // STORE
    // =========================

    public function test_store_success()
    {
        $token = $this->createAdminToken();

        $payload = ['name' => 'Reading'];

        $response = $this->withHeaders($this->authHeader($token))->postJson(
            '/api/hobbies',
            $payload,
        );

        $response->assertStatus(201)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('hobbies', $payload);
    }

    public function test_store_forbidden_without_permission()
    {
        $token = $this->createUserToken();

        $payload = ['name' => 'Reading'];

        $response = $this->withHeaders($this->authHeader($token))->postJson(
            '/api/hobbies',
            $payload,
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // SHOW
    // =========================

    public function test_show_success()
    {
        $hobby = Hobby::factory()->create();
        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson(
            "/api/hobbies/{$hobby->id}",
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    public function test_show_forbidden_without_permission()
    {
        $hobby = Hobby::factory()->create();
        $token = $this->createUserToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson(
            "/api/hobbies/{$hobby->id}",
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // UPDATE
    // =========================

    public function test_update_success()
    {
        $hobby = Hobby::factory()->create();
        $token = $this->createAdminToken();

        $payload = ['name' => 'Updated Hobby'];

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/hobbies/{$hobby->id}",
            $payload,
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('hobbies', $payload);
    }

    public function test_update_forbidden_without_permission()
    {
        $hobby = Hobby::factory()->create();
        $token = $this->createUserToken();

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/hobbies/{$hobby->id}",
            [
                'name' => 'Updated Hobby',
            ],
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // DELETE
    // =========================

    public function test_delete_success()
    {
        $hobby = Hobby::factory()->create();
        $token = $this->createAdminToken();

        $response = $this->withHeaders($this->authHeader($token))->deleteJson(
            "/api/hobbies/{$hobby->id}",
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseMissing('hobbies', [
            'id' => $hobby->id,
        ]);
    }

    public function test_delete_forbidden_without_permission()
    {
        $hobby = Hobby::factory()->create();
        $token = $this->createUserToken();

        $response = $this->withHeaders($this->authHeader($token))->deleteJson(
            "/api/hobbies/{$hobby->id}",
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }
}
