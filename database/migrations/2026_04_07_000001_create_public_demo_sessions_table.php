<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_demo_sessions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('session_token', 255)->unique();
            $table->uuid('election_id');
            $table->string('display_code', 255);
            $table->integer('current_step')->default(1);
            $table->boolean('code_verified')->default(false);
            $table->boolean('agreed')->default(false);
            $table->json('candidate_selections')->nullable();
            $table->boolean('has_voted')->default(false);
            $table->timestamp('voted_at')->nullable();
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->foreign('election_id')
                ->references('id')
                ->on('elections')
                ->cascadeOnDelete();

            $table->index('session_token');
            $table->index(['session_token', 'election_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_demo_sessions');
    }
};
