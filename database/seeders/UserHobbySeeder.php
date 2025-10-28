<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Hobby;

class UserHobbySeeder extends Seeder
{
    public function run(): void
    {
        User::factory(100)->create()->each(function ($user) {
            // Untuk setiap user, generate 1-5 hobby
            $hobbies = Hobby::factory(rand(1, 5))->create(['user_id' => $user->id]);
        });
    }
}
