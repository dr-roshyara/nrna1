<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Fix candidate column sizes to support JSON-encoded vote selections
     *
     * Previous migration (2026_03_08_171418) created VARCHAR(36) columns
     * assuming they would store UUIDs only. However, the code actually stores
     * JSON-encoded objects containing post_id, post_name, candidates array, etc.
     *
     * This causes data truncation. Expanding to TEXT to support full JSON data.
     */
    public function up(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Expand all 60 candidate columns from VARCHAR(36) to TEXT
            // This supports full JSON-encoded vote selection objects
            for ($i = 1; $i <= 60; $i++) {
                $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (Schema::hasColumn('demo_votes', $columnName)) {
                    $table->text($columnName)
                        ->nullable()
                        ->change();
                }
            }
        });

        // Also fix the votes table if it exists
        if (Schema::hasTable('votes')) {
            Schema::table('votes', function (Blueprint $table) {
                for ($i = 1; $i <= 60; $i++) {
                    $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    if (Schema::hasColumn('votes', $columnName)) {
                        $table->text($columnName)
                            ->nullable()
                            ->change();
                    }
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Revert to VARCHAR(36)
            for ($i = 1; $i <= 60; $i++) {
                $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (Schema::hasColumn('demo_votes', $columnName)) {
                    $table->string($columnName, 36)
                        ->nullable()
                        ->change();
                }
            }
        });

        if (Schema::hasTable('votes')) {
            Schema::table('votes', function (Blueprint $table) {
                for ($i = 1; $i <= 60; $i++) {
                    $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    if (Schema::hasColumn('votes', $columnName)) {
                        $table->string($columnName, 36)
                            ->nullable()
                            ->change();
                    }
                }
            });
        }
    }
};
