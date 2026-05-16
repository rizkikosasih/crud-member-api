<?php

namespace App\Services;

use App\Events\Account\AccountPasswordChangedEvent;
use App\Events\Account\AccountUpdatedEvent;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use PHPOpenSourceSaver\JWTAuth\JWTGuard;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AccountService
{
    protected JWTGuard $guard;

    public function __construct()
    {
        $this->guard = auth('api');
    }

    public function me(): User
    {
        /** @var User $user */
        $user = $this->guard->user();

        return $user->load('roles');
    }

    private function filterUpdatableFields(array $data): array
    {
        return array_intersect_key($data, array_flip(['name', 'email']));
    }

    public function changePassword(array $data): User
    {
        /** @var User $user */
        $user = $this->guard->user();

        return DB::transaction(function () use ($user, $data) {
            if (!Hash::check($data['old_password'], $user->password)) {
                throw new HttpException(422, 'Incorrect Password');
            }

            $user->update([
                'password' => Hash::make($data['new_password']),
            ]);

            event(new AccountPasswordChangedEvent($user->id, request()->ip()));

            return $user->load('roles');
        });
    }

    public function update(?array $data): User
    {
        /** @var User $user */
        $user = $this->guard->user();

        return DB::transaction(function () use ($user, $data) {
            $dirtyData = $this->filterUpdatableFields($data);

            $changes = [];

            if (!empty($dirtyData)) {
                $original = $user->only(array_keys($dirtyData));

                $changes = array_diff_assoc($dirtyData, $original);

                if (!empty($changes)) {
                    $user->update($dirtyData);
                    event(new AccountUpdatedEvent($user->id, $changes));
                }
            }

            return $user->load('roles');
        });
    }
}
