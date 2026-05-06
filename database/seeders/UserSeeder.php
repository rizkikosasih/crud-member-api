<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::findByName('admin');
        $staffRole = Role::findByName('staff');

        $rizki = User::firstOrCreate(
            ['email' => 'rizki@mail.com'],
            [
                'name' => 'Rizki Kosasih',
                'password' => Hash::make('rahasia123'),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $rizki->assignRole($adminRole);

        $admin = User::firstOrCreate(
            ['email' => 'admin@mail.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make(config('user.defaults.password')),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $admin->assignRole($adminRole);

        $staff = User::firstOrCreate(
            ['email' => 'staff@mail.com'],
            [
                'name' => 'Staff User',
                'password' => Hash::make(config('user.defaults.password')),
                'is_active' => true,
                'email_verified_at' => now(),
            ],
        );

        $staff->assignRole($staffRole);

        // Optional: bulk fake users (tanpa role atau staff default)
        User::factory()->count(5)->create()->each(fn($user) => $user->assignRole($staffRole));
    }
}
