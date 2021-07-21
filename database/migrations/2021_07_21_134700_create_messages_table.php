<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            // $table->bigInteger('user_id')->unsigned();            
            $table->string('from');
            $table->string('to');
            $table->string('code');
            $table->string('message'); 
            $table->bigInteger('message_receiver_id')->unsigned();
            $table->string('message_receiver_name');
            $table->bigInteger('messager_sender_id')->unsigned();
            $table->string('messager_sender_name');
            $table->timestamps(); 
             //
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
