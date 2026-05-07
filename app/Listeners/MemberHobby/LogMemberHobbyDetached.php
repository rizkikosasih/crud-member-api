<?php

namespace App\Listeners\MemberHobby;

use App\Events\MemberHobby\MemberHobbyDetachedEvent;
use Illuminate\Support\Facades\Log;

class LogMemberHobbyDetached
{
    public function handle(MemberHobbyDetachedEvent $event): void
    {
        Log::channel('daily')->info('Member-Hobby Detached', [
            'member_id' => $event->member->id,
            'hobby_id' => $event->hobbyId,
            'actor_id' => $event->actorId,
        ]);
    }
}
