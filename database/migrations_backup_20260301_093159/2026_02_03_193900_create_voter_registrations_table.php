<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVoterRegistrationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * Creates the voter_registrations table to track voter status per election.
     * This separates voter identity and intent from the users table.
     * NO foreign key constraints to maintain independence from external schemas.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('voter_registrations', function (Blueprint $table) {
            $table->id();

            // References (NO foreign key constraints for flexibility)
            $table->unsignedBigInteger('user_id')->comment('Reference to users table');
            $table->unsignedBigInteger('election_id')->comment('Reference to elections table');

            // Voter status
            $table->enum('status', [
                'pending',      // Wants to vote, waiting approval
                'approved',     // Approved to vote (can_vote = true)
                'rejected',     // Rejected from voting
                'voted'         // Has voted
            ])->default('pending');

            // Election type for easy filtering
            $table->enum('election_type', ['demo', 'real'])->default('demo')
                ->comment('Cached from elections table for performance');

            // Timestamps
            $table->timestamp('registered_at')->nullable()
                ->comment('When user registered to vote in this election');
            $table->timestamp('approved_at')->nullable()
                ->comment('When committee approved voter');
            $table->timestamp('voted_at')->nullable()
                ->comment('When voter submitted their vote');

            // Audit trail
            $table->string('approved_by')->nullable()
                ->comment('Name of committee member who approved');
            $table->string('rejected_by')->nullable()
                ->comment('Name of committee member who rejected');
            $table->text('rejection_reason')->nullable()
                ->comment('Reason for rejection if rejected');

            // Metadata
            $table->json('metadata')->nullable()
                ->comment('Additional data like IP address, browser, etc.');

            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'election_type']);
            $table->index(['election_id', 'status']);
            $table->index(['election_type', 'status']);
            $table->unique(['user_id', 'election_id']);

            // For pagination and filtering
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('voter_registrations');
    }
}
