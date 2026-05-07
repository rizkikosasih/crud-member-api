<?php

namespace App\Listeners\Member;

use App\Events\Member\MemberRestoredEvent;
use Illuminate\Support\Facades\Log;

class LogMemberRestored
{
    public function handle(MemberRestoredEvent $event): void
    {
        Log::channel('daily')->info('Member restored', [
            'member_id' => $event->member->id,
            'actor_id' => $event->actorId,
        ]);
    }
}
