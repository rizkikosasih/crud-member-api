<?php

namespace App\Providers;

use App\Repositories\Contracts\HobbyRepositoryInterface;
use App\Repositories\Contracts\MemberRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\HobbyRepository;
use App\Repositories\MemberRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\PermissionRegistrar;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(MemberRepositoryInterface::class, MemberRepository::class);
        $this->app->bind(HobbyRepositoryInterface::class, HobbyRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
