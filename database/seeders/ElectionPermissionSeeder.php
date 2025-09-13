<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class ElectionPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create election-related permissions
        $permissions = [
            'publish-election-results',
            'view-election-results',
            'manage-election-settings',
            'approve-voters',
            'manage-candidates',
            'export-election-data'
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create or get the election committee role
        $committeeRole = Role::firstOrCreate(['name' => 'election-committee']);

        // Assign all election permissions to committee role
        $committeeRole->givePermissionTo($permissions);

        $this->command->info('Election permissions and roles created successfully!');
    }
}
