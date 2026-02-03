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
            // Add election_id column at the beginning
            $table->unsignedBigInteger('election_id')
                  ->after('id')
                  ->default(1)
                  ->comment('Reference to elections table - scopes demo votes per election');

            // Add indexes
            $table->index('election_id');
            $table->index(['election_id', 'user_id']);
        });

        // Default all demo votes to first election (demo election from seeder)
        DB::table('demo_votes')
            ->where('election_id', 1)
            ->orWhereNull('election_id')
            ->update(['election_id' => 1]);

        // Make election_id NOT NULL
        Schema::table('demo_votes', function (Blueprint $table) {
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
        Schema::table('demo_votes', function (Blueprint $table) {
            // Drop indexes
            $table->dropIndex(['election_id']);
            $table->dropIndex(['election_id', 'user_id']);

            // Drop column
            $table->dropColumn('election_id');
        });
    }
}
