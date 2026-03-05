<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            // CRITICAL: NO user_id - votes are completely anonymous
            // voting_code is hashed for audit trail only
            $table->string('voting_code')->unique();
            $table->json('candidate_selections')->nullable();
            $table->boolean('no_vote_option')->default(false);
            $table->timestamp('voted_at');
            $table->string('voter_ip')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
