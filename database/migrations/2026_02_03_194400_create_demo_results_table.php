<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates demo_results table as an exact mirror of results table.
     * This table stores DEMO election results only.
     * Physical separation ensures demo results never mix with real results.
     *
     * Mirrors results table structure:
     * - vote_id: reference to demo_votes table
     * - post_id: reference to posts table
     * - candidacy_id: reference to candidacies table
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id')
                  ->default(1)
                  ->comment('Reference to elections table - scopes results per election');
            $table->bigInteger('vote_id')->unsigned();
            $table->string('post_id');
            $table->string('candidacy_id');
            $table->timestamps();

            // Indexes
            $table->index('election_id');
            $table->index(['election_id', 'vote_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_results');
    }
}
