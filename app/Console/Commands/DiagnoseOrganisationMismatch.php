<?php

namespace App\Console\Commands;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Console\Command;

class DiagnoseOrganisationMismatch extends Command
{
    protected $signature = 'organisation:diagnose {user_id?} {election_id?}';
    protected $description = 'Diagnose organisation mismatch issues';

    public function handle()
    {
        // Find user (default: first user)
        $userId = $this->argument('user_id');
        $user = $userId
            ? User::findOrFail($userId)
            : User::first();

        if (!$user) {
            $this->error('No users found. Create a user first.');
            return;
        }

        $this->info("=== USER ORGANISATION DIAGNOSIS ===\n");
        $this->line("User: {$user->name} ({$user->id})");
        $this->line("Email: {$user->email}\n");

        // 1. Show user's current organisation_id
        $this->info("1. USER'S CURRENT ORGANISATION:");
        $currentOrg = $user->currentOrganisation;
        if ($currentOrg) {
            $this->line("   ID: {$currentOrg->id}");
            $this->line("   Name: {$currentOrg->name}");
            $this->line("   Slug: {$currentOrg->slug}");
            $this->line("   Type: {$currentOrg->type}");
        } else {
            $this->error("   ❌ Current organisation is NULL");
        }

        // 2. Show all organisations user belongs to
        $this->info("\n2. ALL ORGANISATIONS USER BELONGS TO:");
        $roles = UserOrganisationRole::where('user_id', $user->id)
            ->with('organisation')
            ->get();

        if ($roles->isEmpty()) {
            $this->error("   ❌ User has NO roles in any organisation!");
        } else {
            foreach ($roles as $role) {
                $org = $role->organisation;
                $isCurrent = $org->id === $user->organisation_id ? '✓ CURRENT' : '';
                $this->line("   • {$org->name} (ID: {$org->id}) - Role: {$role->role} {$isCurrent}");
            }
        }

        // 3. Show elections in user's current organisation
        if ($user->organisation_id) {
            $this->info("\n3. ELECTIONS IN YOUR CURRENT ORGANISATION:");
            $elections = \App\Models\Election::where('organisation_id', $user->organisation_id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();

            if ($elections->isEmpty()) {
                $this->line("   No elections found");
            } else {
                foreach ($elections as $election) {
                    $this->line("   • {$election->name} (ID: {$election->id}) - Type: {$election->type}");
                }
            }
        }

        // 4. Show elections in OTHER organisations
        $this->info("\n4. ELECTIONS IN OTHER ORGANISATIONS:");
        $otherElections = \App\Models\Election::whereNotIn('organisation_id', $roles->pluck('organisation_id')->toArray())
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($otherElections->isEmpty()) {
            $this->line("   No elections found in organisations you don't belong to");
        } else {
            foreach ($otherElections as $election) {
                $org = $election->organisation;
                $this->line("   • {$election->name} (ID: {$election->id})");
                $this->line("     Org: {$org->name} (ID: {$org->id})");
            }
        }

        // 5. If a specific election is given, check the mismatch
        $electionId = $this->argument('election_id');
        if ($electionId) {
            $this->info("\n5. SPECIFIC ELECTION CHECK:");
            $election = \App\Models\Election::findOrFail($electionId);
            $this->line("   Election: {$election->name}");
            $this->line("   Election's Org ID: {$election->organisation_id}");
            $this->line("   User's Org ID: {$user->organisation_id}");

            if ($election->organisation_id === $user->organisation_id) {
                $this->info("   ✓ MATCH - User can vote in this election");
            } else {
                $this->error("   ❌ MISMATCH - User cannot vote in this election");
                $this->line("\n   TO FIX: Update user's organisation_id");
            }
        }

        $this->info("\n=== SOLUTIONS ===\n");
        $this->line("Option 1: Use the web UI to switch organisations");
        $this->line("Option 2: Use command: artisan organisation:assign-user {user_id} {org_id}");
        $this->line("Option 3: Manually update the database (see instructions below)\n");
    }
}
