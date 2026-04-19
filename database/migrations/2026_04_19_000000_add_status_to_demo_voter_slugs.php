<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Add status column to demo_voter_slugs to match voter_slugs schema.
     * This ensures test setup and production schema are consistent.
     */
    public function up(): void
    {
        Schema::table('demo_voter_slugs', function (Blueprint $table) {
            // Add status column if it doesn't exist
            if (!Schema::hasColumn('demo_voter_slugs', 'status')) {
                $table->enum('status', ['active', 'voted', 'abstained'])
                      ->default('active')
                      ->after('current_step');
            }
        });
    }

    public function down(): void
    {
        Schema::table('demo_voter_slugs', function (Blueprint $table) {
            if (Schema::hasColumn('demo_voter_slugs', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
