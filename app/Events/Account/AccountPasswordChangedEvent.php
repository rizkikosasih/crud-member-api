<?php

namespace App\Events\Account;

class AccountPasswordChangedEvent
{
    public function __construct(public string $userId, public string $ip) {}
}
