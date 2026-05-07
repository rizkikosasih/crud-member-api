<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\PatchRequest;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserController extends Controller
{
    public function __construct(protected UserService $userService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'per_page']);

        $users = $this->userService->index($filters);

        return ApiResponse::pagination(
            $users,
            UserResource::class,
            'Users retrieved successfully.',
        );
    }

    public function store(StoreRequest $request)
    {
        $user = $this->userService->store($request->validated());
        return ApiResponse::success(new UserResource($user), 'User retrieved successfully.', 201);
    }

    public function show(User $user)
    {
        return ApiResponse::success(
            new UserResource($this->userService->show($user)),
            'User detail showed.',
        );
    }

    public function update(UpdateRequest $request, User $user)
    {
        $this->authorize('update', $user);

        $user = $this->userService->update($user, $request->validated());
        return ApiResponse::success(new UserResource($user), 'User updated.');
    }

    public function patch(PatchRequest $request, User $user)
    {
        $this->authorize('patch', $user);

        $user = $this->userService->update($user, $request->validated());
        return ApiResponse::success(new UserResource($user), 'User updated.');
    }

    public function destroy(User $user)
    {
        $this->authorize('delete', $user);

        $this->userService->delete($user);

        return ApiResponse::success(null, 'User deleted.');
    }

    public function restore(User $user)
    {
        $this->authorize('restore', $user);

        $user = $this->userService->restore($user);

        return ApiResponse::success(new UserResource($user), 'User restored.');
    }
}
