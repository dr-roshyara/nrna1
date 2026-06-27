<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Drop all legacy code columns from demo_codes table.
     * These have been replaced by semantic naming:
     * - code1, code2, code3, code4 → code_to_open_voting_form / code_to_save_vote
     * - code1_sent_at, etc. → code_to_open_voting_form_sent_at
     * - code1_used_at, etc. → code_to_open_voting_form_used_at
     * - is_code1_usable, etc. → is_code_to_open_voting_form_usable
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Drop old code columns (code1, code2, code3, code4, vote_show_code)
            $columns_to_drop = [
                'code1',
                'code2',
                'code3',
                'code4',
                'vote_show_code',
                'code_for_vote',
                // Old sent_at timestamps
                'code1_sent_at',
                'code2_sent_at',
                'code3_sent_at',
                'code4_sent_at',
                // Old used_at timestamps
                'code1_used_at',
                'code2_used_at',
                'code3_used_at',
                'code4_used_at',
                // Old usability flags
                'is_code1_usable',
                'is_code2_usable',
                'is_code3_usable',
                'is_code4_usable',
                // Old usage tracking
                'has_used_code1',
                'has_used_code2',
                // Other legacy columns
                'vote_last_seen',
            ];

            foreach ($columns_to_drop as $column) {
                if (Schema::hasColumn('demo_codes', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }

    public function down(): void
    {
        // Rollback not practical - these columns existed in old_migrations
        // If needed, re-run 2026_02_20_012741_create_demo_codes_table from old_migrations
    }
};
