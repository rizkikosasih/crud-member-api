<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class PermissionTest extends TestCase
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

    private function createToken(?string $role = null): string
    {
        $user = User::factory()->create();

        if ($role) {
            $user->assignRole($role);
        }

        return $this->guard->login($user);
    }

    private function authHeader(string $token): array
    {
        return ['Authorization' => "Bearer {$token}"];
    }

    // =========================
    // UNAUTHENTICATED
    // =========================

    public function test_unauthenticated_cannot_access_users()
    {
        $response = $this->getJson('/api/users');

        $response->assertStatus(401)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // AUTHENTICATED WITHOUT ROLE
    // =========================

    public function test_user_without_role_cannot_access_users()
    {
        $token = $this->createToken(); // no role

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/users');

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // AUTHENTICATED WITH ROLE BUT NO PERMISSION
    // =========================

    public function test_user_with_role_without_permission_cannot_access_users()
    {
        $token = $this->createToken('staff');

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/users');

        $response->assertStatus(403)->assertJsonStructure(['status', 'message', 'errors']);
    }

    // =========================
    // AUTHORIZED USER (ADMIN)
    // =========================

    public function test_admin_can_access_users()
    {
        $token = $this->createToken('admin');

        $response = $this->withHeaders($this->authHeader($token))->getJson('/api/users');

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }
}
