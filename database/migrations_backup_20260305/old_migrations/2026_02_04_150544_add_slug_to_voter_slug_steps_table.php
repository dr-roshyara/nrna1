<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSlugToVoterSlugStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            $table->string('slug')->nullable()->after('voter_slug_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('voter_slug_steps', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
