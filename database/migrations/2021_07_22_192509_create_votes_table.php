<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->string('user_id');
            // $table->string('post_id');
              //no vote  option 
            $table->boolean('no_vote_option')->default(0);
            $table->string('voting_code');
            
            $table->json("candidate_01")->nullable();
            $table->json("candidate_02")->nullable();
            $table->json("candidate_03")->nullable();
            $table->json("candidate_04")->nullable();
            $table->json("candidate_05")->nullable();
            $table->json("candidate_06")->nullable();
            $table->json("candidate_07")->nullable();
            $table->json("candidate_08")->nullable();
            $table->json("candidate_09")->nullable();
            $table->json("candidate_10")->nullable();
            $table->json("candidate_11")->nullable();
            $table->json("candidate_12")->nullable();
            $table->json("candidate_13")->nullable();
            $table->json("candidate_14")->nullable();
            $table->json("candidate_15")->nullable();
            $table->json("candidate_16")->nullable();
            $table->json("candidate_17")->nullable();
            $table->json("candidate_18")->nullable();
            $table->json("candidate_19")->nullable();
            $table->json("candidate_20")->nullable();
            $table->json("candidate_21")->nullable();
            $table->json("candidate_22")->nullable();
            $table->json("candidate_23")->nullable();
            $table->json("candidate_24")->nullable();
            $table->json("candidate_25")->nullable();
            $table->json("candidate_26")->nullable();
            $table->json("candidate_27")->nullable();
            $table->json("candidate_28")->nullable();
            $table->json("candidate_29")->nullable();
            $table->json("candidate_30")->nullable();
            //
            $table->json("candidate_31")->nullable();
            $table->json("candidate_32")->nullable();
            $table->json("candidate_33")->nullable();
            $table->json("candidate_34")->nullable();
            $table->json("candidate_35")->nullable();
            $table->json("candidate_36")->nullable();
            $table->json("candidate_37")->nullable();
            $table->json("candidate_38")->nullable();
            $table->json("candidate_39")->nullable();
            $table->json("candidate_40")->nullable();            
            //
            $table->json("candidate_41")->nullable();
            $table->json("candidate_42")->nullable();
            $table->json("candidate_43")->nullable();
            $table->json("candidate_44")->nullable();
            $table->json("candidate_45")->nullable();
            $table->json("candidate_46")->nullable();
            $table->json("candidate_47")->nullable();
            $table->json("candidate_48")->nullable();
            $table->json("candidate_49")->nullable();
            $table->json("candidate_50")->nullable();            
            //
            $table->json("candidate_51")->nullable();
            $table->json("candidate_52")->nullable();
            $table->json("candidate_53")->nullable();
            $table->json("candidate_54")->nullable();
            $table->json("candidate_55")->nullable();
            $table->json("candidate_56")->nullable();
            $table->json("candidate_57")->nullable();
            $table->json("candidate_58")->nullable();
            $table->json("candidate_59")->nullable();
            $table->json("candidate_60")->nullable(); 
            //
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
        Schema::dropIfExists('votes');
    }
}
