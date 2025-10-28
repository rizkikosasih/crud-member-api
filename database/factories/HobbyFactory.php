<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Hobby;

class HobbyFactory extends Factory
{
    protected $model = Hobby::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word,
        ];
    }
}
