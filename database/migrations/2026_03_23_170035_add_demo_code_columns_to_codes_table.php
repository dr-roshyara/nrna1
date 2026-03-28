<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add all columns from demo_codes that are missing from codes,
     * so CodeController can work with real elections the same way
     * it works with demo elections.
     */
    public function up(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            // Verification code columns (mirrors demo_codes naming)
            $table->string('code_to_open_voting_form', 32)->nullable()->after('client_ip');
            $table->timestamp('code_to_open_voting_form_sent_at')->nullable()->after('code_to_open_voting_form');
            $table->timestamp('code_to_open_voting_form_used_at')->nullable()->after('code_to_open_voting_form_sent_at');
            $table->boolean('is_code_to_open_voting_form_usable')->default(1)->after('code_to_open_voting_form_used_at');

            $table->string('code_to_save_vote', 32)->nullable()->after('is_code_to_open_voting_form_usable');
            $table->timestamp('code_to_save_vote_sent_at')->nullable()->after('code_to_save_vote');
            $table->timestamp('code_to_save_vote_used_at')->nullable()->after('code_to_save_vote_sent_at');
            $table->boolean('is_code_to_save_vote_usable')->default(0)->after('code_to_save_vote_used_at');

            // Sent flags
            $table->boolean('has_code1_sent')->default(0)->after('is_code_to_save_vote_usable');
            $table->boolean('has_code2_sent')->default(0)->after('has_code1_sent');

            // Voting progress flags
            $table->boolean('vote_submitted')->default(0)->after('has_code2_sent');
            $table->timestamp('vote_submitted_at')->nullable()->after('vote_submitted');
            $table->boolean('has_agreed_to_vote')->default(0)->after('vote_submitted_at');
            $table->timestamp('has_agreed_to_vote_at')->nullable()->after('has_agreed_to_vote');
            $table->timestamp('voting_started_at')->nullable()->after('has_agreed_to_vote_at');

            // Timing alias used by CodeController (voting_time_min already exists)
            $table->integer('voting_time_in_minutes')->default(30)->after('voting_started_at');

            // Audit voting_code hash
            $table->string('voting_code', 128)->nullable()->after('voting_time_in_minutes');
        });
    }

    public function down(): void
    {
        Schema::table('codes', function (Blueprint $table) {
            $table->dropColumn([
                'code_to_open_voting_form',
                'code_to_open_voting_form_sent_at',
                'code_to_open_voting_form_used_at',
                'is_code_to_open_voting_form_usable',
                'code_to_save_vote',
                'code_to_save_vote_sent_at',
                'code_to_save_vote_used_at',
                'is_code_to_save_vote_usable',
                'has_code1_sent',
                'has_code2_sent',
                'vote_submitted',
                'vote_submitted_at',
                'has_agreed_to_vote',
                'has_agreed_to_vote_at',
                'voting_started_at',
                'voting_time_in_minutes',
                'voting_code',
            ]);
        });
    }
};
