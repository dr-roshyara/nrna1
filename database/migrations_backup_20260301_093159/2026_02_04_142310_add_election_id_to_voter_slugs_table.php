<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddElectionIdToVoterSlugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Add election_id to link voter slug to specific election
            if (!Schema::hasColumn('voter_slugs', 'election_id')) {
                $table->unsignedBigInteger('election_id')->nullable()->default(1);
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
        Schema::table('voter_slugs', function (Blueprint $table) {
            if (Schema::hasColumn('voter_slugs', 'election_id')) {
                $table->dropIndex(['election_id']);
                $table->dropColumn('election_id');
            }
        });
    }
}
