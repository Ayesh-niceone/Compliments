<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // List of all your modules / controllers
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
        ];

        // Common CRUD actions
        $actions = ['view', 'create', 'edit', 'delete'];

        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = "{$action} {$module}";
                Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
            }
        }

        // Optionally add any special permissions
        $specialPermissions = [
            'assign roles',
            'manage permissions',
            'view dashboard',
            'view logs'
        ];

        foreach ($specialPermissions as $perm) {
            Permission::firstOrCreate(['name' => $perm], ['guard_name' => 'web']);
        }

        $this->command->info('✅ Permissions seeded successfully!');
    }
}
