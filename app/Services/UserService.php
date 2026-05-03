<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserService
{
    public function __construct(protected \App\Contracts\UserRepositoryInterface $userRepository) {}

    public function index(array $filters = []): LengthAwarePaginator
    {
        return $this->userRepository->paginate($filters);
    }

    public function show(User $user): User
    {
        $user->load('roles');
        return $user;
    }

    public function store(array $data): User
    {
        $user = DB::transaction(function () use ($data) {
            $user = $this->userRepository->create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make(config('user.defaults.password')),
                'is_active' => $data['is_active'] ?? true,
            ]);

            $user->assignRole($data['roles'] ?? [config('user.defaults.role')]);

            return $user->load('roles');
        });

        return $user;
    }

    public function update(User $user, array $data): User
    {
        $user = DB::transaction(function () use ($user, $data) {
            $user = $this->userRepository->update(
                $user,
                array_intersect_key($data, array_flip(['name', 'email', 'is_active'])),
            );

            if (array_key_exists('roles', $data)) {
                $user->syncRoles($data['roles']);
            }

            return $user->load('roles');
        });

        return $user;
    }

    public function delete(User $user): void
    {
        $user->delete();
    }

    public function restore(User $user): User
    {
        $user->restore();
        $user->load('roles');

        return $user;
    }
}
