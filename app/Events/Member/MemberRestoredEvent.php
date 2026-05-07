<?php

namespace App\Events\Member;

use App\Models\Member;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MemberRestoredEvent
{
    use Dispatchable, SerializesModels;

    public function __construct(public Member $member, public int $actorId) {}
}
