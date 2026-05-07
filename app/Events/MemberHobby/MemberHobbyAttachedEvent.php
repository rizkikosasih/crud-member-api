<?php

namespace App\Events\MemberHobby;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberHobbyAttachedEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public Member $member, public ?int $actorId) {}
}
