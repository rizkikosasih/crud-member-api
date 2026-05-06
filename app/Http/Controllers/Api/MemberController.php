<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\MemberHobbyRequest;
use App\Http\Requests\Member\StoreRequest;
use App\Http\Requests\Member\UpdateRequest;
use App\Http\Requests\Member\PatchRequest;
use App\Http\Resources\HobbyResource;
use App\Http\Resources\MemberResource;
use App\Models\Hobby;
use App\Models\Member;
use App\Services\MemberService;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function __construct(private MemberService $memberService) {}

    public function index(Request $request)
    {
        $filters = $request->only(['search', 'is_active', 'per_page']);

        $members = $this->memberService->index($filters);

        return ApiResponse::pagination(
            $members,
            MemberResource::class,
            'Members retrieved successfully',
        );
    }

    public function store(StoreRequest $request)
    {
        $member = $this->memberService->create($request->validated());

        return ApiResponse::success(new MemberResource($member), 'Member created successfully', 201);
    }

    public function show(Member $member)
    {
        return ApiResponse::success(
            new MemberResource($this->memberService->show($member)),
            'Member detail retrieved',
        );
    }

    public function update(UpdateRequest $request, Member $member)
    {
        $updated = $this->memberService->update($member, $request->validated());

        return ApiResponse::success(new MemberResource($updated), 'Member updated successfully');
    }

    public function patch(PatchRequest $request, Member $member)
    {
        $updated = $this->memberService->update($member, $request->validated());

        return ApiResponse::success(new MemberResource($updated), 'Member updated successfully');
    }

    public function destroy(Member $member)
    {
        $this->memberService->delete($member);

        return ApiResponse::success(null, 'Member deleted');
    }

    public function restore(Member $member)
    {
        $member = $this->memberService->restore($member);

        return ApiResponse::success(new MemberResource($member), 'Member restored');
    }

    public function attachHobbies(MemberHobbyRequest $request, Member $member)
    {
        $member = $this->memberService->attachHobbies($member, $request->validated('hobby_ids'));

        return ApiResponse::success(
            HobbyResource::collection($member->hobbies),
            'Hobbies attached successfully.',
        );
    }

    public function syncHobbies(MemberHobbyRequest $request, Member $member)
    {
        $member = $this->memberService->syncHobbies($member, $request->validated('hobby_ids'));

        return ApiResponse::success(
            HobbyResource::collection($member->hobbies),
            'Hobbies updated successfully.',
        );
    }

    public function detachHobby(Member $member, Hobby $hobby)
    {
        $member = $this->memberService->detachHobby($member, $hobby->id);

        return ApiResponse::success(
            HobbyResource::collection($member->hobbies),
            'Hobby removed successfully.',
        );
    }

    public function hobbies(Member $member)
    {
        $memberHobbies = $this->memberService->getHobbies($member);

        return ApiResponse::success(
            HobbyResource::collection($memberHobbies),
            'Member hobbies retrieved successfully.',
        );
    }
}
