<?php

namespace App\Listeners\Account;

use App\Events\Account\AccountUpdatedEvent;
use Illuminate\Support\Facades\Log;

class LogAccountUpdated
{
    public function handle(AccountUpdatedEvent $event): void
    {
        Log::channel('daily')->info('Account Updated', [
            'user_id' => $event->userId,
            'changes' => $event->changes,
        ]);
    }
}
