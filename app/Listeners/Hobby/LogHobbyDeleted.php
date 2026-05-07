<?php

namespace App\Listeners\Hobby;

use App\Events\Hobby\HobbyDeletedEvent;
use Illuminate\Support\Facades\Log;

class LogHobbyDeleted
{
    public function handle(HobbyDeletedEvent $event): void
    {
        Log::channel('daily')->info('Hobby deleted', [
            'hobby_id' => $event->hobby->id,
            'actor_id' => $event->actorId,
        ]);
    }
}
