<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Migration: Refactor demo_codes columns for semantic clarity
 *
 * Renames voting code columns to be self-documenting:
 * - code1 → code_to_open_voting_form
 * - code2 → code_to_save_vote
 * - is_code1_usable → is_code_to_open_voting_form_usable
 * - is_code2_usable → is_code_to_save_vote_usable
 * - code1_sent_at → code_to_open_voting_form_sent_at
 * - code2_sent_at → code_to_save_vote_sent_at
 * - code1_used_at → code_to_open_voting_form_used_at
 * - code2_used_at → code_to_save_vote_used_at
 *
 * This migration:
 * 1. Adds new semantic columns in parallel
 * 2. Copies data from old columns to new ones
 * 3. Drops old columns (code1, code2, code3, code4, vote_show_code)
 * 4. Fully reversible with down() method
 */
return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Step 1: Check if we need to add missing columns that original migration didn't include
            // (These should only be missing in existing databases, not test databases)
            if (Schema::hasColumn('demo_codes', 'code1') && !Schema::hasColumn('demo_codes', 'code1_sent_at')) {
                // Original incomplete migration - add missing columns before new ones
                $table->dateTime('code1_sent_at')->nullable();
            }

            if (Schema::hasColumn('demo_codes', 'code2') && !Schema::hasColumn('demo_codes', 'code2_sent_at')) {
                $table->dateTime('code2_sent_at')->nullable();
            }

            if (Schema::hasColumn('demo_codes', 'code1') && !Schema::hasColumn('demo_codes', 'is_code2_usable')) {
                $table->boolean('is_code2_usable')->default(false);
            }

            if (Schema::hasColumn('demo_codes', 'code1') && !Schema::hasColumn('demo_codes', 'has_code1_sent')) {
                $table->boolean('has_code1_sent')->default(false);
                $table->boolean('has_code2_sent')->default(false);
            }

            // Step 2: Add new semantic columns (only if they don't exist)
            if (!Schema::hasColumn('demo_codes', 'code_to_open_voting_form')) {
                $table->string('code_to_open_voting_form')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'code_to_save_vote')) {
                $table->string('code_to_save_vote')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'is_code_to_open_voting_form_usable')) {
                $table->boolean('is_code_to_open_voting_form_usable')->default(false);
            }

            if (!Schema::hasColumn('demo_codes', 'is_code_to_save_vote_usable')) {
                $table->boolean('is_code_to_save_vote_usable')->default(false);
            }

            if (!Schema::hasColumn('demo_codes', 'code_to_open_voting_form_sent_at')) {
                $table->dateTime('code_to_open_voting_form_sent_at')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'code_to_save_vote_sent_at')) {
                $table->dateTime('code_to_save_vote_sent_at')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'code_to_open_voting_form_used_at')) {
                $table->dateTime('code_to_open_voting_form_used_at')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'code_to_save_vote_used_at')) {
                $table->dateTime('code_to_save_vote_used_at')->nullable();
            }

            if (!Schema::hasColumn('demo_codes', 'user_id')) {
                $table->uuid('user_id')->nullable();
            }
        });

        // Step 3: Migrate data ONLY if ALL required old columns exist
        // (Skip this in fresh databases where old columns are already being created with new names)
        $oldColumnsExist = Schema::hasColumn('demo_codes', 'code1')
            && Schema::hasColumn('demo_codes', 'code2')
            && Schema::hasColumn('demo_codes', 'is_code1_usable')
            && Schema::hasColumn('demo_codes', 'code1_used_at');

        $newColumnsExist = Schema::hasColumn('demo_codes', 'code_to_open_voting_form')
            && Schema::hasColumn('demo_codes', 'code_to_save_vote');

        if ($oldColumnsExist && $newColumnsExist) {
            // Only update if both old and new columns exist (existing database scenario)
            DB::statement('UPDATE demo_codes SET
                code_to_open_voting_form = COALESCE(code_to_open_voting_form, code1),
                code_to_save_vote = COALESCE(code_to_save_vote, code2),
                is_code_to_open_voting_form_usable = is_code1_usable
            ');

            // Handle optional columns if they exist
            if (Schema::hasColumn('demo_codes', 'is_code2_usable')) {
                DB::statement('UPDATE demo_codes SET is_code_to_save_vote_usable = is_code2_usable');
            }

            if (Schema::hasColumn('demo_codes', 'code1_sent_at')) {
                DB::statement('UPDATE demo_codes SET code_to_open_voting_form_sent_at = code1_sent_at WHERE code1_sent_at IS NOT NULL');
            }

            if (Schema::hasColumn('demo_codes', 'code2_sent_at')) {
                DB::statement('UPDATE demo_codes SET code_to_save_vote_sent_at = code2_sent_at WHERE code2_sent_at IS NOT NULL');
            }

            if (Schema::hasColumn('demo_codes', 'code2_used_at')) {
                DB::statement('UPDATE demo_codes SET code_to_save_vote_used_at = code2_used_at WHERE code2_used_at IS NOT NULL');
            }
        }

        // Step 4: Drop old columns only if they exist
        Schema::table('demo_codes', function (Blueprint $table) {
            $columns_to_drop = [];

            if (Schema::hasColumn('demo_codes', 'code1')) {
                $columns_to_drop[] = 'code1';
            }
            if (Schema::hasColumn('demo_codes', 'code2')) {
                $columns_to_drop[] = 'code2';
            }
            if (Schema::hasColumn('demo_codes', 'is_code1_usable')) {
                $columns_to_drop[] = 'is_code1_usable';
            }
            if (Schema::hasColumn('demo_codes', 'is_code2_usable')) {
                $columns_to_drop[] = 'is_code2_usable';
            }
            if (Schema::hasColumn('demo_codes', 'code1_sent_at')) {
                $columns_to_drop[] = 'code1_sent_at';
            }
            if (Schema::hasColumn('demo_codes', 'code2_sent_at')) {
                $columns_to_drop[] = 'code2_sent_at';
            }
            if (Schema::hasColumn('demo_codes', 'code1_used_at')) {
                $columns_to_drop[] = 'code1_used_at';
            }
            if (Schema::hasColumn('demo_codes', 'code2_used_at')) {
                $columns_to_drop[] = 'code2_used_at';
            }

            if (!empty($columns_to_drop)) {
                $table->dropColumn($columns_to_drop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Step 1: Recreate old columns
            $table->string('code1')->nullable()->after('election_id');
            $table->string('code2')->nullable()->after('code1');

            $table->boolean('is_code1_usable')->default(true)->after('code2');
            $table->boolean('is_code2_usable')->default(false)->after('is_code1_usable');

            $table->dateTime('code1_sent_at')->nullable()->after('is_code2_usable');
            $table->dateTime('code2_sent_at')->nullable()->after('code1_sent_at');

            $table->dateTime('code1_used_at')->nullable()->after('code2_sent_at');
            $table->dateTime('code2_used_at')->nullable()->after('code1_used_at');
        });

        // Step 2: Migrate data back to old columns
        DB::statement('UPDATE demo_codes SET
            code1 = code_to_open_voting_form,
            code2 = code_to_save_vote,
            is_code1_usable = is_code_to_open_voting_form_usable,
            is_code2_usable = is_code_to_save_vote_usable,
            code1_sent_at = code_to_open_voting_form_sent_at,
            code2_sent_at = code_to_save_vote_sent_at,
            code1_used_at = code_to_open_voting_form_used_at,
            code2_used_at = code_to_save_vote_used_at
        ');

        // Step 3: Drop new semantic columns
        Schema::table('demo_codes', function (Blueprint $table) {
            $table->dropColumn([
                'code_to_open_voting_form',
                'code_to_save_vote',
                'is_code_to_open_voting_form_usable',
                'is_code_to_save_vote_usable',
                'code_to_open_voting_form_sent_at',
                'code_to_save_vote_sent_at',
                'code_to_open_voting_form_used_at',
                'code_to_save_vote_used_at',
            ]);
        });
    }
};
