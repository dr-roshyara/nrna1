<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMoreFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            //
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('gender');
            $table->string('country');
            $table->string('state');
            $table->string('street')->nullable();
            $table->string('housenumber')->nullable();                        
            $table->string('postalcode')->nullabe();                        
            $table->string('city');
            $table->string('additional_address')->nullabe();
            $table->string('nrna_id')->unique();
            $table->string('telephone')->unique(); 
         
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
