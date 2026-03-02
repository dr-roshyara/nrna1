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

            // Two-code system (can be extended to 4-code)
            $table->string('code1'); // First code (entry)
            $table->string('code2')->nullable(); // Second code (verification)
            $table->string('code3')->nullable(); // Third code (optional)
            $table->string('code4')->nullable(); // Fourth code (optional)

            // Code state
            $table->boolean('is_code1_usable')->default(0); // Can code1 be used?
            $table->timestamp('code1_used_at')->nullable(); // When was code1 used?
            $table->boolean('is_code2_usable')->default(0); // Can code2 be used?
            $table->timestamp('code2_used_at')->nullable(); // When was code2 used?
            $table->boolean('is_code3_usable')->default(0); // Can code3 be used?
            $table->timestamp('code3_used_at')->nullable(); // When was code3 used?
            $table->boolean('is_code4_usable')->default(0); // Can code4 be used?
            $table->timestamp('code4_used_at')->nullable(); // When was code4 used?

            // Voting state
            $table->boolean('can_vote_now')->default(0); // Has user verified the code?
            $table->boolean('has_voted')->default(0); // Has user completed voting?
            $table->boolean('vote_submitted')->default(0);
            $table->timestamp('voting_started_at')->nullable(); // When did voter start voting?

            // Vote verification
            $table->string('vote_show_code')->nullable(); // Code to show verification proof
            $table->timestamp('vote_last_seen')->nullable(); // When was vote last viewed for verification

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
            // Foreign key for organisation_id is now enforced

            // Indexes
            $table->unique(['election_id', 'user_id']);
            $table->index('code1');
            $table->index('code2'); // For verification lookup
            $table->index(['is_code1_usable', 'can_vote_now']); // For voter status queries
            $table->index('expires_at'); // For expiration checks
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('codes');
    }
};
