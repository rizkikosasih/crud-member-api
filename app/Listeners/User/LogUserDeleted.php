<?php

namespace App\Listeners\User;

use App\Events\User\UserDeletedEvent;
use Illuminate\Support\Facades\Log;

class LogUserDeleted
{
    public function handle(UserDeletedEvent $event): void
    {
        Log::channel('daily')->info('User Deleted', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
