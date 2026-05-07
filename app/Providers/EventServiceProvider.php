<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

// AUTH
use App\Events\Auth\LoginEvent;
use App\Events\Auth\RegisterEvent;
use App\Events\Auth\LogoutEvent;
use App\Events\Auth\RefreshEvent;
use App\Listeners\Auth\LogLoginActivity;
use App\Listeners\Auth\LogRegisterActivity;
use App\Listeners\Auth\LogLogoutActivity;
use App\Listeners\Auth\LogRefreshActivity;

// ACCOUNT
use App\Events\Account\AccountPasswordChangedEvent;
use App\Events\Account\AccountUpdatedEvent;
use App\Listeners\Account\LogAccountPasswordChanged;
use App\Listeners\Account\LogAccountUpdated;

// USER
use App\Events\User\UserCreatedEvent;
use App\Events\User\UserUpdatedEvent;
use App\Events\User\UserDeletedEvent;
use App\Events\User\UserRestoredEvent;
use App\Listeners\User\LogUserCreated;
use App\Listeners\User\LogUserUpdated;
use App\Listeners\User\LogUserDeleted;
use App\Listeners\User\LogUserRestored;

// MEMBER
use App\Events\Member\MemberCreatedEvent;
use App\Events\Member\MemberUpdatedEvent;
use App\Events\Member\MemberDeletedEvent;
use App\Events\Member\MemberRestoredEvent;
use App\Listeners\Member\LogMemberCreated;
use App\Listeners\Member\LogMemberUpdated;
use App\Listeners\Member\LogMemberDeleted;
use App\Listeners\Member\LogMemberRestored;

// HOBBY
use App\Events\Hobby\HobbyCreatedEvent;
use App\Events\Hobby\HobbyUpdatedEvent;
use App\Events\Hobby\HobbyDeletedEvent;
use App\Listeners\Hobby\LogHobbyCreated;
use App\Listeners\Hobby\LogHobbyUpdated;
use App\Listeners\Hobby\LogHobbyDeleted;

// MEMBER-HOBBY
use App\Events\MemberHobby\MemberHobbyAttachedEvent;
use App\Events\MemberHobby\MemberHobbySyncedEvent;
use App\Events\MemberHobby\MemberHobbyDetachedEvent;
use App\Listeners\MemberHobby\LogMemberHobbyAttached;
use App\Listeners\MemberHobby\LogMemberHobbySynced;
use App\Listeners\MemberHobby\LogMemberHobbyDetached;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        // AUTH
        LoginEvent::class => [LogLoginActivity::class],
        RegisterEvent::class => [LogRegisterActivity::class],
        LogoutEvent::class => [LogLogoutActivity::class],
        RefreshEvent::class => [LogRefreshActivity::class],

        // ACCOUNT
        AccountPasswordChangedEvent::class => [LogAccountPasswordChanged::class],
        AccountUpdatedEvent::class => [LogAccountUpdated::class],

        // USER
        UserCreatedEvent::class => [LogUserCreated::class],
        UserUpdatedEvent::class => [LogUserUpdated::class],
        UserDeletedEvent::class => [LogUserDeleted::class],
        UserRestoredEvent::class => [LogUserRestored::class],

        // MEMBER
        MemberCreatedEvent::class => [LogMemberCreated::class],
        MemberUpdatedEvent::class => [LogMemberUpdated::class],
        MemberDeletedEvent::class => [LogMemberDeleted::class],
        MemberRestoredEvent::class => [LogMemberRestored::class],

        // HOBBY
        HobbyCreatedEvent::class => [LogHobbyCreated::class],
        HobbyUpdatedEvent::class => [LogHobbyUpdated::class],
        HobbyDeletedEvent::class => [LogHobbyDeleted::class],

        // MEMBER-HOBBY
        MemberHobbyAttachedEvent::class => [LogMemberHobbyAttached::class],
        MemberHobbySyncedEvent::class => [LogMemberHobbySynced::class],
        MemberHobbyDetachedEvent::class => [LogMemberHobbyDetached::class],
    ];

    public function boot(): void
    {
        //
    }
}
