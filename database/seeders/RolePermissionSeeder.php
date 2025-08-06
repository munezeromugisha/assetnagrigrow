<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'view dashboard',
            'manage farms',
            'manage fields',
            'manage input outputs',
            'export input outputs',
            'view statistics',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles and assign permissions
        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->givePermissionTo(Permission::all());

        $manager = Role::firstOrCreate(['name' => 'Farm Manager']);
        $manager->givePermissionTo([
            'view dashboard',
            'view statistics',
            'manage input outputs',
            'export input outputs',
        ]);

        $officer = Role::firstOrCreate(['name' => 'Field Officer']);
        $officer->givePermissionTo([
            'manage input outputs',
        ]);

        $viewer = Role::firstOrCreate(['name' => 'Viewer']);
        $viewer->givePermissionTo([
            'view dashboard',
            'view statistics',
        ]);

        $this->command->info('Roles and permissions seeded successfully.');
    }
}
