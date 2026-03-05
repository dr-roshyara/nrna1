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
        Schema::table('elections', function (Blueprint $table) {
            $table->dropIndex(['status']);
            $table->dropIndex(['organisation_id', 'status']);
            $table->dropColumn('status');
        });
    }
};
