<?php

namespace App\Services;

use App\Events\Hobby\HobbyCreatedEvent;
use App\Events\Hobby\HobbyUpdatedEvent;
use App\Models\Hobby;
use App\Repositories\HobbyRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HobbyService
{
    public function __construct(protected HobbyRepository $hobbyRepository) {}

    private function actorId(): ?int
    {
        return auth('api')->id();
    }

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
        return DB::transaction(function () use ($data) {
            $data['name'] = $this->normalizeName($data['name']);

            $hobby = $this->hobbyRepository->create($data);

            event(new HobbyCreatedEvent($hobby, $this->actorId()));

            return $hobby;
        });
    }

    public function update(Hobby $hobby, array $data): Hobby
    {
        return DB::transaction(function () use ($hobby, $data) {
            $data['name'] = $this->normalizeName($data['name']);

            $name = $hobby->name;

            $hobby = $this->hobbyRepository->update($hobby, $data);

            event(new HobbyUpdatedEvent($hobby, $name, $this->actorId()));

            return $hobby;
        });
    }

    public function delete(Hobby $hobby): void
    {
        DB::transaction(function () use ($hobby) {
            $this->hobbyRepository->delete($hobby);

            event(new HobbyCreatedEvent($hobby, $this->actorId()));
        });
    }
}
