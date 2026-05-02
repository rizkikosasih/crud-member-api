<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\Member\StoreRequest;
use App\Http\Requests\Member\UpdateRequest;
use App\Http\Resources\MemberResource;
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

        return ApiResponse::success(new MemberResource($member), 'Member created successfully');
    }

    public function show(Member $member)
    {
        return ApiResponse::success(
            new MemberResource($this->memberService->detail($member)),
            'Member detail retrieved',
        );
    }

    public function update(UpdateRequest $request, Member $member)
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
}
