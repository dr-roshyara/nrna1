<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCandidacies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('candidacies', function (Blueprint $table) {
           
            $table->foreign('user_id') 
                ->references('user_id')
                ->on('users') 
                ->onDelete('cascade');
            //
            //  $table->foreign('proposer_id')
            //       ->references('user_id')
            //       ->on('users')
            //       ->onDelete('cascade');
            // //
            //  $table->foreign('supporter_id')
            //       ->references('user_id')
            //       ->on('users')
            //       ->onDelete('cascade'); 
            //                  
            $table->foreign('post_id')
                  ->references('post_id')
                  ->on('posts')
                  ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidacies', function (Blueprint $table) {
            //
        });
    }
}
