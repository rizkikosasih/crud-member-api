<?php

namespace App\Events\Hobby;

use App\Models\Hobby;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class HobbyUpdatedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public Hobby $hobby, public string $name, public ?int $actorId) {}
}
