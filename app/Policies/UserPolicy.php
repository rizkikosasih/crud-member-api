<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id !== $user->id;
    }

    public function patch(User $authUser, User $user): bool
    {
        return $authUser->id !== $user->id;
    }

    public function delete(User $authUser, User $user): bool
    {
        return $authUser->id !== $user->id;
    }

    public function restore(User $authUser, User $user): bool
    {
        return $authUser->id !== $user->id && $user->trashed();
    }
}
