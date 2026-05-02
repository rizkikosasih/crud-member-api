<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
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
        Route::get('/', 'index')->name('api.user.index')->middleware('permission:user.view');

        Route::post('/', 'store')->name('api.user.store')->middleware('permission:user.create');

        Route::get('/{user}', 'show')->name('api.user.show')->middleware('permission:user.view');

        Route::put('/{user}', 'update')
            ->name('api.user.update')
            ->middleware('permission:user.update');

        Route::delete('/{user}', 'destroy')
            ->name('api.user.destroy')
            ->middleware('permission:user.delete');

        Route::patch('/{user}/restore', 'restore')
            ->name('api.user.restore')
            ->middleware('permission:user.restore');
    });
