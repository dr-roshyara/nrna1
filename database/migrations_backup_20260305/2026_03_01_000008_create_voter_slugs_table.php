<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_slugs', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // Unique voting link: /v/{slug}
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('organisation_id')->nullable();

            // Voting progress tracking
            $table->unsignedSmallInteger('current_step')->default(1); // 1-5: code entry, agreement, vote, verify, complete
            $table->json('step_meta')->nullable(); // Step-specific metadata

            // Expiry and status
            $table->timestamp('expires_at')->nullable();
            $table->boolean('is_active')->default(1);

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('set null');

            // Indexes
            $table->index('slug');
            $table->index(['user_id', 'election_id']);
            $table->index('is_active');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_slugs');
    }
};
