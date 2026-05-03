<?php

namespace App\Services;

use App\Models\Hobby;
use App\Repositories\HobbyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class HobbyService
{
    public function __construct(protected HobbyRepository $hobbyRepository) {}

    private function normalizeName(string $name): string
    {
        return Str::of($name)->trim()->lower()->replaceMatches('/\s+/', ' ')->title(); // <- ini bikin tiap kata kapital
    }

    public function index(array $filters): LengthAwarePaginator
    {
        return $this->hobbyRepository->paginate($filters);
    }

    public function show(Hobby $hobby): Hobby
    {
        return $hobby;
    }

    public function create(array $data): Hobby
    {
        $data['name'] = $this->normalizeName($data['name']);

        return $this->hobbyRepository->create($data);
    }

    public function update(Hobby $hobby, array $data): Hobby
    {
        $data['name'] = $this->normalizeName($data['name']);

        return $this->hobbyRepository->update($hobby, $data);
    }

    public function delete(Hobby $hobby): void
    {
        $this->hobbyRepository->delete($hobby);
    }
}
