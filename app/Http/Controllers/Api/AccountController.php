<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Http\Requests\Auth\ChangePasswordRequest;

class AccountController extends Controller
{
    public function __construct(private AccountService $service) {}

    public function me()
    {
        return ApiResponse::success(
            $this->service->me()
        );
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return ApiResponse::success(
            $this->service->changePassword($request->validated()),
            'Password has changed.'
        );
    }
}
