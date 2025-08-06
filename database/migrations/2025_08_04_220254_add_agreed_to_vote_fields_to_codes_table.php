<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgreedToVoteFieldsToCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('codes', function (Blueprint $table) {
        $table->boolean('has_agreed_to_vote')->default(false);
        $table->timestamp('has_agreed_to_vote_at')->nullable();
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
            $table->dropColumn('has_agreed_to_vote');
            $table->dropColumn('has_agreed_to_vote_at');
        });
    }

}
