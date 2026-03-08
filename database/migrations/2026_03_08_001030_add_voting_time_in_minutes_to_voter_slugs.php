<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Add voting_time_in_minutes column for session expiration control
     */
    public function up(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Add voting_time_in_minutes (renamed from voting_time_min for clarity)
            if (!Schema::hasColumn('voter_slugs', 'voting_time_in_minutes')) {
                $table->integer('voting_time_in_minutes')
                    ->default(30)
                    ->after('can_vote_now')
                    ->comment('Voting window duration in minutes');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            if (Schema::hasColumn('voter_slugs', 'voting_time_in_minutes')) {
                $table->dropColumn('voting_time_in_minutes');
            }
        });
    }
};
