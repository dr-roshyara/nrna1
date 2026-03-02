<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionOrderToDemoCandidaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_candidacies', function (Blueprint $table) {
            $table->integer('position_order')->default(0)->after('post_id')->comment('Display order of candidate within post');
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
            $table->dropColumn('position_order');
        });
    }
}
