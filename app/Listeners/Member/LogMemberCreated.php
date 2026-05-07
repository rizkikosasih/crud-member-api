<?php

namespace App\Listeners\Member;

use App\Events\Member\MemberCreatedEvent;
use Illuminate\Support\Facades\Log;

class LogMemberCreated
{
    public function handle(MemberCreatedEvent $event): void
    {
        Log::channel('daily')->info('Member created', [
            'member_id' => $event->member->id,
            'actor_id' => $event->actorId,
        ]);
    }
}
