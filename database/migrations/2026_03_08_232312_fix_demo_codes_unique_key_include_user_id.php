<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * FIX: Change unique constraint from (code + organisation_id)
     * to (code + organisation_id + user_id)
     *
     * REASON: Multiple users in same organisation need different codes.
     * Current constraint prevents different users from getting codes.
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Drop old unique constraint (code + org only)
            $table->dropUnique('demo_codes_code1_organisation_id_unique');
        });

        Schema::table('demo_codes', function (Blueprint $table) {
            // Add new unique constraint (code + org + user)
            // This allows multiple users in same org to have different codes
            $table->unique(
                ['code_to_open_voting_form', 'organisation_id', 'user_id'],
                'demo_codes_code_org_user_unique'
            );
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Revert to old unique constraint
            $table->dropUnique('demo_codes_code_org_user_unique');
        });

        Schema::table('demo_codes', function (Blueprint $table) {
            // Restore old constraint
            $table->unique(
                ['code_to_open_voting_form', 'organisation_id'],
                'demo_codes_code1_organisation_id_unique'
            );
        });
    }
};
