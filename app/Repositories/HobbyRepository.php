<?php

namespace App\Repositories;

use App\Contracts\HobbyRepositoryInterface;
use App\Models\Hobby;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class HobbyRepository implements HobbyRepositoryInterface
{
    public function paginate(array $filters = []): LengthAwarePaginator
    {
        return Hobby::query()
            ->latest()
            ->paginate($filters['per_page'] ?? 10)
            ->withQueryString();
    }

    public function findById(int $id): Hobby
    {
        return Hobby::query()->findOrFail($id);
    }

    public function create(array $data): Hobby
    {
        return Hobby::create($data);
    }

    public function update(Hobby $hobby, array $data): Hobby
    {
        $hobby->update($data);
        return $hobby;
    }

    public function delete(Hobby $hobby): bool
    {
        return $hobby->delete();
    }
}
