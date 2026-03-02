<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVoteSubmittedToCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('codes', function (Blueprint $table) {
              $table->Boolean('vote_submitted')->default(0);
              $table->timestamp('vote_submitted_at')->nullable();
              $table->Boolean('has_code1_sent') ->default(0);
              $table->Boolean('has_code2_sent')->default(0);
              
              
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('codes', function (Blueprint $table) {
            //
        });
    }
}
