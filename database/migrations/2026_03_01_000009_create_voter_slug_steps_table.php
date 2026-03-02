<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_slug_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('voter_slug_id');
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('organisation_id')->nullable(); // Multi-tenancy scoping

            // Step tracking: 1=code entry, 2=agreement, 3=vote selection, 4=verification, 5=completion
            $table->unsignedSmallInteger('step');

            // Audit trail
            $table->string('ip_address')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            // Metadata for the step
            $table->json('metadata')->nullable(); // Step-specific data (e.g., code entered, agreement accepted, selections made)
            $table->json('step_data')->nullable(); // Additional step-specific data

            $table->timestamps();

            // Foreign keys
            $table->foreign('voter_slug_id')
                  ->references('id')
                  ->on('voter_slugs')
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
            $table->index(['voter_slug_id', 'step']);
            $table->index('organisation_id');
            $table->unique(['voter_slug_id', 'election_id', 'step']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_slug_steps');
    }
};
