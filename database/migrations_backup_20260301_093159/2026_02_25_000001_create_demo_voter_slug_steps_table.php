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
        Schema::create('demo_voter_slug_steps', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organisation_id')->nullable()->index();
            $table->unsignedBigInteger('demo_voter_slug_id')->index();
            $table->string('slug')->nullable();
            $table->unsignedBigInteger('election_id')->nullable()->index();
            $table->integer('step');
            $table->json('step_data')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // Foreign key to demo_voter_slugs
            $table->foreign('demo_voter_slug_id')
                  ->references('id')
                  ->on('demo_voter_slugs')
                  ->onDelete('cascade');

            // Indexes for common queries
            $table->index(['demo_voter_slug_id', 'step']);
            $table->index(['election_id', 'step']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_voter_slug_steps');
    }
};
