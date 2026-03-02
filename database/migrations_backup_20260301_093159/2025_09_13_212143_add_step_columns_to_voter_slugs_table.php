<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStepColumnsToVoterSlugsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->unsignedTinyInteger('current_step')->default(1)->after('is_active');
            $table->json('step_meta')->nullable()->after('current_step');
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
            $table->dropColumn(['current_step', 'step_meta']);
        });
    }
}
