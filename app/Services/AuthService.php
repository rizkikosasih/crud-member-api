<?php

namespace App\Services;

use App\Events\Auth\LoginEvent;
use App\Events\Auth\LogoutEvent;
use App\Events\Auth\RefreshEvent;
use App\Events\Auth\RegisterEvent;
use App\Models\User;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AuthService
{
    protected JWTGuard $guard;

    public function __construct(AuthFactory $auth)
    {
        $this->guard = $auth->guard('api');
    }

    private function dispatchAuthEvent(string $eventClass, User $user): void
    {
        event(new $eventClass($user, request()->ip()));
    }

    public function register(array $data): array
    {
        $user = DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $defaultRole = config('user.defaults.role');

            $user->assignRole($data['roles'] ?? $defaultRole);

            return $user;
        });

        $token = $this->guard->login($user);

        $this->dispatchAuthEvent(RegisterEvent::class, $user);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function login(array $credentials): array
    {
        if (!($token = $this->guard->attempt($credentials))) {
            throw new AuthenticationException('Invalid credentials');
        }

        $user = $this->guard->user();

        $this->dispatchAuthEvent(LoginEvent::class, $user);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }

    public function logout(): void
    {
        $user = $this->guard->user();

        $this->dispatchAuthEvent(LogoutEvent::class, $user);

        $this->guard->logout();
    }

    public function refresh(): array
    {
        $user = $this->guard->user();

        $token = $this->guard->refresh();

        $this->dispatchAuthEvent(RefreshEvent::class, $user);

        return [
            'token' => $token,
            'user' => $user,
        ];
    }
}
