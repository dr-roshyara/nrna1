<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Fix demo_posts unique constraint to be composite on (post_id, election_id)
 *
 * REASON: post_id must be unique WITHIN an election, not globally.
 * The same post_id (e.g., 'PRES') can exist in multiple elections.
 *
 * BEFORE: post_id has a simple UNIQUE constraint (wrong!)
 * AFTER: (post_id, election_id) has a UNIQUE constraint (correct!)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demo_posts', function (Blueprint $table) {
            // Drop the old unique constraint on post_id
            try {
                $table->dropUnique(['post_id']);
            } catch (\Exception $e) {
                // Constraint might not exist with this default name
                // Try dropping by finding the actual constraint name
                try {
                    DB::statement('ALTER TABLE demo_posts DROP INDEX post_id');
                } catch (\Exception $e2) {
                    // If still fails, continue - might be during fresh install
                }
            }

            // Add the new composite unique constraint
            $table->unique(['post_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::table('demo_posts', function (Blueprint $table) {
            // Drop the composite unique constraint
            try {
                $table->dropUnique(['post_id', 'election_id']);
            } catch (\Exception $e) {
                // Constraint might not exist
            }

            // Restore the old simple unique constraint
            try {
                $table->unique(['post_id']);
            } catch (\Exception $e) {
                // If it fails, that's okay - rollback might be on fresh install
            }
        });
    }
};
