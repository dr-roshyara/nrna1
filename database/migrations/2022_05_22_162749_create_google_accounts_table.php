<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGoogleAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('google_accounts', function (Blueprint $table) {
            $table->id();
            // Relationships.
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                   ->references('id')->on('users')
                   ->onDelete('cascade');
            //Data
            $table->string('google_id');
            $table->string('name');
            $table->json('token');
            //Time Stamps
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
        Schema::dropIfExists('google_accounts');
    }
}
