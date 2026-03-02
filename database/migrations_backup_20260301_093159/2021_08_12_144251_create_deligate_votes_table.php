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
            $table->string('member1_id')->nullable();
            //member #1
            $table->string('member2_id')->nullable();
            //member #1
            $table->string('member3_id')->nullable();
            //member #1
            $table->string('member4_id')->nullable();
            //member #1
            $table->string('member5_id')->nullable();
            //member #1
            $table->string('member6_id')->nullable();
            //member #1
            $table->string('member7_id')->nullable();
            //member #1
            $table->string('member8_id')->nullable();
            //member #1
            $table->string('member9_id')->nullable();
            //member #1
            $table->string('member10_id')->nullable();
            //member #1
            $table->string('member11_id')->nullable();
            //member #1
            $table->string('member12_id')->nullable();
            //member #1
            $table->string('member13_id')->nullable();
            //member #1
            $table->string('member14_id')->nullable();
            //member #1
            $table->string('member15_id')->nullable();
            //member #1
            $table->string('member16_id')->nullable();
            //member #1
            $table->string('member17_id')->nullable();
            //member #1
            $table->string('member18_id')->nullable();
            //member #1
            $table->string('member19_id')->nullable();
            //member #1
            $table->string('member20_id')->nullable();
            //member #1
            $table->string('member21_id')->nullable();
            //member #1
            $table->string('member22_id')->nullable();
            //member #1
            $table->string('member23_id')->nullable();
            //member #1
            $table->string('member24_id')->nullable();
            //member #1
            $table->string('member25_id')->nullable();
            //member #1
            $table->string('member26_id')->nullable();
            //member #1
            $table->string('member27_id')->nullable();
            //member #1
            $table->string('member28_id')->nullable();
            //member #1
            $table->string('member29_id')->nullable();
            //member #1
            $table->string('member30_id')->nullable();
            //member #1
            $table->string('member31_id')->nullable();
            //member #1
            $table->string('member32_id')->nullable();
            //member #1
            $table->string('member33_id')->nullable();
            //member #1
            $table->string('member34_id')->nullable();
            //member #1
            $table->string('member35_id')->nullable();
            
            //member #1
            $table->timestamps();

            // $table->foreign('user_id') 
            // ->references('id')->on('users') 
            //    ->onDelete('cascade');
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
