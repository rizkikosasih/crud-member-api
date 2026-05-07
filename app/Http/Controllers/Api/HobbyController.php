<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\HobbyResource;
use App\Models\Hobby;
use App\Services\HobbyService;
use Illuminate\Http\Request;

class HobbyController extends Controller
{
    public function __construct(protected HobbyService $hobbyService) {}

    private function hobbyRules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
        ];
    }

    private function hobbyMessages(): array
    {
        return [
            'name.required' => 'Hobby name is required.',
            'name.string' => 'Hobby name must be a valid string.',
            'name.max' => 'Hobby name must not exceed 255 characters.',
        ];
    }

    public function index(Request $request)
    {
        $hobbies = $this->hobbyService->index($request->only(['per_page']));

        return ApiResponse::pagination(
            $hobbies,
            HobbyResource::class,
            'Hobbies retrieved successfully',
        );
    }

    public function show(Hobby $hobby)
    {
        $hobby = $this->hobbyService->show($hobby);

        return ApiResponse::success(new HobbyResource($hobby), 'Hobby retrieved successfully');
    }

    public function store(Request $request)
    {
        $validated = $request->validate($this->hobbyRules(), $this->hobbyMessages());

        $hobby = $this->hobbyService->create($validated);

        return ApiResponse::success(new HobbyResource($hobby), 'Hobby created successfully', 201);
    }

    public function update(Request $request, Hobby $hobby)
    {
        $validated = $request->validate($this->hobbyRules(), $this->hobbyMessages());

        $hobby = $this->hobbyService->update($hobby, $validated);

        return ApiResponse::success(new HobbyResource($hobby), 'Hobby updated successfully');
    }

    public function destroy(Hobby $hobby)
    {
        $this->hobbyService->delete($hobby);

        return ApiResponse::success(null, 'Hobby deleted successfully');
    }
}
