<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Convert all 60 candidate_XX columns from VARCHAR(255) to TEXT
     *
     * Reason: Vote data (including JSON objects) may exceed 255 chars
     * TEXT allows up to 64KB per column, solving row size limit issues
     *
     * Impact: No data loss (TEXT can store everything VARCHAR could)
     */
    public function up(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Convert all 60 candidate columns to TEXT
            for ($i = 1; $i <= 60; $i++) {
                $column = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (Schema::hasColumn('demo_votes', $column)) {
                    $table->text($column)->nullable()->change();
                }
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Revert to VARCHAR(255) - note: data > 255 chars will be truncated!
            for ($i = 1; $i <= 60; $i++) {
                $column = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                if (Schema::hasColumn('demo_votes', $column)) {
                    $table->string($column, 255)->nullable()->change();
                }
            }
        });
    }
};
