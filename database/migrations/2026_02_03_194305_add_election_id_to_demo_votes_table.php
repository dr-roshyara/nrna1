<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddElectionIdToDemoVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds election_id to demo_votes table to support multiple demo elections.
     * Combined with table separation (votes vs demo_votes), this provides:
     * - Physical separation by table (real vs demo)
     * - Logical separation by election_id (multiple demo elections)
     *
     * NO foreign key constraint - maintains independence from elections table.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // ✅ Add election_id column for demo vote scoping
            // CRITICAL: demo_votes table has NO user_id column (by design for anonymity)
            if (!Schema::hasColumn('demo_votes', 'election_id')) {
                $table->unsignedBigInteger('election_id')
                      ->default(1)
                      ->after('id')
                      ->comment('Reference to elections table - scopes demo votes per election');

                // Add index for election lookups
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
        Schema::table('demo_votes', function (Blueprint $table) {
            // Drop index
            if (Schema::hasIndexColumn('demo_votes', 'election_id')) {
                $table->dropIndex(['election_id']);
            }

            // Drop column
            if (Schema::hasColumn('demo_votes', 'election_id')) {
                $table->dropColumn('election_id');
            }
        });
    }
}
