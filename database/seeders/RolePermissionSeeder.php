<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cache permission
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        /*
        |--------------------------------------------------------------------------
        | Role & Permissions
        |--------------------------------------------------------------------------
        */

        $rolePermissions = [
            'admin' => [
                'user.view',
                'user.create',
                'user.update',
                'user.delete',
                'user.restore',
                'member.view',
                'member.create',
                'member.update',
                'member.delete',
                'member.restore',
                'hobby.view',
                'hobby.create',
                'hobby.update',
                'hobby.delete',
            ],

            'staff' => [
                'member.view',
                'member.create',
                'member.update',
                'member.delete',
                'hobby.view',
                'hobby.create',
                'hobby.update',
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Create Role & Assign Permissions
        |--------------------------------------------------------------------------
        */

        foreach ($rolePermissions as $roleName => $permissions) {
            $role = Role::firstOrCreate([
                'name' => $roleName,
                'guard_name' => 'api',
            ]);

            $permissionModels = [];

            foreach ($permissions as $permission) {
                $permissionModels[] = Permission::firstOrCreate([
                    'name' => $permission,
                    'guard_name' => 'api',
                ]);
            }

            $role->syncPermissions($permissionModels);
        }
    }
}
