<?php

namespace App\Events\MemberHobby;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberHobbySyncedEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public Member $member, public array $hobbies, public ?int $actorId) {}
}
