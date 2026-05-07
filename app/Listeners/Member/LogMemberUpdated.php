<?php

namespace App\Listeners\Member;

use App\Events\Member\MemberUpdatedEvent;
use Illuminate\Support\Facades\Log;

class LogMemberUpdated
{
    public function handle(MemberUpdatedEvent $event): void
    {
        Log::channel('daily')->info('Member updated', [
            'member_id' => $event->member->id,
            'changes' => $event->changes,
            'actor_id' => $event->actorId,
        ]);
    }
}
