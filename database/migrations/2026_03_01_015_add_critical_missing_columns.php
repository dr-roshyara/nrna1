<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * CRITICAL MISSING COLUMNS FIX
     *
     * This migration adds essential columns that were missing from the
     * consolidated 2026-03-01 migrations. These columns are REQUIRED for
     * the voting system to function properly.
     *
     * IMPORTANT: Base migration already has candidate_01-60 and other columns
     * Only adding: post_id, voting_code, and other missing pieces
     */
    public function up(): void
    {
        // ========================================
        // 1. VOTES TABLE - MOST CRITICAL
        // ========================================
        if (Schema::hasTable('votes')) {
            Schema::table('votes', function (Blueprint $table) {
                if (!Schema::hasColumn('votes', 'post_id')) {
                    $table->unsignedBigInteger('post_id')->nullable();
                    $table->foreign('post_id')->references('id')->on('posts')->onDelete('cascade');
                }
                if (!Schema::hasColumn('votes', 'voting_code')) {
                    $table->string('voting_code')->nullable();
                }
            });
        }

        // ========================================
        // 2. POSTS TABLE - CRITICAL
        // ========================================
        if (Schema::hasTable('posts')) {
            Schema::table('posts', function (Blueprint $table) {
                if (!Schema::hasColumn('posts', 'post_id')) {
                    $table->string('post_id')->nullable();
                    $table->unique('post_id');
                }
                if (!Schema::hasColumn('posts', 'nepali_name')) {
                    $table->string('nepali_name')->nullable();
                }
            });
        }

        // ========================================
        // 3. CANDIDACIES TABLE - CRITICAL
        // ========================================
        if (Schema::hasTable('candidacies')) {
            Schema::table('candidacies', function (Blueprint $table) {
                if (!Schema::hasColumn('candidacies', 'candidacy_id')) {
                    $table->string('candidacy_id')->nullable();
                    $table->unique('candidacy_id');
                }
                if (!Schema::hasColumn('candidacies', 'candidacy_name')) {
                    $table->string('candidacy_name')->nullable();
                }
                if (!Schema::hasColumn('candidacies', 'proposer_id')) {
                    $table->unsignedBigInteger('proposer_id')->nullable();
                }
                if (!Schema::hasColumn('candidacies', 'supporter_id')) {
                    $table->unsignedBigInteger('supporter_id')->nullable();
                }
                if (!Schema::hasColumn('candidacies', 'image_path_1')) {
                    $table->string('image_path_1')->nullable();
                }
                if (!Schema::hasColumn('candidacies', 'image_path_2')) {
                    $table->string('image_path_2')->nullable();
                }
                if (!Schema::hasColumn('candidacies', 'image_path_3')) {
                    $table->string('image_path_3')->nullable();
                }
            });
        }

        // ========================================
        // 4. VOTER_SLUGS TABLE - CRITICAL
        // ========================================
        if (Schema::hasTable('voter_slugs')) {
            Schema::table('voter_slugs', function (Blueprint $table) {
                if (!Schema::hasColumn('voter_slugs', 'user_id')) {
                    $table->unsignedBigInteger('user_id')->nullable();
                    $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                }
                if (!Schema::hasColumn('voter_slugs', 'election_id')) {
                    $table->unsignedBigInteger('election_id')->nullable();
                    $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
                }
                if (!Schema::hasColumn('voter_slugs', 'expires_at')) {
                    $table->timestamp('expires_at')->nullable();
                }
                if (!Schema::hasColumn('voter_slugs', 'is_active')) {
                    $table->boolean('is_active')->default(true);
                }
            });
        }

        // ========================================
        // 5. RESULTS TABLE - BACKWARD COMPATIBILITY
        // ========================================
        if (Schema::hasTable('results')) {
            Schema::table('results', function (Blueprint $table) {
                if (!Schema::hasColumn('results', 'candidacy_id')) {
                    $table->unsignedBigInteger('candidacy_id')->nullable();
                    $table->foreign('candidacy_id')->references('id')->on('candidacies')->onDelete('cascade');
                }
            });
        }

        // ========================================
        // 6. VOTER_REGISTRATIONS TABLE - HIGH PRIORITY
        // ========================================
        if (Schema::hasTable('voter_registrations')) {
            Schema::table('voter_registrations', function (Blueprint $table) {
                if (!Schema::hasColumn('voter_registrations', 'election_type')) {
                    $table->enum('election_type', ['real', 'demo'])->default('real');
                }
                if (!Schema::hasColumn('voter_registrations', 'registered_at')) {
                    $table->timestamp('registered_at')->nullable();
                }
                if (!Schema::hasColumn('voter_registrations', 'approved_by')) {
                    $table->string('approved_by')->nullable();
                }
                if (!Schema::hasColumn('voter_registrations', 'rejected_by')) {
                    $table->string('rejected_by')->nullable();
                }
                if (!Schema::hasColumn('voter_registrations', 'rejection_reason')) {
                    $table->text('rejection_reason')->nullable();
                }
                if (!Schema::hasColumn('voter_registrations', 'metadata')) {
                    $table->json('metadata')->nullable();
                }
                if (!Schema::hasColumn('voter_registrations', 'created_at')) {
                    $table->timestamps();
                }
            });
        }

        // ========================================
        // 7. VOTER_SLUG_STEPS TABLE - MEDIUM PRIORITY
        // ========================================
        if (Schema::hasTable('voter_slug_steps')) {
            Schema::table('voter_slug_steps', function (Blueprint $table) {
                if (!Schema::hasColumn('voter_slug_steps', 'step_data')) {
                    $table->json('step_data')->nullable();
                }
            });
        }

        // ========================================
        // 8. DEMO_POSTS TABLE - HIGH PRIORITY
        // ========================================
        if (Schema::hasTable('demo_posts')) {
            Schema::table('demo_posts', function (Blueprint $table) {
                if (!Schema::hasColumn('demo_posts', 'post_id')) {
                    $table->string('post_id')->nullable();
                }
                if (!Schema::hasColumn('demo_posts', 'nepali_name')) {
                    $table->string('nepali_name')->nullable();
                }
                if (!Schema::hasColumn('demo_posts', 'organisation_id')) {
                    $table->unsignedBigInteger('organisation_id')->nullable();
                    $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
                }
            });
        }

        // ========================================
        // 9. USERS TABLE - MEDIUM PRIORITY
        // ========================================
        if (Schema::hasTable('users')) {
            Schema::table('users', function (Blueprint $table) {
                if (!Schema::hasColumn('users', 'current_team_id')) {
                    $table->unsignedBigInteger('current_team_id')->nullable();
                }
                if (!Schema::hasColumn('users', 'profile_photo_path')) {
                    $table->string('profile_photo_path')->nullable();
                }
                
                if (!Schema::hasColumn('users', 'is_committee_member')) {
                    $table->boolean('is_committee_member')->default(false);
                }
            });
        }

        // ========================================
        // 10. CODES TABLE - MEDIUM PRIORITY
        // ========================================
        if (Schema::hasTable('codes')) {
            Schema::table('codes', function (Blueprint $table) {
                if (!Schema::hasColumn('codes', 'code3')) {
                    $table->string('code3')->nullable();
                }
                if (!Schema::hasColumn('codes', 'code4')) {
                    $table->string('code4')->nullable();
                }
                if (!Schema::hasColumn('codes', 'vote_show_code')) {
                    $table->string('vote_show_code')->nullable();
                }
                if (!Schema::hasColumn('codes', 'is_code3_usable')) {
                    $table->boolean('is_code3_usable')->default(false);
                }
                if (!Schema::hasColumn('codes', 'is_code4_usable')) {
                    $table->boolean('is_code4_usable')->default(false);
                }
                if (!Schema::hasColumn('codes', 'code3_used_at')) {
                    $table->timestamp('code3_used_at')->nullable();
                }
                if (!Schema::hasColumn('codes', 'code4_used_at')) {
                    $table->timestamp('code4_used_at')->nullable();
                }
                if (!Schema::hasColumn('codes', 'vote_last_seen')) {
                    $table->timestamp('vote_last_seen')->nullable();
                }
            });
        }
    }

    public function down(): void
    {
        // Drop columns (safety: check if they exist first)
        $tables = [
            'codes' => ['code3', 'code4', 'vote_show_code', 'is_code3_usable', 'is_code4_usable', 'code3_used_at', 'code4_used_at', 'vote_last_seen'],
            'users' => ['current_team_id', 'profile_photo_path', 'is_voter', 'is_committee_member'],
            'demo_posts' => ['post_id', 'nepali_name', 'organisation_id'],
            'voter_slug_steps' => ['step_data'],
            'voter_registrations' => ['election_type', 'registered_at', 'approved_by', 'rejected_by', 'rejection_reason', 'metadata', 'created_at'],
            'results' => ['candidacy_id'],
            'voter_slugs' => ['user_id', 'election_id', 'expires_at', 'is_active'],
            'candidacies' => ['candidacy_id', 'candidacy_name', 'proposer_id', 'supporter_id', 'image_path_1', 'image_path_2', 'image_path_3'],
            'posts' => ['post_id', 'nepali_name'],
            'votes' => ['post_id', 'voting_code'],
        ];

        foreach ($tables as $tableName => $columns) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($columns) {
                    foreach ($columns as $column) {
                        if (Schema::hasColumn($table->getTable(), $column)) {
                            $table->dropColumn($column);
                        }
                    }
                });
            }
        }
    }
};
