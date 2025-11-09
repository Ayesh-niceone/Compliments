<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // List of modules (controllers)
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

        // Standard CRUD actions
        $actions = ['view', 'create', 'edit', 'delete'];

        // Create all permissions
        $allPermissions = [];
        foreach ($modules as $module) {
            foreach ($actions as $action) {
                $permissionName = "{$action} {$module}";
                $permission = Permission::firstOrCreate(
                    ['name' => $permissionName],
                    ['guard_name' => 'web']
                );
                $allPermissions[] = $permission;
            }
        }

        // Special / global permissions
        $extraPermissions = [
            'assign roles',
            'manage permissions',
            'view dashboard',
        ];

        foreach ($extraPermissions as $perm) {
            $permission = Permission::firstOrCreate(['name' => $perm], ['guard_name' => 'web']);
            $allPermissions[] = $permission;
        }

        // -------------------------------
        // Create Roles
        // -------------------------------
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $supervisor = Role::firstOrCreate(['name' => 'Supervisor']);
        $customerCare = Role::firstOrCreate(['name' => 'Customer Care']);

        // -------------------------------
        // Assign Permissions
        // -------------------------------

        // Admin → full access
        $admin->syncPermissions($allPermissions);

        // Supervisor → can view, create, edit (but not delete)
        $supervisorPermissions = Permission::where(function ($q) {
            $q->where('name', 'like', 'view%')
              ->orWhere('name', 'like', 'create%')
              ->orWhere('name', 'like', 'edit%')
              ->orWhere('name', 'view dashboard');
        })->get();
        $supervisor->syncPermissions($supervisorPermissions);

        // Customer Care → limited access
        $customerCarePermissions = Permission::whereIn('name', [
            'view users',
            'view compliments',
            'create compliments',
            'edit compliments',
            'view statuses',
            'view dashboard',
        ])->get();
        $customerCare->syncPermissions($customerCarePermissions);

        $this->command->info('✅ Roles and Permissions seeded successfully!');
    }
}
