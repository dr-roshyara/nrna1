<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vote_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('candidate_id')->nullable(); // NULL if vote was abstention/no-vote

            // Vote verification (linked to vote but NO user info)
            $table->string('vote_hash')->nullable(); // Copy from vote for verification

            // Count aggregation (for results reporting)
            $table->unsignedInteger('vote_count')->default(1);

            $table->timestamps();

            // Foreign keys
            $table->foreign('vote_id')
                  ->references('id')
                  ->on('votes')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('posts')
                  ->onDelete('cascade');

            $table->foreign('candidate_id')
                  ->references('id')
                  ->on('candidacies')
                  ->onDelete('set null');

            // Indexes (NO user_id!)
            $table->index(['election_id', 'post_id']);
            $table->index(['post_id', 'candidate_id']);
            $table->index('vote_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
