<?php

namespace App\Events\Account;

class AccountUpdatedEvent
{
    public function __construct(public string $userId, public array $changes) {}
}
