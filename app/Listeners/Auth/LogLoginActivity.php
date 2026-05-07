<?php

namespace App\Listeners\Auth;

use App\Events\Auth\LoginEvent;
use Illuminate\Support\Facades\Log;

class LogLoginActivity
{
    public function handle(LoginEvent $event): void
    {
        Log::channel('daily')->info('Auth Login', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
