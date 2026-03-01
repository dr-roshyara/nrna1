<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organisation_id')->nullable();

            // Two-code system
            $table->string('code1'); // First code (entry)
            $table->string('code2')->nullable(); // Second code (verification)

            // Code state
            $table->boolean('is_code1_usable')->default(1); // Can code1 be used?
            $table->timestamp('code1_used_at')->nullable(); // When was code1 used?
            $table->boolean('is_code2_usable')->default(1); // Can code2 be used?
            $table->timestamp('code2_used_at')->nullable(); // When was code2 used?

            // Voting state
            $table->boolean('can_vote_now')->default(0); // Has user verified the code?
            $table->boolean('has_voted')->default(0); // Has user completed voting?
            $table->boolean('vote_submitted')->default(0);
            $table->timestamp('voting_started_at')->nullable(); // When did voter start voting?

            // Sending
            $table->boolean('has_code1_sent')->default(0);
            $table->timestamp('code1_sent_at')->nullable();
            $table->boolean('has_code2_sent')->default(0);
            $table->timestamp('code2_sent_at')->nullable();

            // Expiry
            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('voting_time_minutes')->default(30);

            // Metadata
            $table->json('metadata')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('set null');

            // Indexes
            $table->unique(['election_id', 'user_id']);
            $table->index('code1');
            $table->index('code2');
            $table->index(['is_code1_usable', 'can_vote_now']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
