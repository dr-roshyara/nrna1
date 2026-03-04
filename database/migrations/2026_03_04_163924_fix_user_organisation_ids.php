<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix users with stale organisation_ids
     *
     * Problem: Some users have org_id > 1 but no valid pivot record for that organisation.
     * This causes redirect logic to attempt redirecting to non-existent organisations → 403 errors.
     *
     * Solution: For any user with org_id > 1 but NO pivot for that org:
     * 1. Reset their org_id to 1 (platform organisation)
     * 2. Ensure they have a pivot for org_id=1
     * 3. Remove any invalid pivots
     */
    public function up(): void
    {
        $users = \Illuminate\Support\Facades\DB::table('users')
            ->where('organisation_id', '>', 1)
            ->get();

        foreach ($users as $user) {
            // Check if user has a pivot for their assigned organisation
            $hasPivotForAssignedOrg = \Illuminate\Support\Facades\DB::table('user_organisation_roles')
                ->where('user_id', $user->id)
                ->where('organisation_id', $user->organisation_id)
                ->exists();

            if (!$hasPivotForAssignedOrg) {
                // User has org_id > 1 but no valid pivot - reset to platform org
                \Illuminate\Support\Facades\DB::table('users')
                    ->where('id', $user->id)
                    ->update(['organisation_id' => 1]);

                // Remove any bad pivots for the stale org_id
                \Illuminate\Support\Facades\DB::table('user_organisation_roles')
                    ->where('user_id', $user->id)
                    ->where('organisation_id', $user->organisation_id)
                    ->delete();

                \Illuminate\Support\Facades\Log::info('Migration: Fixed stale org_id for user', [
                    'user_id' => $user->id,
                    'email' => $user->email,
                    'old_org_id' => $user->organisation_id,
                    'new_org_id' => 1,
                ]);
            }
        }

        // Ensure all users have at least a platform organisation pivot
        $usersWithoutPlatformPivot = \Illuminate\Support\Facades\DB::table('users')
            ->leftJoin('user_organisation_roles', function ($join) {
                $join->on('users.id', '=', 'user_organisation_roles.user_id')
                    ->where('user_organisation_roles.organisation_id', 1);
            })
            ->whereNull('user_organisation_roles.user_id')
            ->select('users.id', 'users.email')
            ->distinct()
            ->get();

        foreach ($usersWithoutPlatformPivot as $user) {
            \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insertOrIgnore([
                'user_id' => $user->id,
                'organisation_id' => 1,
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            \Illuminate\Support\Facades\Log::info('Migration: Created missing platform pivot for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'organisation_id' => 1,
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't delete pivots on rollback - too risky
        // Users should keep their platform org access
    }
};
