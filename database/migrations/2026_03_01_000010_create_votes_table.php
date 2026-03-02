<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('organisation_id');

            // CRITICAL ANONYMITY DESIGN:
            // - NO user_id column (votes are completely anonymous!)
            // - vote_hash: Cryptographic proof of vote (allows verification without exposing user)
            // - Proves user voted without revealing HOW they voted
            $table->string('vote_hash')->unique(); // SHA256 hash(user_id + election_id + code + timestamp)

            // Vote data - 60 candidate slots (numbered positions)
            for ($i = 1; $i <= 60; $i++) {
                $table->unsignedBigInteger('candidate_' . str_pad($i, 2, '0', STR_PAD_LEFT))->nullable();
            }

            // Special option for "no vote" / abstention
            $table->json('no_vote_posts')->nullable(); // Posts where voter abstained

            // Metadata for verification and audit
            $table->json('metadata')->nullable();
            $table->timestamp('cast_at');
            $table->timestamps();

            // Foreign keys
            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            // Indexes
            $table->index('election_id');
            $table->index('organisation_id');
            $table->index('vote_hash'); // For verification lookup
            $table->index('cast_at'); // For chronological queries
            $table->index(['election_id', 'organisation_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};
