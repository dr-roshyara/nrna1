<?php

namespace App\Console\Commands;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Console\Command;

class AssignUserToOrganisation extends Command
{
    protected $signature = 'organisation:assign-user {user_id} {organisation_id} {--role=owner}';
    protected $description = 'Assign a user to an organisation and set their current organisation';

    public function handle()
    {
        $userId = $this->argument('user_id');
        $organisationId = $this->argument('organisation_id');
        $role = $this->option('role');

        // Verify user exists
        $user = User::findOrFail($userId);
        $this->info("User: {$user->name} ({$user->id})");

        // Verify organisation exists
        $organisation = Organisation::findOrFail($organisationId);
        $this->info("Organisation: {$organisation->name} ({$organisation->id})");

        // Check if user already has a role in this organisation
        $existingRole = UserOrganisationRole::where('user_id', $userId)
            ->where('organisation_id', $organisationId)
            ->first();

        if ($existingRole) {
            $this->warn("User already has role '{$existingRole->role}' in this organisation");
            if ($this->confirm("Update role to '{$role}'?")) {
                $existingRole->update(['role' => $role]);
                $this->info("✓ Role updated to '{$role}'");
            }
        } else {
            // Create new role
            UserOrganisationRole::create([
                'user_id' => $userId,
                'organisation_id' => $organisationId,
                'role' => $role,
            ]);
            $this->info("✓ User assigned to organisation with role '{$role}'");
        }

        // Update user's current organisation_id
        $oldOrgId = $user->organisation_id;
        $user->update(['organisation_id' => $organisationId]);

        $this->info("\n✓ User's current organisation updated:");
        $this->line("  From: {$oldOrgId}");
        $this->line("  To:   {$organisationId}");

        // Verify the change
        $user->refresh();
        if ($user->organisation_id === $organisationId) {
            $this->info("\n✓ SUCCESS: User now belongs to {$organisation->name}");
        } else {
            $this->error("\n❌ FAILED: Update did not persist");
        }

        // Show user's organisations
        $this->info("\nUser's organisations:");
        UserOrganisationRole::where('user_id', $userId)
            ->with('organisation')
            ->get()
            ->each(fn($role) => $this->line("  • {$role->organisation->name} - Role: {$role->role}"));
    }
}
