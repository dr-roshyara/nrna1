<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = ['member', 'election-committee', 'constitutional-council', 'auditor', 'admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // Create permissions
        $permissions = ['election.create', 'election.publish', 'election.vote', 'election.results.view'];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // Assign all permissions to admin
        $admin = Role::where('name', 'admin')->first();
        if ($admin) {
            $admin->givePermissionTo($permissions);
        }

        // Assign the committee's permissions per the recorded governance policy
        // (scripts/check_roles.php CHECK 4 — F-GATE-8: seeder was incomplete
        // relative to the gate's policy; aligned under EG-003)
        $committee = Role::where('name', 'election-committee')->first();
        if ($committee) {
            $committee->givePermissionTo(['election.create', 'election.publish', 'election.results.view']);
        }
    }
}