<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoterStatusIndexToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
        {
            Schema::table('users', function (Blueprint $table) {
                // Composite index for is_voter and can_vote columns
                $table->index(['is_voter', 'can_vote'], 'users_is_voter_can_vote_index');
            });
        }

        public function down()
        {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_is_voter_can_vote_index');
            });
        }

}
