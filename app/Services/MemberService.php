<?php

namespace App\Services;

use App\Models\Member;
use App\Contracts\MemberRepositoryInterface;
use App\Events\Member\MemberCreatedEvent;
use App\Events\Member\MemberDeletedEvent;
use App\Events\Member\MemberRestoredEvent;
use App\Events\Member\MemberUpdatedEvent;
use App\Events\MemberHobby\MemberHobbyAttachedEvent;
use App\Events\MemberHobby\MemberHobbyDetachedEvent;
use App\Events\MemberHobby\MemberHobbySyncedEvent;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class MemberService
{
    public function __construct(protected MemberRepositoryInterface $memberRepository) {}

    private function actorId(): ?int
    {
        return auth('api')->id();
    }

    private function filterUpdatableFields(array $data): array
    {
        return array_intersect_key($data, array_flip(['name', 'email', 'phone', 'is_active']));
    }

    public function index(array $filters): LengthAwarePaginator
    {
        return $this->memberRepository->paginate($filters);
    }

    public function show(Member $member): Member
    {
        $member->load('hobbies');

        return $member;
    }

    public function create(array $data): Member
    {
        return DB::transaction(function () use ($data) {
            $dirtyData = $this->filterUpdatableFields($data);

            $member = $this->memberRepository->create($dirtyData);

            if (array_key_exists('hobbies', $data)) {
                $member->hobbies()->sync($data['hobbies']);
            }

            event(new MemberCreatedEvent($member, $this->actorId()));

            return $member->load('hobbies');
        });
    }

    public function update(Member $member, array $data): Member
    {
        return DB::transaction(function () use ($member, $data) {
            $dirtyData = $this->filterUpdatableFields($data);

            $changes = [];

            if (!empty($dirtyData)) {
                $original = $member->only(array_keys($dirtyData));

                $changes = array_diff_assoc($dirtyData, $original);

                if (!empty($changes)) {
                    $member = $this->memberRepository->update($member, $dirtyData);
                }
            }

            if (array_key_exists('hobbies', $data)) {
                $hobbies = $data['hobbies'] ?? [];
                $member->hobbies()->sync($hobbies);
            }

            if (!empty($changes)) {
                event(new MemberUpdatedEvent($member, $changes, $this->actorId()));
            }

            return $member->load('hobbies');
        });
    }

    public function delete(Member $member): void
    {
        DB::transaction(function () use ($member) {
            $this->memberRepository->delete($member);

            event(new MemberDeletedEvent($member, $this->actorId()));
        });
    }

    public function restore(Member $member): Member
    {
        return DB::transaction(function () use ($member) {
            if (!$member->trashed()) {
                return $member->load('hobbies');
            }

            $member->restore();
            $member->load('hobbies');

            event(new MemberRestoredEvent($member, $this->actorId()));

            return $member;
        });
    }

    /**
     * Member-Hobby Relation
     */

    public function getHobbies(Member $member)
    {
        return $member->hobbies;
    }

    public function attachHobbies(Member $member, array $hobbyIds)
    {
        $existing = $member->hobbies()->pluck('hobby_id')->toArray();

        $filtered = array_values(array_diff($hobbyIds, $existing));

        if (empty($filtered)) {
            return $member->load('hobbies');
        }

        $member->hobbies()->attach($filtered);

        event(new MemberHobbyAttachedEvent($member, $this->actorId()));

        return $member->load('hobbies');
    }

    public function syncHobbies(Member $member, array $hobbyIds)
    {
        $hobbies = $member->hobbies()->pluck('hobby_id')->toArray();

        $member->hobbies()->sync($hobbyIds);

        event(new MemberHobbySyncedEvent($member, $hobbies, $this->actorId()));

        return $member->load('hobbies');
    }

    public function detachHobby(Member $member, int $hobbyId)
    {
        $member->hobbies()->detach($hobbyId);

        event(new MemberHobbyDetachedEvent($member, $hobbyId, $this->actorId()));

        return $member->load('hobbies');
    }
}
