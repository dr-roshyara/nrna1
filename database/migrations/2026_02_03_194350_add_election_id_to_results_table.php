<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class AddElectionIdToResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds election_id to results table to scope results per election.
     * Maintains referential integrity with votes table.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('results', function (Blueprint $table) {
            // Add election_id column for results scoping
            if (!Schema::hasColumn('results', 'election_id')) {
                $table->unsignedBigInteger('election_id')
                      ->default(1)
                      ->after('id')
                      ->comment('Reference to elections table - scopes results per election');

                // Add indexes
                $table->index('election_id');
                $table->index(['election_id', 'vote_id']);
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
        Schema::table('results', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['election_id']);
            $table->dropIndex(['election_id', 'vote_id']);

            // Drop column
            $table->dropColumn('election_id');
        });
    }
}
