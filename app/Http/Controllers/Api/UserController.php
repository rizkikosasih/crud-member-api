<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hobby;

class UserController extends Controller
{
    public function index()
    {
        return response()->json(User::with('hobbies')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'hobbies' => 'array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        foreach ($request->hobbies ?? [] as $h) {
            $user->hobbies()->create(['name' => $h]);
        }

        return response()->json($user->load('hobbies'), 201);
    }

    public function show($id)
    {
        return response()->json(User::with('hobbies')->findOrFail($id));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->update($request->only('name','email','password'));

        if ($request->has('hobbies')) {
            $user->hobbies()->delete();
            foreach ($request->hobbies as $h) {
                $user->hobbies()->create(['name' => $h]);
            }
        }

        return response()->json($user->load('hobbies'));
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
