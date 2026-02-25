<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demo_voter_slugs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisation_id')->nullable()->index();
            $table->unsignedBigInteger('user_id')->nullable()->index();
            $table->unsignedBigInteger('election_id')->nullable()->index();
            $table->string('slug')->unique();
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->integer('current_step')->default(0);
            $table->json('step_meta')->nullable();
            $table->boolean('has_voted')->default(false);
            $table->boolean('can_vote_now')->default(true);
            $table->integer('voting_time_min')->nullable();

            // Step tracking - IP and timestamps
            $table->string('step_1_ip')->nullable();
            $table->timestamp('step_1_completed_at')->nullable();
            $table->string('step_2_ip')->nullable();
            $table->timestamp('step_2_completed_at')->nullable();
            $table->string('step_3_ip')->nullable();
            $table->timestamp('step_3_completed_at')->nullable();
            $table->string('step_4_ip')->nullable();
            $table->timestamp('step_4_completed_at')->nullable();
            $table->string('step_5_ip')->nullable();
            $table->timestamp('step_5_completed_at')->nullable();

            $table->timestamps();

            // Indexes for common queries
            $table->index(['election_id', 'user_id']);
            $table->index(['organisation_id', 'election_id']);
            $table->index(['user_id', 'election_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_voter_slugs');
    }
};
