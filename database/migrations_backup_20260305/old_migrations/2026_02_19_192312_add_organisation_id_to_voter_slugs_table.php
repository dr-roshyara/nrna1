<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToVoterSlugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            if (!Schema::hasColumn('voter_slugs', 'organisation_id')) {
                $table->unsignedBigInteger('organisation_id')->nullable()->after('id')->index();
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
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        });
    }
}
