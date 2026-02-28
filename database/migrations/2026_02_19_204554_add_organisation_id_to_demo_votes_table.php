<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToDemoVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Check BEFORE attempting to modify table
        if (!Schema::hasColumn('demo_votes', 'organisation_id')) {
            Schema::table('demo_votes', function (Blueprint $table) {
                // Add organisation_id for multi-tenancy tenant scoping
                // NOTE: Deliberately NO user_id to preserve vote anonymity
                $table->unsignedBigInteger('organisation_id')
                      ->nullable()
                      ->after('id')
                      ->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            if (Schema::hasColumn('demo_votes', 'organisation_id')) {
                $table->dropIndex(['organisation_id']);
                $table->dropColumn('organisation_id');
            }
        });
    }
}
