<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add voting_code column to demo_codes table for anonymous vote lookup.
     * This column links the code record to the anonymous vote record without
     * storing user_id on the vote, preserving anonymity guarantees.
     */
    public function up(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            // Add voting_code for linking to demo_votes anonymously
            if (!Schema::hasColumn('demo_codes', 'voting_code')) {
                $table->string('voting_code', 64)->nullable()->after('election_id');
                $table->index('voting_code');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('demo_codes', function (Blueprint $table) {
            if (Schema::hasColumn('demo_codes', 'voting_code')) {
                $table->dropIndex(['voting_code']);
                $table->dropColumn('voting_code');
            }
        });
    }
};
