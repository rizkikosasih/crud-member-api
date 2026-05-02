<?php

namespace App\Repositories;

use App\Contracts\MemberRepositoryInterface;
use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MemberRepository implements MemberRepositoryInterface
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return Member::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")->orWhere(
                        'email',
                        'like',
                        "%{$search}%",
                    );
                });
            })
            ->when(isset($filters['is_active']), function ($q) use ($filters) {
                $q->where('is_active', $filters['is_active']);
            })
            ->latest()
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    public function findById(int $id): Member
    {
        return Member::query()->findOrFail($id);
    }

    public function create(array $data): Member
    {
        return Member::create($data);
    }

    public function update(Member $member, array $data): Member
    {
        $member->update($data);
        return $member;
    }

    public function delete(Member $member): bool
    {
        return $member->delete();
    }
}
