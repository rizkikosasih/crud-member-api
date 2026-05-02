<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    public function register(RegisterRequest $request)
    {
        return ApiResponse::success(
            $this->service->register($request->validated()),
            'User registered.',
            201,
        );
    }

    public function login(LoginRequest $request)
    {
        return ApiResponse::success($this->service->login($request->validated()), 'Login success.');
    }

    public function logout()
    {
        $this->service->logout();

        return ApiResponse::success(null, 'Logout success.');
    }

    public function refresh()
    {
        return ApiResponse::success($this->service->refresh(), 'Token Refreshed.');
    }
}
