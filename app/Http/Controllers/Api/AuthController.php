<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;

class AuthController extends Controller
{
    public function __construct(private AuthService $service) {}

    public function register(RegisterRequest $request)
    {
        return response()->json(
            $this->service->register($request->validated())
        );
    }

    public function login(LoginRequest $request)
    {
        return response()->json(
            $this->service->login($request->validated())
        );
    }

    public function logout()
    {
        return response()->json(
            $this->service->logout()
        );
    }

    public function refresh()
    {
        return response()->json(
            $this->service->refresh()
        );
    }
}
