<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
             $table->bigInteger('user_id')->unsigned();
            $table->string('code1')->nullable();
            $table->string('code2')->nullable();
            $table->string('code3')->nullable();
            $table->string('code4')->nullable();
            $table->string('vote_show_code')->nullable();
            //
            $table->boolean('is_code1_usable')->default(0);
            $table->boolean('is_code2_usable')->default(0);
            $table->boolean('is_code3_usable')->default(0);
            $table->boolean('is_code4_usable')->default(0);
            //
            $table->boolean('can_vote_now')->default(0);
            $table->boolean('has_voted')->default(0);            
            $table->date('vote_last_seen')->nullable();

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
        Schema::dropIfExists('codes');
    }
}
