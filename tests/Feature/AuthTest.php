<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected function registerPayload(array $overrides = []): array
    {
        return array_merge(
            [
                'name' => 'Test User',
                'email' => 'test@mail.com',
                'password' => config('user.defaults.password'),
                'password_confirmation' => config('user.defaults.password'),
            ],
            $overrides,
        );
    }

    protected function loginPayload(string $email, string $password): array
    {
        return compact('email', 'password');
    }

    // =========================
    // REGISTER SUCCESS
    // =========================

    public function test_user_can_register()
    {
        $response = $this->postJson('/api/auth/register', $this->registerPayload());

        $response->assertStatus(201)->assertJsonStructure(['status', 'message', 'data']);
    }

    // =========================
    // REGISTER VALIDATION
    // =========================

    public function test_user_cannot_register_invalid_validation()
    {
        $response = $this->postJson(
            '/api/auth/register',
            $this->registerPayload([
                'name' => '',
                'email' => 'test mail.com',
            ]),
        );

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation failed.',
            ])
            ->assertJsonStructure(['status', 'message', 'errors'])
            ->assertJsonValidationErrors(['name', 'email']);
    }

    public function test_user_cannot_register_email_exists()
    {
        $password = config('user.defaults.password');

        User::factory()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson('/api/auth/register', $this->registerPayload());

        $response
            ->assertStatus(422)
            ->assertJson([
                'message' => 'Validation failed.',
            ])
            ->assertJsonValidationErrors(['email']);
    }

    // =========================
    // LOGIN SUCCESS
    // =========================

    public function test_user_can_login()
    {
        $password = config('user.defaults.password');

        $user = User::factory()->create([
            'email' => 'test@mail.com',
            'password' => bcrypt($password),
        ]);

        $response = $this->postJson(
            '/api/auth/login',
            $this->loginPayload($user->email, $password),
        );

        $response->assertStatus(200)->assertJsonStructure(['status', 'message', 'data']);
    }

    // =========================
    // LOGIN FAILURE
    // =========================

    public function test_user_cannot_login_with_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'wrongpass@mail.com',
        ]);

        $response = $this->postJson(
            '/api/auth/login',
            $this->loginPayload($user->email, 'wrong-password'),
        );

        $response->assertStatus(401)->assertJsonStructure(['status', 'message', 'errors']);
    }
}
