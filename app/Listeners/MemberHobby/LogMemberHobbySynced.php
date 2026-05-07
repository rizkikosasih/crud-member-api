<?php

namespace App\Listeners\MemberHobby;

use App\Events\MemberHobby\MemberHobbySyncedEvent;
use Illuminate\Support\Facades\Log;

class LogMemberHobbySynced
{
    public function handle(MemberHobbySyncedEvent $event): void
    {
        Log::channel('daily')->info('Member-Hobby Synced', [
            'member_id' => $event->member->id,
            'hobbies' => $event->hobbies,
            'actor_id' => $event->actorId,
        ]);
    }
}
