<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Http\Requests\Account\ChangePasswordRequest;
use App\Http\Requests\Account\UpdateRequest;
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

    public function update(UpdateRequest $request)
    {
        return ApiResponse::success(
            new UserResource($this->accountService->update($request->validated())),
            'Profile updated successfully.',
        );
    }
}
