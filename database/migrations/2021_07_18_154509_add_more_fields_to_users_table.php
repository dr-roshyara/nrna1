<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

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
            $table->string('first_name')->nullable();
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('gender')->nullable();
            $table->string('region')->nullable();
            $table->string('country')->nullable();
            $table->string('state')->nullable();
            $table->string('street')->nullable();
            $table->string('housenumber')->nullable();                        
            $table->string('postalcode')->nullable();                         
            $table->string('city')->nullable();
            $table->string('additional_address')->nullable(); 
            $table->string('nrna_id')->unique()->nullable(); 
            $table->string('telephone')->unique()->nullable(); 
         
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
