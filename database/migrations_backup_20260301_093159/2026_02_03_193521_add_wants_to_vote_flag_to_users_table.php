<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddWantsToVoteFlagToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds voter registration intent tracking to separate customers from voters.
     *
     * - wants_to_vote: Boolean flag indicating user's intent to participate in voting
     * - voter_registration_at: Timestamp tracking when user first requested voter status
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Add wants_to_vote flag after is_voter
            $table->boolean('wants_to_vote')
                ->default(false)
                ->after('is_voter')
                ->comment('User intends to participate in voting');

            // Track when user registered as a voter
            $table->timestamp('voter_registration_at')
                ->nullable()
                ->after('wants_to_vote')
                ->comment('When user first requested voter status');

            // Composite index for voter filtering queries
            $table->index(['wants_to_vote', 'is_voter'], 'idx_wants_voter');
        });

        // ============================================================================
        // DATA MIGRATION: Set wants_to_vote based on user's current state
        // ============================================================================

        DB::transaction(function () {
            // CASE 1: Committee members are NOT voters (customers)
            // These users manage elections but don't participate in voting
            DB::table('users')
                ->where('is_committee_member', 1)
                ->update([
                    'wants_to_vote' => false,
                    'voter_registration_at' => null,
                ]);

            // CASE 2: Pending voters (is_voter=0, can_vote=0)
            // These users have expressed intent but haven't been approved yet
            DB::table('users')
                ->where('is_voter', 0)
                ->where('can_vote', 0)
                ->where('is_committee_member', 0)
                ->update([
                    'wants_to_vote' => true,
                    'voter_registration_at' => DB::raw('created_at'),
                ]);

            // CASE 3: Approved voters (is_voter=1, can_vote=1)
            // These users have been approved and can participate in voting
            DB::table('users')
                ->where('is_voter', 1)
                ->where('can_vote', 1)
                ->update([
                    'wants_to_vote' => true,
                    'voter_registration_at' => DB::raw('created_at'),
                ]);

            // CASE 4: Suspended voters (is_voter=1, can_vote=0)
            // These users were previously approved but are now suspended
            // Keep wants_to_vote=true to preserve intent, but mark registration time
            DB::table('users')
                ->where('is_voter', 1)
                ->where('can_vote', 0)
                ->where('is_committee_member', 0)
                ->update([
                    'wants_to_vote' => true,
                    'voter_registration_at' => DB::raw('created_at'),
                ]);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_wants_voter');
            $table->dropColumn(['wants_to_vote', 'voter_registration_at']);
        });
    }
}
