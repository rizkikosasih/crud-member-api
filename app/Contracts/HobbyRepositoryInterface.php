<?php

namespace App\Contracts;

use App\Models\Hobby;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface HobbyRepositoryInterface
{
    public function paginate(array $filters = []): LengthAwarePaginator;
    public function findById(int $id): Hobby;
    public function create(array $data): Hobby;
    public function update(Hobby $hobby, array $data): Hobby;
    public function delete(Hobby $hobby): bool;
}
