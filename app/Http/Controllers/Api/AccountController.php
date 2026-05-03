<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Resources\UserResource;

class AccountController extends Controller
{
    public function __construct(private AccountService $accountService) {}

    public function me()
    {
        return ApiResponse::success(new UserResource($this->accountService->me()));
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return ApiResponse::success(
            new UserResource($this->accountService->changePassword($request->validated())),
            'Password has changed.',
        );
    }
}
