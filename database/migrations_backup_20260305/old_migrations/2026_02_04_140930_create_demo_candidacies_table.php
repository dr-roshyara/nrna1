<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoCandidaciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_candidacies', function (Blueprint $table) {
            $table->id();
            $table->string('candidacy_id')->unique();
            $table->string('user_id');  // NOT unique (allows user in both demo+real)
            $table->string('user_name')->nullable();  // Candidate name from user table
            $table->string('candidacy_name')->nullable();  // Alt candidacy name
            $table->unsignedBigInteger('election_id')->default(1); // Demo election
            $table->string('post_id');
            $table->string('post_name')->nullable();  // Post display name
            $table->string('post_nepali_name')->nullable();  // Nepali post name if applicable
            $table->string('proposer_id')->nullable();
            $table->string('proposer_name')->nullable();  // Proposer display name
            $table->string('supporter_id')->nullable();
            $table->string('supporter_name')->nullable();  // Supporter display name
            $table->string('image_path_1')->nullable();
            $table->string('image_path_2')->nullable();
            $table->string('image_path_3')->nullable();
            $table->timestamps();

            // Indexes for lookups
            $table->index('election_id');
            $table->index('post_id');
            $table->index(['election_id', 'post_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_candidacies');
    }
}
