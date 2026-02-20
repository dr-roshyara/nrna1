<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToDemoCandidaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            // Add organisation_id to support MODE 2 (with organisation) demo voting
            // NULL = MODE 1 (accessible to all), non-NULL = MODE 2 (scoped to organisation)
            $table->unsignedBigInteger('organisation_id')->nullable()->after('election_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            $table->dropColumn('organisation_id');
        });
    }
}
