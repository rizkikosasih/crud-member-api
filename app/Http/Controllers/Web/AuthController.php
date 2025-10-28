<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Services\AuthApiService;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    protected AuthApiService $authApi;

    public function __construct(AuthApiService $authApi)
    {
        $this->authApi = $authApi;
    }

    public function login()
    {
        return view('auth.login');
    }

    public function register()
    {
        return view('auth.register');
    }
}
