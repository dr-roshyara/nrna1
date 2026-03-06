<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('election_id');
            // CRITICAL: NO user_id - votes are completely anonymous
            // vote_hash: SHA256 cryptographic proof (cannot link to voter)
            $table->string('vote_hash')->unique();
            // Candidate selections (candidate_01 through candidate_60)
            $table->string('candidate_01')->nullable();
            $table->string('candidate_02')->nullable();
            $table->string('candidate_03')->nullable();
            $table->string('candidate_04')->nullable();
            $table->string('candidate_05')->nullable();
            $table->string('candidate_06')->nullable();
            $table->string('candidate_07')->nullable();
            $table->string('candidate_08')->nullable();
            $table->string('candidate_09')->nullable();
            $table->string('candidate_10')->nullable();
            $table->string('candidate_11')->nullable();
            $table->string('candidate_12')->nullable();
            $table->string('candidate_13')->nullable();
            $table->string('candidate_14')->nullable();
            $table->string('candidate_15')->nullable();
            $table->string('candidate_16')->nullable();
            $table->string('candidate_17')->nullable();
            $table->string('candidate_18')->nullable();
            $table->string('candidate_19')->nullable();
            $table->string('candidate_20')->nullable();
            $table->string('candidate_21')->nullable();
            $table->string('candidate_22')->nullable();
            $table->string('candidate_23')->nullable();
            $table->string('candidate_24')->nullable();
            $table->string('candidate_25')->nullable();
            $table->string('candidate_26')->nullable();
            $table->string('candidate_27')->nullable();
            $table->string('candidate_28')->nullable();
            $table->string('candidate_29')->nullable();
            $table->string('candidate_30')->nullable();
            $table->string('candidate_31')->nullable();
            $table->string('candidate_32')->nullable();
            $table->string('candidate_33')->nullable();
            $table->string('candidate_34')->nullable();
            $table->string('candidate_35')->nullable();
            $table->string('candidate_36')->nullable();
            $table->string('candidate_37')->nullable();
            $table->string('candidate_38')->nullable();
            $table->string('candidate_39')->nullable();
            $table->string('candidate_40')->nullable();
            $table->string('candidate_41')->nullable();
            $table->string('candidate_42')->nullable();
            $table->string('candidate_43')->nullable();
            $table->string('candidate_44')->nullable();
            $table->string('candidate_45')->nullable();
            $table->string('candidate_46')->nullable();
            $table->string('candidate_47')->nullable();
            $table->string('candidate_48')->nullable();
            $table->string('candidate_49')->nullable();
            $table->string('candidate_50')->nullable();
            $table->string('candidate_51')->nullable();
            $table->string('candidate_52')->nullable();
            $table->string('candidate_53')->nullable();
            $table->string('candidate_54')->nullable();
            $table->string('candidate_55')->nullable();
            $table->string('candidate_56')->nullable();
            $table->string('candidate_57')->nullable();
            $table->string('candidate_58')->nullable();
            $table->string('candidate_59')->nullable();
            $table->string('candidate_60')->nullable();
            // Posts where voter selected "no vote" option
            $table->json('no_vote_posts')->nullable();
            // When the vote was cast
            $table->timestamp('cast_at');
            // Metadata for audit and verification
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

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
        Schema::dropIfExists('votes');
    }
};
