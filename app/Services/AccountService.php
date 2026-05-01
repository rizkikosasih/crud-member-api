<?php

namespace App\Services;

use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;
use App\Models\User;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;

class AccountService
{
    protected JWTGuard $guard;

    public function __construct()
    {
        $this->guard = auth('api');
    }

    public function me(): array
    {
        return ApiResponse::success($this->guard->user());
    }

    public function changePassword(array $data): array
    {
        /** @var User $user */
        $user = $this->guard->user();

        if (!Hash::check($data['old_password'], $user->password)) {
            return ApiResponse::error('Old password incorrect');
        }

        $user->update([
            'password' => Hash::make($data['new_password']),
        ]);

        return ApiResponse::success($user->fresh(), 'Password updated');
    }
}
