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
            $table->string('candidacy_id')->unique(); 
            //foreign key user 
            $table->string('user_id')->unique();   
  
            $table->string('post_id');            
            $table->string('proposer_id')->unique()->nullable();
            $table->string('supporter_id')->unique()->nullable();             
      
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
