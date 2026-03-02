<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Demo Posts
        Schema::create('demo_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->boolean('is_national_wide')->default(true);
            $table->string('state_name')->nullable();
            $table->unsignedInteger('required_number')->default(1);
            $table->boolean('select_all_required')->default(true);
            $table->unsignedInteger('position_order')->default(0);
            $table->timestamps();

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->index(['election_id', 'is_national_wide']);
            $table->index(['election_id', 'state_name']);
        });

        // Demo Candidacies
        Schema::create('demo_candidacies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedInteger('position_order')->default(0);
            $table->text('bio')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('political_party')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('demo_posts')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('restrict');

            $table->index(['election_id', 'post_id']);
            $table->unique(['post_id', 'user_id']);
        });

        // Demo Codes
        Schema::create('demo_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('organisation_id')->nullable();

            $table->string('code1');
            $table->string('code2')->nullable();
            $table->string('code3')->nullable();
            $table->string('code4')->nullable();

            $table->boolean('is_code1_usable')->default(0);
            $table->timestamp('code1_used_at')->nullable();
            $table->boolean('is_code2_usable')->default(0);
            $table->timestamp('code2_used_at')->nullable();
            $table->boolean('is_code3_usable')->default(0);
            $table->timestamp('code3_used_at')->nullable();
            $table->boolean('is_code4_usable')->default(0);
            $table->timestamp('code4_used_at')->nullable();

            $table->boolean('can_vote_now')->default(0);
            $table->boolean('has_voted')->default(0);
            $table->boolean('vote_submitted')->default(0);
            $table->timestamp('voting_started_at')->nullable();

            $table->boolean('has_code1_sent')->default(0);
            $table->timestamp('code1_sent_at')->nullable();
            $table->boolean('has_code2_sent')->default(0);
            $table->timestamp('code2_sent_at')->nullable();

            $table->timestamp('expires_at')->nullable();
            $table->unsignedInteger('voting_time_minutes')->default(30);

            // Additional columns for vote verification
            $table->string('vote_show_code')->nullable();
            $table->timestamp('vote_last_seen')->nullable();

            $table->json('metadata')->nullable();
            $table->timestamps();

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

            $table->unique(['election_id', 'user_id']);
            $table->index('code1');
            $table->index('code2');
            $table->index(['is_code1_usable', 'can_vote_now']);
            $table->index('expires_at');
        });

        // Demo Votes
        Schema::create('demo_votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('organisation_id')->nullable(); // Allows NULL for public demo

            // Same anonymity design as production votes
            $table->string('vote_hash')->unique(); // Cryptographic proof

            for ($i = 1; $i <= 60; $i++) {
                $table->unsignedBigInteger('candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT))->nullable();
            }

            $table->json('no_vote_posts')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('cast_at');
            $table->timestamps();

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->index('election_id');
            $table->index('organisation_id');
            $table->index('vote_hash');
            $table->index('cast_at');
            $table->index(['election_id', 'organisation_id']);
        });

        // Demo Results
        Schema::create('demo_results', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vote_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('post_id');
            $table->unsignedBigInteger('candidate_id')->nullable();
            $table->unsignedBigInteger('organisation_id')->nullable();

            // Verification without exposure
            $table->string('vote_hash')->nullable(); // For cross-reference with vote

            $table->unsignedInteger('vote_count')->default(1);
            $table->timestamps();

            $table->foreign('vote_id')
                  ->references('id')
                  ->on('demo_votes')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('demo_posts')
                  ->onDelete('cascade');

            $table->foreign('candidate_id')
                  ->references('id')
                  ->on('demo_candidacies')
                  ->onDelete('set null');

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('set null');

            $table->index(['election_id', 'post_id']);
            $table->index(['post_id', 'candidate_id']);
            $table->index(['election_id', 'organisation_id']);
            $table->index('vote_hash');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('demo_results');
        Schema::dropIfExists('demo_votes');
        Schema::dropIfExists('demo_codes');
        Schema::dropIfExists('demo_candidacies');
        Schema::dropIfExists('demo_posts');
    }
};
