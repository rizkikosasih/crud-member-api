<?php

namespace App\Listeners\User;

use App\Events\User\UserUpdatedEvent;
use Illuminate\Support\Facades\Log;

class LogUserUpdated
{
    public function handle(UserUpdatedEvent $event): void
    {
        Log::channel('daily')->info('User Updated', [
            'user_id' => $event->user->id,
            'changes' => $event->changes,
        ]);
    }
}
