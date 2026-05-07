<?php

namespace App\Listeners\MemberHobby;

use App\Events\MemberHobby\MemberHobbyAttachedEvent;
use Illuminate\Support\Facades\Log;

class LogMemberHobbyAttached
{
    public function handle(MemberHobbyAttachedEvent $event): void
    {
        Log::channel('daily')->info('Member-Hobby Attached', [
            'member_id' => $event->member->id,
            'actor_id' => $event->actorId,
        ]);
    }
}
