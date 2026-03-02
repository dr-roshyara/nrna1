<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds status field to support multi-election architecture
     * Statuses: planned, active, completed, archived
     */
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            // Add status enum after is_active
            $table->enum('status', ['planned', 'active', 'completed', 'archived'])
                ->default('active')
                ->after('is_active');

            // Add index for status-based queries
            $table->index('status');

            // Add composite index for organisation + status
            $table->index(['organisation_id', 'status']);
        });

        // Set active status for existing active elections
        \DB::table('elections')
            ->where('is_active', true)
            ->update(['status' => 'active']);

        // Set planned status for existing inactive elections
        \DB::table('elections')
            ->where('is_active', false)
            ->update(['status' => 'planned']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Use raw SQL to safely drop indexes that may not exist
        try {
            \DB::statement('ALTER TABLE elections DROP INDEX elections_status_index');
        } catch (\Exception $e) {
            // Index doesn't exist, continue
        }

        try {
            \DB::statement('ALTER TABLE elections DROP INDEX elections_organisation_id_status_index');
        } catch (\Exception $e) {
            // Index doesn't exist, continue
        }

        Schema::table('elections', function (Blueprint $table) {
            if (Schema::hasColumn('elections', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
