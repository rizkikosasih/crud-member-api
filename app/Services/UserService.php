<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Events\User\UserCreatedEvent;
use App\Events\User\UserDeletedEvent;
use App\Events\User\UserRestoredEvent;
use App\Events\User\UserUpdatedEvent;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function __construct(protected UserRepositoryInterface $userRepository) {}

    private function filterUpdatableFields(array $data): array
    {
        return array_intersect_key($data, array_flip(['name', 'email', 'is_active']));
    }

    public function index(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->paginate($filters);
    }

    public function show(User $user): User
    {
        return $user->load('roles');
    }

    public function store(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(config('user.defaults.password')),
                'is_active' => $data['is_active'] ?? true,
            ]);

            if (array_key_exists('roles', $data)) {
                $defaultRole = config('user.defaults.role');

                $user->assignRole($data['roles'] ?: $defaultRole);
            }

            event(new UserCreatedEvent($user));

            return $user->load('roles');
        });
    }

    public function update(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $dirtyData = $this->filterUpdatableFields($data);

            $changes = [];

            if (!empty($dirtyData)) {
                $original = $user->only(array_keys($dirtyData));

                $changes = array_diff_assoc($dirtyData, $original);

                if (!empty($changes)) {
                    $user = $this->userRepository->update($user, $dirtyData);
                }
            }

            if (array_key_exists('roles', $data)) {
                $user->syncRoles($data['roles']);
            }

            if (!empty($changes)) {
                event(new UserUpdatedEvent($user, $changes));
            }

            return $user->load('roles');
        });
    }

    public function delete(User $user): void
    {
        DB::transaction(function () use ($user) {
            $user->delete();

            event(new UserDeletedEvent($user, request()->ip()));
        });
    }

    public function restore(User $user): User
    {
        return DB::transaction(function () use ($user) {
            if (!$user->trashed()) {
                return $user->load('hobbies');
            }

            $user->restore();

            event(new UserRestoredEvent($user, request()->ip()));

            return $user->load('roles');
        });
    }
}
