<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Demo Posts
        Schema::create('demo_posts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->string('name');
            $table->string('nepali_name')->nullable();
            $table->boolean('is_national_wide')->default(true);
            $table->string('state_name')->nullable();
            $table->integer('required_number');
            $table->timestamps();

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

        // Demo Candidacies
        Schema::create('demo_candidacies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('post_id');
            $table->uuid('user_id')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->integer('position_order')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('post_id')
                  ->references('id')
                  ->on('demo_posts')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->index(['organisation_id', 'post_id']);
        });

        // Demo Codes
        Schema::create('demo_codes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->string('code1');
            $table->string('code2')->nullable();
            $table->boolean('is_code1_usable')->default(true);
            $table->timestamp('code1_used_at')->nullable();
            $table->timestamp('code2_used_at')->nullable();
            $table->boolean('can_vote_now')->default(false);
            $table->boolean('has_voted')->default(false);
            $table->integer('voting_time_min')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'election_id']);
            $table->unique(['code1', 'organisation_id']);
        });

        // Demo Voter Slugs
        Schema::create('demo_voter_slugs', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->uuid('user_id');
            $table->string('slug')->unique();
            $table->integer('current_step')->default(0);
            $table->json('step_meta')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'election_id', 'user_id']);
        });

        // Demo Voter Slug Steps
        Schema::create('demo_voter_slug_steps', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('voter_slug_id');
            $table->integer('step');
            $table->string('ip_address')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('voter_slug_id')
                  ->references('id')
                  ->on('demo_voter_slugs')
                  ->onDelete('cascade');

            $table->index(['organisation_id', 'voter_slug_id']);
        });

        // Demo Votes (anonymous)
        Schema::create('demo_votes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            $table->string('voting_code')->unique();
            $table->json('candidate_selections')->nullable();
            $table->boolean('no_vote_option')->default(false);
            $table->timestamp('voted_at');
            $table->string('voter_ip')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('demo_votes');
        Schema::dropIfExists('demo_voter_slug_steps');
        Schema::dropIfExists('demo_voter_slugs');
        Schema::dropIfExists('demo_codes');
        Schema::dropIfExists('demo_candidacies');
        Schema::dropIfExists('demo_posts');
    }
};
