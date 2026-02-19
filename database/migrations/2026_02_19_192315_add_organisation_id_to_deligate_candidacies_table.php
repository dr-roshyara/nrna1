<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrganisationIdToDeligateCandidaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('deligate_candidacies', function (Blueprint $table) {
            if (!Schema::hasColumn('deligate_candidacies', 'organisation_id')) {
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
        Schema::table('deligate_candidacies', function (Blueprint $table) {
            $table->dropIndex(['organisation_id']);
            $table->dropColumn('organisation_id');
        });
    }
}
