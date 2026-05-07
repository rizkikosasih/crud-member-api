<?php

namespace App\Listeners\Hobby;

use App\Events\Hobby\HobbyCreatedEvent;
use Illuminate\Support\Facades\Log;

class LogHobbyCreated
{
    public function handle(HobbyCreatedEvent $event): void
    {
        Log::channel('daily')->info('Hobby created', [
            'hobby_id' => $event->hobby->id,
            'actor_id' => $event->actorId,
        ]);
    }
}
