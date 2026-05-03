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

    public function register(array $data): object
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

        $token = $this->guard->login($user);

        return (object) [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function login(array $credentials): object
    {
        if (!($token = $this->guard->attempt($credentials))) {
            abort(401, 'Invalid credentials');
        }

        /** @var User $user */
        $user = $this->guard->user();

        return (object) [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function logout(): void
    {
        $this->guard->logout();
    }

    public function refresh(): object
    {
        $token = $this->guard->refresh();
        /** @var User $user */
        $user = $this->guard->user();

        return (object) [
            'token' => $token,
            'user' => $user,
        ];
    }
}
