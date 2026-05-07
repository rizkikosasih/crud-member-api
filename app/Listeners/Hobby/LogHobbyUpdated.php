<?php

namespace App\Listeners\Hobby;

use App\Events\Hobby\HobbyUpdatedEvent;
use Illuminate\Support\Facades\Log;

class LogHobbyUpdated
{
    public function handle(HobbyUpdatedEvent $event): void
    {
        Log::channel('daily')->info('Hobby updated', [
            'hobby_id' => $event->hobby->id,
            'name' => $event->name,
            'actor_id' => $event->actorId,
        ]);
    }
}
