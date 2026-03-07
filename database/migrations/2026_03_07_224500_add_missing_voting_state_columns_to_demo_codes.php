<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: Ensure all voting state columns exist in demo_codes table
 *
 * The original CreateDemoCodesTable migration includes has_agreed_to_vote
 * and has_agreed_to_vote_at columns, but they may be missing from existing
 * databases that went through partial migrations.
 *
 * This ensures they exist before code tries to use them.
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Add voting state columns if they don't exist
            if (!Schema::hasColumn('demo_codes', 'vote_submitted')) {
                $table->boolean('vote_submitted')->default(false);
            }

            if (!Schema::hasColumn('demo_codes', 'vote_submitted_at')) {
                $table->dateTime('vote_submitted_at')->nullable();
            }

            // Add agreement tracking columns if they don't exist
            if (!Schema::hasColumn('demo_codes', 'has_agreed_to_vote')) {
                $table->boolean('has_agreed_to_vote')->default(false);
            }

            if (!Schema::hasColumn('demo_codes', 'has_agreed_to_vote_at')) {
                $table->dateTime('has_agreed_to_vote_at')->nullable();
            }

            // Add other potentially missing voting state columns
            if (!Schema::hasColumn('demo_codes', 'voting_started_at')) {
                $table->dateTime('voting_started_at')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'client_ip')) {
                $table->string('client_ip')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            $columns_to_drop = [];

            if (Schema::hasColumn('demo_codes', 'vote_submitted')) {
                $columns_to_drop[] = 'vote_submitted';
            }

            if (Schema::hasColumn('demo_codes', 'vote_submitted_at')) {
                $columns_to_drop[] = 'vote_submitted_at';
            }

            if (Schema::hasColumn('demo_codes', 'has_agreed_to_vote')) {
                $columns_to_drop[] = 'has_agreed_to_vote';
            }

            if (Schema::hasColumn('demo_codes', 'has_agreed_to_vote_at')) {
                $columns_to_drop[] = 'has_agreed_to_vote_at';
            }

            if (Schema::hasColumn('demo_codes', 'voting_started_at')) {
                $columns_to_drop[] = 'voting_started_at';
            }

            if (Schema::hasColumn('demo_codes', 'client_ip')) {
                $columns_to_drop[] = 'client_ip';
            }

            if (!empty($columns_to_drop)) {
                $table->dropColumn($columns_to_drop);
            }
        });
    }
};
