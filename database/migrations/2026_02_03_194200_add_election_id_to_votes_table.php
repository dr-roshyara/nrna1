<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddElectionIdToVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds election_id to votes table to scope votes per election.
     * Combined with table separation (votes vs demo_votes), this provides:
     * - Physical separation by table (real vs demo)
     * - Logical separation by election_id (multiple elections per type)
     *
     * NO foreign key constraint - maintains independence from elections table.
     * Backward compatible: NULL election_id for existing votes (will default to first election).
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            // ✅ Add election_id column for vote scoping
            // IMPORTANT: Added after 'id' (NOT after 'user_id')
            // CRITICAL: votes table has NO user_id column (by design for anonymity)
            if (!Schema::hasColumn('votes', 'election_id')) {
                $table->unsignedBigInteger('election_id')
                      ->default(1)
                      ->after('id')
                      ->comment('Reference to elections table - scopes votes per election');

                // Add index for frequent queries (election lookups)
                $table->index('election_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('votes', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['election_id']);
            $table->dropIndex(['election_id', 'user_id']);

            // Drop column
            $table->dropColumn('election_id');
        });
    }
}
