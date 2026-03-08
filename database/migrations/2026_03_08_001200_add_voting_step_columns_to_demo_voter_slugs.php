<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add voting step columns and session management columns to demo_voter_slugs
     * to match the VoterSlug structure for consistency across demo and real voting
     */
    public function up(): void
    {
        Schema::table('demo_voter_slugs', function (Blueprint $table) {
            // Session management
            if (!Schema::hasColumn('demo_voter_slugs', 'expires_at')) {
                $table->timestamp('expires_at')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'is_active')) {
                $table->boolean('is_active')->default(true);
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'has_voted')) {
                $table->boolean('has_voted')->default(false);
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'can_vote_now')) {
                $table->boolean('can_vote_now')->default(true);
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'voting_time_in_minutes')) {
                $table->integer('voting_time_in_minutes')
                    ->default(30)
                    ->comment('Voting window duration in minutes');
            }

            // Step 1: Code verification
            if (!Schema::hasColumn('demo_voter_slugs', 'step_1_ip')) {
                $table->string('step_1_ip')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'step_1_completed_at')) {
                $table->timestamp('step_1_completed_at')->nullable();
            }

            // Step 2: Vote agreement
            if (!Schema::hasColumn('demo_voter_slugs', 'step_2_ip')) {
                $table->string('step_2_ip')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'step_2_completed_at')) {
                $table->timestamp('step_2_completed_at')->nullable();
            }

            // Step 3: Candidate selection
            if (!Schema::hasColumn('demo_voter_slugs', 'step_3_ip')) {
                $table->string('step_3_ip')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'step_3_completed_at')) {
                $table->timestamp('step_3_completed_at')->nullable();
            }

            // Step 4: Verification
            if (!Schema::hasColumn('demo_voter_slugs', 'step_4_ip')) {
                $table->string('step_4_ip')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'step_4_completed_at')) {
                $table->timestamp('step_4_completed_at')->nullable();
            }

            // Step 5: Completion
            if (!Schema::hasColumn('demo_voter_slugs', 'step_5_ip')) {
                $table->string('step_5_ip')->nullable();
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'step_5_completed_at')) {
                $table->timestamp('step_5_completed_at')->nullable();
            }

            // Indexes for performance
            if (!Schema::hasColumn('demo_voter_slugs', 'expires_at')) {
                $table->index('expires_at');
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'is_active')) {
                $table->index('is_active');
            }
            if (!Schema::hasColumn('demo_voter_slugs', 'has_voted')) {
                $table->index('has_voted');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_voter_slugs', function (Blueprint $table) {
            $table->dropColumn([
                'expires_at',
                'is_active',
                'has_voted',
                'can_vote_now',
                'voting_time_in_minutes',
                'step_1_ip',
                'step_1_completed_at',
                'step_2_ip',
                'step_2_completed_at',
                'step_3_ip',
                'step_3_completed_at',
                'step_4_ip',
                'step_4_completed_at',
                'step_5_ip',
                'step_5_completed_at',
            ]);
        });
    }
};
