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
        Schema::table('voter_slugs', function (Blueprint $table) {
            // Session management
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('has_voted')->default(false);
            $table->boolean('can_vote_now')->default(true);
            $table->integer('voting_time_min')->nullable();

            // Step 1: Code verification
            $table->string('step_1_ip')->nullable();
            $table->timestamp('step_1_completed_at')->nullable();

            // Step 2: Vote agreement
            $table->string('step_2_ip')->nullable();
            $table->timestamp('step_2_completed_at')->nullable();

            // Step 3: Candidate selection
            $table->string('step_3_ip')->nullable();
            $table->timestamp('step_3_completed_at')->nullable();

            // Step 4: Verification
            $table->string('step_4_ip')->nullable();
            $table->timestamp('step_4_completed_at')->nullable();

            // Step 5: Completion
            $table->string('step_5_ip')->nullable();
            $table->timestamp('step_5_completed_at')->nullable();

            // Indexes for performance
            $table->index('expires_at');
            $table->index('is_active');
            $table->index('has_voted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('voter_slugs', function (Blueprint $table) {
            $table->dropColumn([
                'expires_at',
                'is_active',
                'has_voted',
                'can_vote_now',
                'voting_time_min',
                'step_1_ip',
                'step_1_completed_at',
                'step_2_ip',
                'step_2_completed_at',
                'step_3_ip',
                'step_3_completed_at',
                'step_4_ip',
                'step_4_completed_at',
                'step_5_ip',
                'step_5_completed_at',
            ]);
        });
    }
};
