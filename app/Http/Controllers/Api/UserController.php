<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hobby;
use App\Traits\ApiResponse;

class UserController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);

        $users = User::with('hobbies')->paginate($perPage);

        return $this->success($users, 'List of users retrieved successfully');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'hobbies' => 'array'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt("pass@123"),
        ]);

        foreach ($request->hobbies ?? [] as $h) {
            $user->hobbies()->create(['name' => $h]);
        }

        return $this->success($user->load('hobbies'), 'User created successfully', 201);
    }

    public function show($id)
    {
        $user = User::with('hobbies')->find($id);

        if (!$user) {
            return $this->error('User not found', 404);
        }

        return $this->success($user, 'User retrieved successfully');
    }

    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('User not found', 404);
        }

        $user->update($request->only('name', 'email', 'password'));

        if ($request->has('hobbies')) {
            $user->hobbies()->delete();

            foreach ($request->hobbies as $h) {
                $user->hobbies()->create(['name' => $h]);
            }
        }

        return $this->success($user->load('hobbies'), 'User updated successfully');
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return $this->error('User not found', 404);
        }

        $user->delete();

        return $this->success(null, 'User deleted successfully');
    }
}
