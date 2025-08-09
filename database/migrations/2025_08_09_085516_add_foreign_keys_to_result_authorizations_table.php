<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToResultAuthorizationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::table('result_authorizations', function (Blueprint $table) {
            // Add foreign keys now that all tables exist
            $table->foreign('election_id')->references('id')->on('elections')->onDelete('cascade');
            $table->foreign('publisher_id')->references('id')->on('publishers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         Schema::table('result_authorizations', function (Blueprint $table) {
            $table->dropForeign(['election_id']);
            $table->dropForeign(['publisher_id']);
        });
    }
}
