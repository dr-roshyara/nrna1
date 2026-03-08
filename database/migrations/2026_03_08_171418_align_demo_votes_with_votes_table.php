<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Align demo_votes table structure with votes table
     * - Remove candidate_selections JSON column
     * - Add 60 candidate_XX columns (VARCHAR 36 for UUIDs)
     * - Make cast_at NOT NULL
     * - Add metadata JSON column
     *
     * NOTE: Using VARCHAR(36) instead of VARCHAR(255) for candidate columns
     * because they store UUIDs (36 chars max). This prevents MySQL row size
     * overflow error (65535 byte limit) on InnoDB tables.
     */
    public function up(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Drop the JSON column that's causing issues
            if (Schema::hasColumn('demo_votes', 'candidate_selections')) {
                $table->dropColumn('candidate_selections');
            }

            // Add 60 candidate columns (matching votes table structure)
            // Using VARCHAR(36) for UUID storage instead of VARCHAR(255)
            for ($i = 1; $i <= 60; $i++) {
                $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (!Schema::hasColumn('demo_votes', $columnName)) {
                    $table->string($columnName, 36)
                        ->nullable()
                        ->after('device_metadata_anonymized')
                        ->comment('Selected candidate UUID for position ' . $i);
                }
            }

            // Make cast_at NOT NULL to match votes table
            if (Schema::hasColumn('demo_votes', 'cast_at')) {
                $table->timestamp('cast_at')->nullable(false)->change();
            }

            // Add metadata JSON column if missing
            if (!Schema::hasColumn('demo_votes', 'metadata')) {
                $table->json('metadata')
                    ->nullable()
                    ->after('cast_at')
                    ->comment('Additional vote metadata');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Remove all candidate columns
            for ($i = 1; $i <= 60; $i++) {
                $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (Schema::hasColumn('demo_votes', $columnName)) {
                    $table->dropColumn($columnName);
                }
            }

            // Restore candidate_selections JSON column
            if (!Schema::hasColumn('demo_votes', 'candidate_selections')) {
                $table->json('candidate_selections')
                    ->nullable()
                    ->after('device_metadata_anonymized');
            }

            // Revert cast_at to nullable
            if (Schema::hasColumn('demo_votes', 'cast_at')) {
                $table->timestamp('cast_at')->nullable()->change();
            }

            // Drop metadata column if it exists
            if (Schema::hasColumn('demo_votes', 'metadata')) {
                $table->dropColumn('metadata');
            }
        });
    }
};
