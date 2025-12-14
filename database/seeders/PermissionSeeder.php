<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Modules / resources
        $modules = [
            'completion_types',
            'compliments',
            'departments',
            'permissions',
            'settings',
            'statuses',
            'users',
            'workers',
            'roles',
            'brands',
        ];

        // CRUD actions
        $actions = ['view', 'create', 'edit', 'delete'];

        $permissions = [];

        // Create CRUD permissions
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissions[] = Permission::firstOrCreate(
                    [
                        'name' => "{$action} {$module}",
                        'guard_name' => 'web',
                    ]
                );
            }
        }

        // Extra permissions
        $specialPermissions = [
            'assign roles',
            'manage permissions',
            'view dashboard',
            'view logs',
        ];

        foreach ($specialPermissions as $perm) {
            $permissions[] = Permission::firstOrCreate(
                [
                    'name' => $perm,
                    'guard_name' => 'web',
                ]
            );
        }

        /**
         * ==============================
         * Create Super Admin Role
         * ==============================
         */
        $superAdminRole = Role::firstOrCreate(
            [
                'name' => 'super-admin',
                'guard_name' => 'web',
            ]
        );

        // Give role all permissions
        $superAdminRole->syncPermissions(Permission::all());

        /**
         * ==============================
         * Assign ALL permissions to FIRST USER
         * ==============================
         */
        $firstUser = User::first(); // or User::find(1)

        if ($firstUser) {
            $firstUser->assignRole($superAdminRole);
            $firstUser->syncPermissions(Permission::all());
        }

        $this->command->info('✅ Permissions seeded & first user granted full access!');
    }
}
