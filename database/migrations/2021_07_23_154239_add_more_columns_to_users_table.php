<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreColumnsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            //
            $table->boolean('can_vote')->default(0);
            $table->boolean('has_voted')->default(0);
            $table->boolean('has_candidacy')->default(0);
            $table->string('code1')->unique()->nullable();
            $table->string('code2')->unique()->nullable();
            $table->boolean('has_used_code1')->default(0);
            $table->boolean('has_used_code2')->default(0);
            $table->string('lcc')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
