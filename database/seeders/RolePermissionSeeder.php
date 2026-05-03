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
                'users.view',
                'users.create',
                'users.update',
                'users.delete',
                'users.restore',

                'members.view',
                'members.create',
                'members.update',
                'members.delete',
                'members.restore',

                'hobbies.view',
                'hobbies.create',
                'hobbies.update',
                'hobbies.delete',

                'members.hobbies.view',
                'members.hobbies.attach',
                'members.hobbies.sync',
                'members.hobbies.detach',
            ],

            'staff' => [
                'members.view',
                'members.create',
                'members.update',

                'hobbies.view',
                'hobbies.create',
                'hobbies.update',

                'members.hobbies.view',
                'members.hobbies.attach',
                'members.hobbies.sync',
                'members.hobbies.detach',
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
