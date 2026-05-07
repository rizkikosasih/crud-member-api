<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LogoutEvent;
use Illuminate\Support\Facades\Log;

class LogLogoutActivity
{
    public function handle(LogoutEvent $event): void
    {
        Log::channel('daily')->info('Auth Logout', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
