<?php

namespace App\Listeners\User;

use App\Events\User\UserRestoredEvent;
use Illuminate\Support\Facades\Log;

class LogUserRestored
{
    public function handle(UserRestoredEvent $event): void
    {
        Log::channel('daily')->info('User Restored', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
