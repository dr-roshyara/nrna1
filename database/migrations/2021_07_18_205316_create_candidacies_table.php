<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCandidaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('candidacies', function (Blueprint $table) {
            $table->id();
            //candidacy id is just an extra . 
            $table->string('candidacy_id'); 
            //foreign key user 
            $table->bigInteger('user_id')->unsigned();   
            //foreign key post 
            $table->bigInteger('post_id')->unsigned();
            // $table->string('candidacy_name')->nullable();            
            $table->bigInteger('proposer_id')->unsigned()->nullable();
            // $table->string('proposer_name')->nullable();
            $table->bigInteger('supporter_id')->unsigned()->nullable();             
            // $table->string('supporter_name')->nullable(); 
            // $table->string('post_name')->nullable();
            // $table->string('post_nepali_name')->nullable();
            $table->string('image_path_1')->nullable();
            $table->string('image_path_2')->nullable(); 
            $table->string('image_path_3')->nullable();                         
         
            // here 
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
        Schema::dropIfExists('candidacies');
    }
}
