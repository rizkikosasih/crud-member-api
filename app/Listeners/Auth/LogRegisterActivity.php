<?php

namespace App\Listeners\Auth;

use App\Events\Auth\RegisterEvent;
use Illuminate\Support\Facades\Log;

class LogRegisterActivity
{
    public function handle(RegisterEvent $event): void
    {
        Log::channel('daily')->info('Auth Register', [
            'user_id' => $event->user->id,
            'email' => $event->user->email,
            'ip' => $event->ip,
        ]);
    }
}
