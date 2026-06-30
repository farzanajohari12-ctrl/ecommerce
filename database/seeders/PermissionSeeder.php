<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define permissions exactly based on your sidebar structure
        $sidebarPermissions = [
            'view_dashboard',
            'manage_ecommerce',
            'manage_catalog',
            'manage_inventory',
            'manage_marketing',
            'manage_reports',
            'manage_access',
            'manage_system_settings',
        ];

        foreach ($sidebarPermissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Give all permissions to Admin role by default
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $adminRole->syncPermissions($sidebarPermissions);
    }
}
