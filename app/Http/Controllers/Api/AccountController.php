<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AccountService;
use App\Http\Requests\Auth\ChangePasswordRequest;

class AccountController extends Controller
{
    public function __construct(private AccountService $service) {}

    public function me()
    {
        return response()->json(
            $this->service->me()
        );
    }

    public function changePassword(ChangePasswordRequest $request)
    {
        return response()->json(
            $this->service->changePassword($request->validated())
        );
    }
}
