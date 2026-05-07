<?php

namespace App\Listeners\Auth;

use App\Events\Auth\RefreshEvent;
use Illuminate\Support\Facades\Log;

class LogRefreshActivity
{
    public function handle(RefreshEvent $event): void
    {
        Log::channel('daily')->info('Auth Refresh Token', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
