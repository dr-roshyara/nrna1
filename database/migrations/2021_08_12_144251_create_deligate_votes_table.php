<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeligateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deligate_votes', function (Blueprint $table) {
            $table->id();
            $table->BigInteger('user_id')->unsigned(); 
            
            $table->string('conformation_code')->nullable();
            //no vote  option 
            $table->boolean('no_vote_option')->default(0);
            
            //member #1
            $table->BigInteger('mmeber1_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber2_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber3_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber4_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber5_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber6_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber7_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber8_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber9_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber10_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber11_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber12_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber13_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber14_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber15_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber16_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber17_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber18_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber19_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber20_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber21_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber22_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber23_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber24_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber25_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber26_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber27_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber28_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber29_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber30_id')->unsigned()->nullable();
            //member #1
            $table->BigInteger('mmeber31_id')->unsigned()->nullable();

            $table->timestamps();

            $table->foreign('user_id') 
            ->references('id')->on('users') 
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
        Schema::dropIfExists('deligate_votes');
    }
}
