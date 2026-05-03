<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AccountService
{
    protected JWTGuard $guard;

    public function __construct()
    {
        $this->guard = auth('api');
    }

    public function me(): User
    {
        return $this->guard->user();
    }

    public function changePassword(array $data): User
    {
        /** @var User $user */
        $user = $this->guard->user();

        if (!Hash::check($data['old_password'], $user->password)) {
            abort(422, 'Old password incorrect');
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        return $user;
    }
}
