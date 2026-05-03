<?php

namespace App\Services;

use App\Models\Member;
use App\Contracts\MemberRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MemberService
{
    public function __construct(protected MemberRepositoryInterface $memberRepository) {}

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

    public function show(Member $member): Member
    {
        $member->load('hobbies');

        return $member;
    }

    public function attachHobbies(Member $member, array $hobbyIds)
    {
        $existing = $member->hobbies()->pluck('hobby_id')->toArray();

        $filtered = array_values(array_diff($hobbyIds, $existing));

        if (empty($filtered)) {
            return $member->load('hobbies');
        }

        $member->hobbies()->attach($filtered);

        return $member->load('hobbies');
    }

    public function syncHobbies(Member $member, array $hobbyIds)
    {
        $member->hobbies()->sync($hobbyIds);

        return $member->load('hobbies');
    }

    public function detachHobby(Member $member, int $hobbyId)
    {
        $member->hobbies()->detach($hobbyId);

        return $member->load('hobbies');
    }

    public function getHobbies(Member $member)
    {
        return $member->hobbies;
    }
}
