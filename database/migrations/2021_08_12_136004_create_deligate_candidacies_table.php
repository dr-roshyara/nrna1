<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeligateCandidaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('deligate_candidacies', function (Blueprint $table) {
        //     $table->id();
        //     $table->bigInteger('user_id')->unsigned();   
        //     $table->string('nrna_id'); 
        //     $table->string('name');
        //     $table->text('description');                        
        //     $table->bigInteger('post_id')->unsiggned();
        //     $table->string('image_path_1')->nullable();
        //     //
        //     $table->foreign('user_id') 
        //         ->references('id')->on('users') 
        //         ->onDelete('cascade');
        //     //
        //     $table->foreign('post_id')
        //           ->references('id')
        //           ->on('deligate_posts')
        //           ->onDelete('cascade');
        //     // here 
        //     $table->timestamps();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('deligate_candidacies');
    }
}
