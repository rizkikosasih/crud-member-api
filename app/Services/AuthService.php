<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthService
{
    protected JWTGuard $guard;

    public function __construct()
    {
        $this->guard = auth('api');
    }

    public function register(array $data): array
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->assignRole($data['roles'] ?? [config('user.default_role')]);

        return ApiResponse::success($user, 'User registered');
    }

    public function login(array $credentials): array
    {
        if (!($token = $this->guard->attempt($credentials))) {
            return ApiResponse::error('Invalid credentials');
        }

        return ApiResponse::success(
            [
                'token' => $token,
                'type' => 'bearer',
            ],
            'Login success',
        );
    }

    public function logout(): array
    {
        $this->guard->logout();

        return ApiResponse::success(null, 'Logout success');
    }

    public function refresh(): array
    {
        $token = $this->guard->refresh();

        return ApiResponse::success(
            [
                'token' => $token,
                'type' => 'bearer',
            ],
            'Token refreshed',
        );
    }
}
