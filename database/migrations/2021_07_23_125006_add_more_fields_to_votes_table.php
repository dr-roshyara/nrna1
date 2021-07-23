<?php

use Brick\Math\BigInteger;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('votes', function (Blueprint $table) {
            //
            $table->BigInteger('user_id')->unsigned();
            //no vote  option 
            $table->boolean('no_vote_option')->default(0);
            //icc member 1
            $table->string('icc_member1_id')->nullable();
            $table->string('icc_member1_name')->nullable();
            //icc member 2 
            $table->string('icc_member2_id')->nullable();
            $table->string('icc_member2_name')->nullable();
            //president 
            $table->string('president_id')->nullable();
            $table->string('president_name')->nullable();
            //vice president 
            $table->string('vice_president1_id')->nullable();
            $table->string('vice_president1_name')->nullable();
            
            //vice president 
            $table->string('vice_president2_id')->nullable();
            $table->string('vice_president2_name')->nullable();
            
            //vice president 
            $table->string('vice_president3_id')->nullable();
            $table->string('vice_president3_name')->nullable();
            
            //vice president 
            $table->string('vice_president4_id')->nullable();
            $table->string('vice_president4_name')->nullable();

            //vice president 
            $table->string('vice_president5_id')->nullable();
            $table->string('vice_president5_name')->nullable();
            
            
            //woman vice president 
            $table->string('woman_vice_president_id')->nullable();
            $table->string('woman_vice_president_name')->nullable();
            
            //General secretary
            $table->string('general_secretary_id')->nullable();
            $table->string('general_secretary_name')->nullable();

            //secretary
            $table->string('secretary1_id')->nullable();
            $table->string('secretary1_name')->nullable();
             //secretary2
             $table->string('secretary2_id')->nullable();
             $table->string('secretary2_name')->nullable();

            //treasure
             $table->string('treasure_id')->nullable();
             $table->string('treasure_name')->nullable();
            //woman_coordinator
            $table->string('woman_coordinator_id')->nullable();
            $table->string('woman_coordinator_name')->nullable();
            
            //woman_coordinator
            $table->string('youth_coordinator_id')->nullable();
            $table->string('youth_coordinator_name')->nullable();
            
            //culture_coordinator
            $table->string('culture_coordinator_id')->nullable();
            $table->string('culture_coordinator_name')->nullable();
            //culture_coordinator
            $table->string('children_coordinator_id')->nullable();
            $table->string('children_coordinator_name')->nullable();
            
            //culture_coordinator
            $table->string('student_coordinator_id')->nullable();
            $table->string('student_coordinator_name')->nullable();
            
            //member_Berlin1
            $table->string('member_berlin1_id')->nullable();
            $table->string('member_berlin1_name')->nullable();

            //member_Berlin2
            $table->string('member_berlin2_id')->nullable();
            $table->string('member_berlin2_name')->nullable();

               //member_hamburg
               $table->string('member_hamburg1_id')->nullable();
               $table->string('member_hamburg1_name')->nullable();
   
               //member_hamburg
               $table->string('member_hamburg2_id')->nullable();
               $table->string('member_hamburg2_name')->nullable();

               //member_niedersachsen
               $table->string('member_niedersachsen1_id')->nullable();
               $table->string('member_niedersachsen1_name')->nullable();
   
               //member_hamburg
               $table->string('member_niedersachsen2_id')->nullable();
               $table->string('member_niedersachsen2_name')->nullable();

   
               //member_nrw
               $table->string('member_nrw1_id')->nullable();
               $table->string('member_nrw1_name')->nullable();
               
               //member_nrw
               $table->string('member_nrw2_id')->nullable();
               $table->string('member_nrw2_name')->nullable();

               //member_hessen
               $table->string('member_hessen1_id')->nullable();
               $table->string('member_hessen1_name')->nullable();
               //member_nrw
               $table->string('member_hessen2_id')->nullable();
               $table->string('member_hessen2_name')->nullable();

               //member_rheinland_pfalz
               $table->string('member_rheinland_pfalz1_id')->nullable();
               $table->string('member_rheinland_pfalz1_name')->nullable();

               //member_rheinland_pfalz
               $table->string('member_rheinland_pfalz2_id')->nullable();
               $table->string('member_rheinland_pfalz2_name')->nullable();
               
                //member_rheinland_pfalz
                $table->string('member_bayern1_id')->nullable();
                $table->string('member_bayern1_name')->nullable();
                

                //member_bayern
               $table->string('member_bayern2_id')->nullable();
               $table->string('member_bayern2_name')->nullable();
               
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
        Schema::table('votes', function (Blueprint $table) {
            //
        });
    }
}
