<?php

namespace Database\Seeders;

use App\Models\Member;
use App\Models\Hobby;
use Illuminate\Database\Seeder;

class MemberHobbySeeder extends Seeder
{
    public function run(): void
    {
        $members = Member::all();
        $hobbies = Hobby::all();

        foreach ($members as $member) {
            // setiap member punya 1 - 4 hobby random
            $randomHobbies = $hobbies->random(rand(1, 4))->pluck('id');

            $member->hobbies()->sync($randomHobbies);
        }
    }
}
