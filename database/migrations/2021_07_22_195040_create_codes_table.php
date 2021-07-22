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
            $table->string('code1');
            $table->string('code2');
            $table->string('code3');
            $table->string('code4');
            $table->boolean('used_code1')->default(0);
            $table->string('used_code2')->default(0);
            $table->string('used_code3')->default(0);          
            $table->string('used_code4')->default(0);
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
