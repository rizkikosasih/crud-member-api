<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthService
{
    protected JWTGuard $guard;

    public function __construct()
    {
        $this->guard = auth('api');
    }

    public function register(array $data): User
    {
        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole($data['roles'] ?? [config('user.defaults.role')]);

            return $user;
        });

        return $user;
    }

    public function login(array $credentials): array
    {
        if (!($token = $this->guard->attempt($credentials))) {
            abort(401, 'Invalid credentials');
        }

        return [
            'token' => $token,
            'type' => 'bearer',
        ];
    }

    public function logout(): void
    {
        return $this->guard->logout();
    }

    public function refresh(): array
    {
        $token = $this->guard->refresh();

        return [
            'token' => $token,
            'type' => 'bearer',
        ];
    }
}
