<?php

namespace App\Events\User;

use App\Models\User;

class UserUpdatedEvent
{
    public function __construct(public User $user, public array $changes) {}
}
