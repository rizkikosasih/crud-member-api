<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\AuthResource;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService) {}

    public function register(RegisterRequest $request)
    {
        $result = $this->authService->register($request->validated());

        return ApiResponse::success(new AuthResource($result), 'User registered.', 201);
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
        return ApiResponse::success(new AuthResource($result), 'Login success.');
    }

    public function logout()
    {
        $this->authService->logout();

        return ApiResponse::success(null, 'Logout success.');
    }

    public function refresh()
    {
        $result = $this->authService->refresh();

        return ApiResponse::success(new AuthResource($result), 'Token Refreshed.');
    }
}
