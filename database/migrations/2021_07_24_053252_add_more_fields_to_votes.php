<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToVotes extends Migration
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
            // //icc member 1
            // $table->string('icc_member1_candidacy_id')->nullable();
            // //icc member 2 
            // $table->string('icc_member2_candidacy_id')->nullable();
            // //president 
            // $table->string('president_candidacy_id')->nullable();
            // //vice president 
            // $table->string('vice_president1_candidacy_id')->nullable();
            
            // //vice president 
            // $table->string('vice_president2_candidacy_id')->nullable();
            
            // //vice president 
            // $table->string('vice_president3_candidacy_id')->nullable();
             
            // //vice president 
            // $table->string('vice_president4_candidacy_id')->nullable();
         
            // //vice president 
            // $table->string('vice_president5_candidacy_id')->nullable();
            
            // //woman vice president 
            // $table->string('woman_vice_president_candidacy_id')->nullable();
           
            // //General secretary
            // $table->string('general_secretary_candidacy_id')->nullable();
            //   //secretary
            // $table->string('secretary1_candidacy_id')->nullable();
            //    //secretary2
            //  $table->string('secretary2_candidacy_id')->nullable();
          
            // //treasure
            //  $table->string('treasure_candidacy_id')->nullable();
            //      //woman_coordinator
            // $table->string('woman_coordinator_candidacy_id')->nullable();
            //   //woman_coordinator
            // $table->string('youth_coordinator_candidacy_id')->nullable();
               
            // //culture_coordinator
            // $table->string('culture_coordinator_candidacy_id')->nullable();
            //   //culture_coordinator
            // $table->string('children_coordinator_candidacy_id')->nullable();
              
            // //culture_coordinator
            // $table->string('student_coordinator_candidacy_id')->nullable();
            //  //member_Berlin1
            // $table->string('member_berlin1_candidacy_id')->nullable();
           
            // //member_Berlin2
            // $table->string('member_berlin2_candidacy_id')->nullable();
 
            //    //member_hamburg
            //    $table->string('member_hamburg1_candidacy_id')->nullable();
       
            //    //member_hamburg
            //    $table->string('member_hamburg2_candidacy_id')->nullable();
             
            //    //member_niedersachsen
            //    $table->string('member_niedersachsen1_candidacy_id')->nullable();
              
            //    //member_hamburg
            //    $table->string('member_niedersachsen2_candidacy_id')->nullable();
              
               
   
            //    //member_nrw
            //    $table->string('member_nrw1_candidacy_id')->nullable();
            //       //member_nrw
            //    $table->string('member_nrw2_candidacy_id')->nullable();
             
            //    //member_hessen
            //    $table->string('member_hessen1_candidacy_id')->nullable();
            //    //member_nrw
            //    $table->string('member_hessen2_candidacy_id')->nullable();

            //    //member_rheinland_pfalz
            //    $table->string('member_rheinland_pfalz1_candidacy_id')->nullable();
        
            //    //member_rheinland_pfalz
            //    $table->string('member_rheinland_pfalz2_candidacy_id')->nullable();
               
            //     //member_rheinland_pfalz
            //     $table->string('member_bayern1_candidacy_id')->nullable();
                

            //     //member_bayern
            //    $table->string('member_bayern2_candidacy_id')->nullable();
            //    $table->text('test_name')->nullable();  
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
