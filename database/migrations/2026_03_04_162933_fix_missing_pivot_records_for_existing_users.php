<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Fix existing users without pivot records in user_organisation_roles
     *
     * Problem: Users created before the pivot fallback system have no pivot records
     * This causes 403 "Sie haben keinen Zugriff auf diese Organisation" errors
     *
     * Solution: Add platform organisation (org_id=1) pivot for all users without any pivots
     */
    public function up(): void
    {
        // Find all users without ANY pivot records
        $usersWithoutPivot = DB::table('users')
            ->leftJoin('user_organisation_roles', 'users.id', '=', 'user_organisation_roles.user_id')
            ->whereNull('user_organisation_roles.user_id')
            ->select('users.id', 'users.email', 'users.organisation_id')
            ->distinct()
            ->get();

        $count = 0;
        foreach ($usersWithoutPivot as $user) {
            // Use the user's organisation_id, default to 1 (platform)
            $orgId = $user->organisation_id ?? 1;

            DB::table('user_organisation_roles')->insertOrIgnore([
                'user_id' => $user->id,
                'organisation_id' => $orgId,
                'role' => 'member',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $count++;

            Log::info('Migration: Fixed missing pivot for user', [
                'user_id' => $user->id,
                'email' => $user->email,
                'organisation_id' => $orgId,
            ]);
        }

        Log::info("Migration: Fixed {$count} existing users with missing pivot records");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't delete pivots on rollback - too risky
        // Users should keep their org access
    }
};
