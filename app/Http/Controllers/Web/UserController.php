<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class UserController extends Controller
{
    public function index()
    {
        return view('users.index');
    }

    public function create()
    {
        return view('users.create');
    }

    public function edit(int $id)
    {
        return view('users.edit', ['userId' => $id]);
    }

    public function editPassword()
    {
        return view('users.edit-password');
    }
}
