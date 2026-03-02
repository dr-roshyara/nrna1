<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDemoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('demo_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('organisation_id')->nullable();  // MODE 1: NULL, MODE 2: org_id

            // Verification codes
            $table->string('code1')->nullable();
            $table->string('code2')->nullable();
            $table->string('code3')->nullable();
            $table->string('code4')->nullable();
            $table->string('vote_show_code')->nullable();

            // Code usability flags
            $table->boolean('is_code1_usable')->default(false);
            $table->boolean('is_code2_usable')->default(false);
            $table->boolean('is_code3_usable')->default(false);
            $table->boolean('is_code4_usable')->default(false);

            // Code sent timestamps
            $table->dateTime('code1_sent_at')->nullable();
            $table->dateTime('code2_sent_at')->nullable();
            $table->dateTime('code3_sent_at')->nullable();
            $table->dateTime('code4_sent_at')->nullable();

            // Code used timestamps
            $table->dateTime('code1_used_at')->nullable();
            $table->dateTime('code2_used_at')->nullable();
            $table->dateTime('code3_used_at')->nullable();
            $table->dateTime('code4_used_at')->nullable();

            // Voting state
            $table->boolean('can_vote_now')->default(false);
            $table->boolean('has_voted')->default(false);
            $table->boolean('vote_submitted')->default(false);
            $table->dateTime('vote_submitted_at')->nullable();

            // Agreement tracking
            $table->boolean('has_agreed_to_vote')->default(false);
            $table->dateTime('has_agreed_to_vote_at')->nullable();

            // Usage flags
            $table->boolean('has_code1_sent')->default(false);
            $table->boolean('has_code2_sent')->default(false);
            $table->boolean('has_used_code1')->default(false);
            $table->boolean('has_used_code2')->default(false);

            // Metadata
            $table->integer('voting_time_in_minutes')->default(30);
            $table->dateTime('vote_last_seen')->nullable();
            $table->string('client_ip')->nullable();
            $table->string('session_name')->nullable();
            $table->dateTime('voting_started_at')->nullable();
            $table->string('code_for_vote')->nullable();

            $table->timestamps();

            // Indexes
            $table->index('user_id');
            $table->index('election_id');
            $table->index('organisation_id');
            $table->unique(['user_id', 'election_id', 'organisation_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('demo_codes');
    }
}
