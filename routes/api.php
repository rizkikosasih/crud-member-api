<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\HobbyController;
use App\Http\Controllers\Api\MemberController;
use App\Http\Controllers\Api\UserController;

/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
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

/*
|--------------------------------------------------------------------------
| ACCOUNT
|--------------------------------------------------------------------------
*/
Route::prefix('account')
    ->middleware('auth:api')
    ->controller(AccountController::class)
    ->group(function () {
        Route::get('me', 'me')->name('api.account.me');

        Route::post('change-password', 'changePassword')->name('api.account.change-password');

        Route::patch('/', 'update')->name('api.account.update');
    });

/*
|--------------------------------------------------------------------------
| USERS
|--------------------------------------------------------------------------
*/
Route::middleware('auth:api')
    ->prefix('users')
    ->controller(UserController::class)
    ->group(function () {
        Route::get('/', 'index')->name('api.users.index')->middleware('permission:users.view');

        Route::post('/', 'store')->name('api.users.store')->middleware('permission:users.create');

        Route::get('{user}', 'show')->name('api.users.show')->middleware('permission:users.view');

        Route::put('{user}', 'update')
            ->name('api.users.update')
            ->middleware('permission:users.update');

        Route::patch('{user}', 'patch')
            ->name('api.users.patch')
            ->middleware('permission:users.update');

        Route::delete('{user}', 'destroy')
            ->name('api.users.destroy')
            ->middleware('permission:users.delete');

        Route::patch('{user}/restore', 'restore')
            ->name('api.users.restore')
            ->middleware('permission:users.restore')
            ->withTrashed();
    });

/*
|--------------------------------------------------------------------------
| MEMBERS
|--------------------------------------------------------------------------
*/
Route::prefix('members')
    ->middleware('auth:api')
    ->controller(MemberController::class)
    ->group(function () {
        Route::get('/', 'index')->name('api.members.index')->middleware('permission:members.view');

        Route::post('/', 'store')
            ->name('api.members.store')
            ->middleware('permission:members.create');

        Route::get('{member}', 'show')
            ->name('api.members.show')
            ->middleware('permission:members.view');

        Route::put('{member}', 'update')
            ->name('api.members.update')
            ->middleware('permission:members.update');

        Route::patch('{member}', 'patch')
            ->name('api.members.patch')
            ->middleware('permission:members.update');

        Route::delete('{member}', 'destroy')
            ->name('api.members.destroy')
            ->middleware('permission:members.delete');

        Route::patch('{member}/restore', 'restore')
            ->name('api.members.restore')
            ->middleware('permission:members.restore')
            ->withTrashed(['member']);

        /*
        |--------------------------------------------------------------------------
        | MEMBER - HOBBIES (RELATION)
        |--------------------------------------------------------------------------
        */

        Route::get('{member}/hobbies', 'hobbies')
            ->name('api.members.hobbies.index')
            ->middleware('permission:members.hobbies.view');

        Route::post('{member}/hobbies', 'attachHobbies')
            ->name('api.members.hobbies.attach')
            ->middleware('permission:members.hobbies.attach');

        Route::put('{member}/hobbies', 'syncHobbies')
            ->name('api.members.hobbies.sync')
            ->middleware('permission:members.hobbies.sync');

        Route::delete('{member}/hobbies/{hobby}', 'detachHobby')
            ->name('api.members.hobbies.detach')
            ->middleware('permission:members.hobbies.detach');
    });

/*
|--------------------------------------------------------------------------
| HOBBIES
|--------------------------------------------------------------------------
*/
Route::prefix('hobbies')
    ->middleware('auth:api')
    ->controller(HobbyController::class)
    ->group(function () {
        Route::get('/', 'index')->name('api.hobbies.index')->middleware('permission:hobbies.view');

        Route::post('/', 'store')
            ->name('api.hobbies.store')
            ->middleware('permission:hobbies.create');

        Route::get('{hobby}', 'show')
            ->name('api.hobbies.show')
            ->middleware('permission:hobbies.view');

        Route::put('{hobby}', 'update')
            ->name('api.hobbies.update')
            ->middleware('permission:hobbies.update');

        Route::delete('{hobby}', 'destroy')
            ->name('api.hobbies.destroy')
            ->middleware('permission:hobbies.delete');
    });
