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
            // Add election_id column (nullable for backward compatibility)
            $table->unsignedBigInteger('election_id')
                  ->nullable()
                  ->after('user_id')
                  ->comment('Reference to elections table - scopes votes per election');

            // Add index for frequent queries (election lookups)
            $table->index('election_id');

            // Add composite index for election + user (common query pattern)
            $table->index(['election_id', 'user_id']);
        });

        // Default existing votes to first election (demo election from seeder)
        // This maintains backward compatibility with existing voting results
        DB::table('votes')
            ->whereNull('election_id')
            ->update([
                'election_id' => 1 // First election from ElectionSeeder
            ]);

        // Make election_id NOT NULL after data migration
        Schema::table('votes', function (Blueprint $table) {
            $table->unsignedBigInteger('election_id')->nullable(false)->change();
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
