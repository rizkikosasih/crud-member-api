<?php

namespace App\Listeners\Account;

use App\Events\Account\AccountPasswordChangedEvent;
use Illuminate\Support\Facades\Log;

class LogAccountPasswordChanged
{
    public function handle(AccountPasswordChangedEvent $event): void
    {
        Log::channel('daily')->info('Account Change Password', [
            'user_id' => $event->userId,
            'ip' => $event->ip,
        ]);
    }
}
