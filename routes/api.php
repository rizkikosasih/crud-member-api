<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::post('register', 'register')->name('auth.register');
        Route::post('login', 'login')->name('auth.login');

        Route::middleware('auth:api')->group(function () {
            Route::post('logout', 'logout')->name('auth.logout');
            Route::post('refresh', 'refresh')->name('auth.refresh');
        });
    });

Route::prefix('account')
    ->middleware('auth:api')
    ->controller(AccountController::class)
    ->group(function () {
        Route::get('me', 'me')->name('account.me');
        Route::post('change-password', 'changePassword')->name('account.change-password');
    });
