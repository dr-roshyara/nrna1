<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('codes', function (Blueprint $table) {
            // Primary & Foreign Keys
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organisation_id')->nullable();

            // Two-code system (can be extended to 4-code)
            $table->string('code1'); // First code (entry)
            $table->string('code2')->nullable(); // Second code (verification)
            $table->string('code3')->nullable(); // Third code (optional)
            $table->string('code4')->nullable(); // Fourth code (optional)

            // Code State Tracking - Code1
            $table->boolean('is_code1_usable')->default(true); // Can code1 be used?
            $table->timestamp('code1_used_at')->nullable(); // When was code1 used?
            $table->boolean('has_used_code1')->default(false); // Has code1 been used?
            $table->boolean('has_code1_sent')->default(false); // Has code1 been sent?
            $table->timestamp('code1_sent_at')->nullable(); // When was code1 sent?

            // Code State Tracking - Code2
            $table->boolean('is_code2_usable')->default(true); // Can code2 be used?
            $table->timestamp('code2_used_at')->nullable(); // When was code2 used?
            $table->boolean('has_used_code2')->default(false); // Has code2 been used?
            $table->boolean('has_code2_sent')->default(false); // Has code2 been sent?
            $table->timestamp('code2_sent_at')->nullable(); // When was code2 sent?

            // Code State Tracking - Code3
            $table->boolean('is_code3_usable')->default(true); // Can code3 be used?
            $table->timestamp('code3_used_at')->nullable(); // When was code3 used?

            // Code State Tracking - Code4
            $table->boolean('is_code4_usable')->default(true); // Can code4 be used?
            $table->timestamp('code4_used_at')->nullable(); // When was code4 used?

            // Voting State
            $table->boolean('can_vote_now')->default(false); // Has user verified the code?
            $table->boolean('has_voted')->default(false); // Has user completed voting?
            $table->boolean('vote_submitted')->default(false); // Has vote been submitted?
            $table->timestamp('vote_submitted_at')->nullable(); // When was vote submitted?
            $table->timestamp('voting_started_at')->nullable(); // When did voter start voting?
            $table->dateTime('vote_completed_at')->nullable(); // When was voting completed?

            // Voter Agreement
            $table->boolean('has_agreed_to_vote')->default(false); // Has voter agreed to vote?
            $table->timestamp('has_agreed_to_vote_at')->nullable(); // When did voter agree?

            // Vote Verification
            $table->string('vote_show_code')->nullable(); // Code to show verification proof
            $table->timestamp('vote_last_seen')->nullable(); // When was vote last viewed for verification
            $table->string('code_for_vote')->nullable(); // Code used for vote

            // Validation
            $table->boolean('is_codemodel_valid')->default(true); // Is the code model valid?

            // Session & Timing
            $table->string('session_name')->nullable(); // Session identifier
            $table->string('client_ip')->nullable(); // Client IP address
            $table->integer('voting_time_in_minutes')->default(30); // Voting time in minutes (replaces voting_time_minutes)
            $table->timestamp('expires_at')->nullable(); // Code expiration time

            // Metadata
            $table->json('metadata')->nullable(); // Additional metadata

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
