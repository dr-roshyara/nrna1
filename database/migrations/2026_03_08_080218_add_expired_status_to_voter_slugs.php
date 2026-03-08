<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Extend the status enum to include 'expired' value for voter slugs
     * that have surpassed their expiration time
     */
    public function up(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Modify status enum to include 'expired' value
            // MySQL ENUM format: 'active', 'voted', 'expired', 'abstained'
            $table->enum('status', ['active', 'voted', 'expired', 'abstained'])
                ->default('active')
                ->change();
        });

        // Add status column to demo_voter_slugs if it doesn't exist
        if (Schema::hasTable('demo_voter_slugs') && !Schema::hasColumn('demo_voter_slugs', 'status')) {
            Schema::table('demo_voter_slugs', function (Blueprint $table) {
                $table->enum('status', ['active', 'voted', 'expired', 'abstained'])
                    ->default('active')
                    ->after('is_active');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to original enum values
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->enum('status', ['active', 'voted'])
                ->default('active')
                ->change();
        });

        // Drop status column from demo_voter_slugs if we added it
        if (Schema::hasTable('demo_voter_slugs') && Schema::hasColumn('demo_voter_slugs', 'status')) {
            // Only drop if it was added by this migration (check by dropping)
            Schema::table('demo_voter_slugs', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
