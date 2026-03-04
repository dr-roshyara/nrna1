<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Backfill user_organisation_roles pivot table for all users who don't have entries.
     * This fixes the 403 "access denied" error for existing users without pivot entries.
     */
    public function up(): void
    {
        // Find all users who don't have a pivot entry for their organisation_id
        $usersWithoutPivot = DB::table('users')
            ->where('organisation_id', '>', 0)
            ->whereNotExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('user_organisation_roles')
                    ->whereColumn('user_organisation_roles.user_id', 'users.id')
                    ->whereColumn('user_organisation_roles.organisation_id', 'users.organisation_id');
            })
            ->select('id', 'organisation_id')
            ->get();

        $now = now();

        // Insert missing pivot entries
        foreach ($usersWithoutPivot as $user) {
            DB::table('user_organisation_roles')->insertOrIgnore([
                'user_id' => $user->id,
                'organisation_id' => $user->organisation_id,
                'role' => 'member',
                'assigned_at' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }

        $count = count($usersWithoutPivot);
        echo "✅ Backfilled {$count} users with pivot entries\n";
    }

    /**
     * Reverse the migrations.
     *
     * Remove all pivot entries that were created during backfill.
     * Note: This removes ALL pivot entries created after migration start time,
     * which could include legitimate entries created during this window.
     */
    public function down(): void
    {
        // We cannot reliably remove only the backfilled entries without
        // tracking which ones were created by this migration.
        // For safety, we log a warning instead of deleting.
        echo "⚠️ WARNING: Cannot safely reverse pivot table backfill.\n";
        echo "Please manually verify the user_organisation_roles table if needed.\n";
    }
};
