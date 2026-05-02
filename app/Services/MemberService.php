<?php

namespace App\Services;

use App\Models\Member;
use App\Repositories\MemberRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MemberService
{
    public function __construct(protected MemberRepository $memberRepository) {}

    public function index(array $filters): LengthAwarePaginator
    {
        return $this->memberRepository->paginate($filters);
    }

    public function create(array $data): Member
    {
        return DB::transaction(function () use ($data) {
            $hobbies = $data['hobbies'] ?? [];
            unset($data['hobbies']);

            $member = $this->memberRepository->create($data);

            if ($hobbies) {
                $member->hobbies()->sync($hobbies);
            }

            return $member->load('hobbies');
        });
    }

    public function update(Member $member, array $data): Member
    {
        return DB::transaction(function () use ($member, $data) {
            $hobbies = $data['hobbies'] ?? null;
            unset($data['hobbies']);

            $member = $this->memberRepository->update($member, $data);

            if (!is_null($hobbies)) {
                $member->hobbies()->sync($hobbies);
            }

            return $member->load('hobbies');
        });
    }

    public function delete(Member $member): void
    {
        $this->memberRepository->delete($member);
    }

    public function restore(Member $member): Member
    {
        if (!$member->trashed()) {
            return $member->load('hobbies');
        }

        $member->restore();
        $member->load('hobbies');

        return $member;
    }

    public function detail(Member $member): Member
    {
        $member->load('hobbies');

        return $member;
    }
}
