<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoterSlugStepsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_slug_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_slug_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedTinyInteger('step'); // 1=code, 2=agreement, 3=vote, 4=verify, 5=complete
            $table->json('step_data')->nullable(); // Store step-specific data
            $table->timestamp('completed_at')->useCurrent(); // When step was completed

            // Timestamps
            $table->timestamps();

            // Foreign keys
            $table->foreign('voter_slug_id')
                  ->references('id')
                  ->on('voter_slugs')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            // Indexes for fast queries
            $table->unique(['voter_slug_id', 'election_id', 'step']); // Each voter completes each step once per election
            $table->index(['voter_slug_id', 'election_id']);
            $table->index(['election_id', 'completed_at']); // For analytics queries
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voter_slug_steps');
    }
}
