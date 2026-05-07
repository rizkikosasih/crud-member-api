<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserTest extends TestCase
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

    private function createToken(string $role = 'admin', bool $withUser = false): string|object
    {
        $user = User::factory()->create();
        $user->assignRole($role);

        $token = $this->guard->login($user);

        return $withUser ? (object) ['token' => $token, 'user' => $user] : $token;
    }

    private function authHeader(string $token): array
    {
        return ['Authorization' => "Bearer {$token}"];
    }

    // =========================
    // LIST USERS
    // =========================

    public function test_can_list_users()
    {
        User::factory()->count(3)->create();

        $token = $this->createToken();

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/users');

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    // =========================
    // CREATE USER
    // =========================

    public function test_admin_can_create_user()
    {
        $token = $this->createToken();

        $response = $this->withHeaders($this->authHeader($token))->postJson('/api/users', [
            'name' => 'Test User',
            'email' => 'test@mail.com',
        ]);

        $response->assertStatus(201)->assertJsonStructure(['status', 'message', 'data']);
    }

    // =========================
    // UPDATE USER (PUT)
    // =========================

    public function test_admin_can_update_user()
    {
        $token = $this->createToken();

        $user = User::factory()->create();

        $response = $this->withHeaders($this->authHeader($token))->putJson(
            "/api/users/{$user->id}",
            [
                'name' => 'Updated User',
                'email' => 'updated@mail.com',
            ],
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated User',
            'email' => 'updated@mail.com',
        ]);
    }

    public function test_admin_cannot_update_self()
    {
        $admin = $this->createToken('admin', true);

        $response = $this->withHeaders($this->authHeader($admin->token))->putJson(
            "/api/users/{$admin->user->id}",
            [
                'name' => 'Updated Name',
                'email' => 'updated@mail.com',
            ],
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // PATCH USER
    // =========================

    public function test_admin_can_patch_user()
    {
        $token = $this->createToken();

        $user = User::factory()->create(['name' => 'Old Name']);

        $response = $this->withHeaders($this->authHeader($token))->patchJson(
            "/api/users/{$user->id}",
            [
                'name' => 'Patched Name',
            ],
        );

        $response->assertStatus(200)->assertJsonPath('data.name', 'Patched Name');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Patched Name',
        ]);
    }

    // =========================
    // DELETE USER
    // =========================

    public function test_admin_can_delete_user()
    {
        $token = $this->createToken();

        $user = User::factory()->create();

        $response = $this->withHeaders($this->authHeader($token))->deleteJson(
            "/api/users/{$user->id}",
        );

        $response->assertStatus(200);

        $this->assertSoftDeleted('users', [
            'id' => $user->id,
        ]);
    }

    public function test_admin_cannot_delete_self()
    {
        $admin = $this->createToken('admin', true);

        $response = $this->withHeaders($this->authHeader($admin->token))->deleteJson(
            "/api/users/{$admin->user->id}",
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // RESTORE USER
    // =========================

    public function test_admin_can_restore_user()
    {
        $token = $this->createToken();

        $user = User::factory()->trashed()->create();

        $response = $this->withHeaders($this->authHeader($token))->patchJson(
            "/api/users/{$user->id}/restore",
        );

        $response->assertStatus(200);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'deleted_at' => null,
        ]);
    }

    public function test_admin_cannot_restore_self()
    {
        $admin = $this->createToken('admin', true);

        $response = $this->withHeaders($this->authHeader($admin->token))->patchJson(
            "/api/users/{$admin->user->id}/restore",
        );

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }
}
