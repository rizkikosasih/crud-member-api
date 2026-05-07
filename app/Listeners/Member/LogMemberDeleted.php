<?php

namespace App\Listeners\Member;

use App\Events\Member\MemberDeletedEvent;
use Illuminate\Support\Facades\Log;

class LogMemberDeleted
{
    public function handle(MemberDeletedEvent $event): void
    {
        Log::channel('daily')->info('Member deleted', [
            'member_id' => $event->member->id,
            'actor_id' => $event->actorId,
        ]);
    }
}
