<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendars', function (Blueprint $table) {
            $table->id();
            //Relationship
            $table->unsignedBigInteger('google_account_id');
            //Relationship
            $table->foreign('google_account_id')
                ->references('id')->on('google_accounts')
                ->onDelete('cascade');
            //Data
            $table->string('google_id');
            $table->string('name');
            $table->string('color');
            $table->string('timezone');
            //Time stamps
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
        Schema::dropIfExists('calendars');
    }
}
