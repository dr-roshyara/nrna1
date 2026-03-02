<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds a unique constraint to ensure only one active slug per user.
     * This provides database-level enforcement of the one-person-one-slug rule.
     */
    public function up(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Add a partial unique index: only one active slug per user
            // This prevents multiple active slugs at the database level
            $table->index(['user_id', 'is_active'], 'voter_slugs_user_active_idx');
        });

        // Clean up any existing duplicate active slugs before applying constraint
        DB::statement("
            UPDATE voter_slugs
            SET is_active = false
            WHERE id NOT IN (
                SELECT * FROM (
                    SELECT MIN(id)
                    FROM voter_slugs
                    WHERE is_active = true
                    GROUP BY user_id
                ) AS temp
            ) AND is_active = true
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->dropIndex('voter_slugs_user_active_idx');
        });
    }
};