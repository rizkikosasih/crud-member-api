<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserDeletedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(
        public User $user,
        public string $ip
    ) {}
}
