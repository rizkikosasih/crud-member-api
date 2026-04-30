<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

/*Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});*/

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['jwt.auth'])->group(function () {
    Route::get('me', [AuthController::class, 'me']);

    Route::apiResource('users', UserController::class);

    Route::prefix('profile')->group(function () {
        Route::put('password', [UserController::class, 'updatePassword'])->name(
            'profile.password.update',
        );
    }); // endpoint /api/profile/password

    Route::post('logout', [AuthController::class, 'logout']);
});
