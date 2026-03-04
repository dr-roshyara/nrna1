<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * RESTORE DEMO TABLES
     *
     * This migration restores the 6 demo_* tables that were missing from
     * the consolidated 2026-03-01 migrations. These tables are used for
     * the demo voting mode (testing elections without affecting real votes).
     *
     * Tables restored:
     * - demo_candidacies (demo candidates)
     * - demo_codes (demo voting codes)
     * - demo_results (demo voting results)
     * - demo_voter_slug_steps (demo voting progress)
     * - demo_voter_slugs (demo voting sessions)
     * - demo_votes (demo votes cast)
     *
     * Related Audit: database/audit_scripts/AUDIT_ANALYSIS.md
     */
    public function up(): void
    {
        // ========================================
        // 1. DEMO_CANDIDACIES TABLE
        // ========================================
        if (!Schema::hasTable('demo_candidacies')) {
            Schema::create('demo_candidacies', function (Blueprint $table) {
                $table->id();
                $table->string('candidacy_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->string('user_name')->nullable();
                $table->string('candidacy_name')->nullable();
                $table->unsignedBigInteger('election_id');
                $table->unsignedBigInteger('post_id');
                $table->string('post_name')->nullable();
                $table->string('post_nepali_name')->nullable();
                $table->unsignedBigInteger('proposer_id')->nullable();
                $table->string('proposer_name')->nullable();
                $table->unsignedBigInteger('supporter_id')->nullable();
                $table->string('supporter_name')->nullable();
                $table->string('image_path_1')->nullable();
                $table->string('image_path_2')->nullable();
                $table->string('image_path_3')->nullable();
                $table->unsignedBigInteger('organisation_id')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                $table->foreign('post_id')->references('id')->on('demo_posts')->onDelete('cascade');
                if (Schema::hasTable('organisations')) {
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
                }

                // Indexes
                $table->index('election_id');
                $table->index('post_id');
                $table->index('user_id');
            });
        }

        // ========================================
        // 2. DEMO_CODES TABLE
        // ========================================
        if (!Schema::hasTable('demo_codes')) {
            Schema::create('demo_codes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('election_id');
                $table->unsignedBigInteger('organisation_id')->nullable();
                $table->string('client_ip');


                // Two-code system
                $table->string('code1')->nullable();
                $table->string('code2')->nullable();
                $table->string('code3')->nullable();
                $table->string('code4')->nullable();
                $table->string('vote_show_code')->nullable();

                // Code state
                $table->boolean('is_code1_usable')->default(1);
                $table->boolean('is_code2_usable')->default(1);
                $table->boolean('is_code3_usable')->default(0);
                $table->boolean('is_code4_usable')->default(0);

                // Code usage tracking
                $table->timestamp('code1_sent_at')->nullable();
                $table->timestamp('code2_sent_at')->nullable();
                $table->timestamp('code3_sent_at')->nullable();
                $table->timestamp('code4_sent_at')->nullable();
                $table->timestamp('code1_used_at')->nullable();
                $table->timestamp('code2_used_at')->nullable();
                $table->timestamp('code3_used_at')->nullable();
                $table->timestamp('code4_used_at')->nullable();

                // Sending status
                $table->boolean('has_code1_sent')->default(0);
                $table->boolean('has_code2_sent')->default(0);
                $table->boolean('has_used_code1')->default(0);
                $table->boolean('has_used_code2')->default(0);

                // Voting state
                $table->boolean('can_vote_now')->default(0);
                $table->boolean('has_voted')->default(0);
                $table->boolean('vote_submitted')->default(0);
                $table->timestamp('vote_submitted_at')->nullable();
                $table->timestamp('voting_started_at')->nullable();
                $table->boolean('has_agreed_to_vote')->default(0);
                $table->timestamp('has_agreed_to_vote_at')->nullable();

                // Metadata
                $table->unsignedInteger('voting_time_in_minutes')->default(30);
                $table->timestamp('vote_last_seen')->nullable();
                $table->string('client_ip')->nullable();
                $table->string('session_name')->nullable();
                $table->string('code_for_vote')->nullable();
                $table->json('metadata')->nullable();

                $table->timestamps();

                // Foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                if (Schema::hasTable('organisations')) {
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
                }

                // Indexes
                $table->unique(['election_id', 'user_id']);
                $table->index('code1');
                $table->index('code2');
                $table->index(['can_vote_now', 'has_voted']);
            });
        }

        // ========================================
        // 3. DEMO_RESULTS TABLE
        // ========================================
        if (!Schema::hasTable('demo_results')) {
            Schema::create('demo_results', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('election_id');
                $table->unsignedBigInteger('vote_id');
                $table->unsignedBigInteger('post_id');
                $table->unsignedBigInteger('candidacy_id')->nullable();
                $table->unsignedBigInteger('candidate_id')->nullable();
                $table->unsignedBigInteger('organisation_id')->nullable();
                $table->string('vote_hash')->nullable();
                $table->integer('vote_count')->default(1);
                $table->json('metadata')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                $table->foreign('post_id')->references('id')->on('demo_posts')->onDelete('cascade');
                $table->foreign('vote_id')->references('id')->on('demo_votes')->onDelete('cascade');
                $table->foreign('candidacy_id')->references('id')->on('demo_candidacies')->onDelete('set null');
                if (Schema::hasTable('organisations')) {
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
                }

                // Indexes
                $table->index('election_id');
                $table->index('post_id');
                $table->index('vote_id');
            });
        }

        // ========================================
        // 4. DEMO_VOTER_SLUGS TABLE
        // ========================================
        if (!Schema::hasTable('demo_voter_slugs')) {
            Schema::create('demo_voter_slugs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organisation_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('election_id');
                $table->string('slug')->unique();
                $table->timestamp('expires_at')->nullable();
                $table->boolean('is_active')->default(1);
                $table->unsignedTinyInteger('current_step')->default(1);
                $table->json('step_meta')->nullable();
                $table->boolean('has_voted')->default(0);
                $table->boolean('can_vote_now')->default(0);
                $table->unsignedInteger('voting_time_min')->default(30);

                // Step IP and completion tracking
                $table->string('step_1_ip')->nullable();
                $table->timestamp('step_1_completed_at')->nullable();
                $table->string('step_2_ip')->nullable();
                $table->timestamp('step_2_completed_at')->nullable();
                $table->string('step_3_ip')->nullable();
                $table->timestamp('step_3_completed_at')->nullable();
                $table->string('step_4_ip')->nullable();
                $table->timestamp('step_4_completed_at')->nullable();
                $table->string('step_5_ip')->nullable();
                $table->timestamp('step_5_completed_at')->nullable();

                $table->timestamps();

                // Foreign keys
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                if (Schema::hasTable('organisations')) {
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
                }

                // Indexes
                $table->index('election_id');
                $table->index('user_id');
                $table->index('slug');
            });
        }

        // ========================================
        // 5. DEMO_VOTER_SLUG_STEPS TABLE
        // ========================================
        if (!Schema::hasTable('demo_voter_slug_steps')) {
            Schema::create('demo_voter_slug_steps', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('organisation_id')->nullable();
                $table->unsignedBigInteger('demo_voter_slug_id');
                $table->string('slug');
                $table->unsignedBigInteger('election_id');
                $table->unsignedTinyInteger('step');
                $table->json('step_data')->nullable();
                $table->timestamp('completed_at')->nullable();
                $table->string('ip_address')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                // Foreign keys
                $table->foreign('demo_voter_slug_id')->references('id')->on('demo_voter_slugs')->onDelete('cascade');
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                if (Schema::hasTable('organisations')) {
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
                }

                // Indexes
                $table->index('election_id');
                $table->index('demo_voter_slug_id');
                $table->index('slug');
            });
        }

        // ========================================
        // 6. DEMO_VOTES TABLE
        // ========================================
        if (!Schema::hasTable('demo_votes')) {
            Schema::create('demo_votes', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('election_id');
                $table->unsignedBigInteger('organisation_id')->nullable();
                $table->string('vote_hash')->nullable();
                $table->boolean('no_vote_option')->default(0);
                $table->string('voting_code')->nullable();

                // Candidate selections (60 candidates max per post)
                for ($i = 1; $i <= 60; $i++) {
                    $columnName = 'candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT);
                    $table->json($columnName)->nullable();
                }

                // Metadata
                $table->json('metadata')->nullable();
                $table->timestamp('cast_at')->nullable();
                $table->string('client_ip')->nullable();

                $table->timestamps();

                // Foreign keys
                $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                if (Schema::hasTable('organisations')) {
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('set null');
                }

                // Indexes
                $table->index('election_id');
                if (Schema::hasColumn('demo_votes', 'vote_hash')) {
                    $table->index('vote_hash');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_voter_slug_steps');
        Schema::dropIfExists('demo_voter_slugs');
        Schema::dropIfExists('demo_results');
        Schema::dropIfExists('demo_votes');
        Schema::dropIfExists('demo_codes');
        Schema::dropIfExists('demo_candidacies');
    }
};
