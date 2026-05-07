<?php

namespace App\Listeners\User;

use App\Events\User\UserCreatedEvent;
use Illuminate\Support\Facades\Log;

class LogUserCreated
{
    public function handle(UserCreatedEvent $event): void
    {
        Log::channel('daily')->info('User Created', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
        ]);
    }
}
