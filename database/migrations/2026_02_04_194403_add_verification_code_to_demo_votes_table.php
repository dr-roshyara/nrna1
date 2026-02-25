<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVerificationCodeToDemoVotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Add verification code for vote retrieval (like real votes)
            if (!Schema::hasColumn('demo_votes', 'verification_code')) {
                $table->string('verification_code')->nullable()->after('voting_code');
                $table->index('verification_code');
            }

            // Add user_id for tracking (even though votes are anonymous)
            if (!Schema::hasColumn('demo_votes', 'user_id')) {
                $table->string('user_id')->nullable()->after('election_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('demo_votes', function (Blueprint $table) {
            // Drop foreign key constraints first if they exist
            try {
                $table->dropForeign(['user_id']);
            } catch (\Exception $e) {
                // FK doesn't exist, continue
            }

            if (Schema::hasColumn('demo_votes', 'verification_code')) {
                try {
                    $table->dropIndex(['verification_code']);
                } catch (\Exception $e) {
                    // Index doesn't exist, continue
                }
                $table->dropColumn('verification_code');
            }

            if (Schema::hasColumn('demo_votes', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
}
