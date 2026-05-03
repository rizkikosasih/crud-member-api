<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\HobbyController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\UserController;

/**
 * Authentication
 */
Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('register', 'register')->name('api.auth.register');
        Route::post('login', 'login')->name('api.auth.login');

        Route::middleware('auth:api')->group(function () {
            Route::post('logout', 'logout')->name('api.auth.logout');
            Route::post('refresh', 'refresh')->name('api.auth.refresh');
        });
    });

/**
 * Account Management
 */
Route::prefix('account')
    ->middleware('auth:api')
    ->controller(AccountController::class)
    ->group(function () {
        Route::get('me', 'me')->name('api.account.me');
        Route::post('change-password', 'changePassword')->name('api.account.change-password');
    });

/**
 * User Management
 */
Route::middleware(['auth:api', 'role:admin'])
    ->prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'index')->name('api.users.index')->middleware('permission:user.view');

        Route::post('/', 'store')->name('api.users.store')->middleware('permission:user.create');

        Route::get('/{user}', 'show')->name('api.users.show')->middleware('permission:user.view');

        Route::put('/{user}', 'update')
            ->name('api.users.update')
            ->middleware('permission:user.update');

        Route::delete('/{user}', 'destroy')
            ->name('api.users.destroy')
            ->middleware('permission:user.delete');

        Route::patch('/{user}/restore', 'restore')
            ->name('api.users.restore')
            ->middleware('permission:user.restore')
            ->withTrashed();
    });

/**
 * Member Management
 */
Route::prefix('members')
    ->controller(MemberController::class)
    ->group(function () {
        Route::get('/', 'index')->name('api.members.index')->middleware('permission:member.view');
        Route::post('/', 'store')
            ->name('api.members.create')
            ->middleware('permission:member.create');
        Route::get('{member}', 'show')
            ->name('api.members.detail')
            ->middleware('permission:member.view');
        Route::put('{member}', 'update')
            ->name('api.members.update')
            ->middleware('permission:member.update');
        Route::delete('{member}', 'destroy')
            ->name('api.members.delete')
            ->middleware('permission:member.delete');
        Route::patch('{member}/restore', 'restore')
            ->name('api.members.restore')
            ->middleware('permission:member.restore')
            ->withTrashed();

        /**
         * MEMBER-HOBBY RELATION
         */
        Route::post('{member}/hobbies', 'attachHobbies')
            ->name('api.members.attachHobbies')
            ->middleware('permission:member.update');
        Route::put('{member}/hobbies', 'syncHobbies')
            ->name('api.members.syncHobbies')
            ->middleware('permission:member.update');
        Route::delete('{member}/hobbies/{hobby}', 'detachHobby')
            ->name('api.members.detachHobby')
            ->middleware('permission:member.update');
        Route::get('{member}/hobbies', 'hobbies')
            ->name('api.members.hobbies')
            ->middleware('permission:member.view');
    });

/**
 * Hobby Management
 */
Route::prefix('hobbies')
    ->controller(HobbyController::class)
    ->group(function () {
        Route::get('/', 'index')->name('api.hobbies.index')->middleware('permission:hobby.view');

        Route::post('/', 'store')
            ->name('api.hobbies.create')
            ->middleware('permission:hobby.create');

        Route::get('{hobby}', 'show')
            ->name('api.hobbies.detail')
            ->middleware('permission:hobby.view');

        Route::put('{hobby}', 'update')
            ->name('api.hobbies.update')
            ->middleware('permission:hobby.update');

        Route::delete('{hobby}', 'destroy')
            ->name('api.hobbies.delete')
            ->middleware('permission:hobby.delete');
    });
