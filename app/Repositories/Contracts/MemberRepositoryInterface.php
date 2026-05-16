<?php

namespace App\Repositories\Contracts;

use App\Models\Member;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface MemberRepositoryInterface
{
    public function paginate(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): Member;
    public function create(array $data): Member;
    public function update(Member $member, array $data): Member;
    public function delete(Member $member): bool;
}
