<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOpenionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('openions', function (Blueprint $table) {
            $table->id();
            $table->string('title', 400)->nullable();
            $table->longText('body');
            $table->string('hash_tag', 600)->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')
                   ->references('id')->on('users')
                   ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('openions');
    }
}
