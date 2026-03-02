<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned();
            $table->bigInteger('role_id')->unsigned();
            // $table->string('role_name')->nullable();       // For MySQL 8.0 use string('name', 125);
            // $table->string('user_name');
                        //
            $table->foreign('user_id')
                ->references('id')->on('users') 
                ->onDelete('cascade');
            //     
            $table->foreign('role_id')
            ->references('id')->on('roles') 
            ->onDelete('cascade');
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
        Schema::dropIfExists('assignments');
    }
}
