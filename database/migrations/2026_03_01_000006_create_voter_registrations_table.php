<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_registrations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('election_id');
            $table->unsignedBigInteger('user_id');

            // Voter status: pending (waiting approval), approved (can vote), voted (completed voting), suspended (revoked)
            $table->enum('status', ['pending', 'approved', 'voted', 'suspended'])->default('pending');

            // Timeline
            $table->timestamp('approved_at')->nullable();
            $table->timestamp('voted_at')->nullable();
            $table->timestamp('suspended_at')->nullable();

            // Audit
            $table->string('approved_by_user')->nullable(); // Name of commission member who approved
            $table->string('ip_address')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('election_id')
                  ->references('id')
                  ->on('elections')
                  ->onDelete('cascade');

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            // Indexes
            $table->unique(['election_id', 'user_id']);
            $table->index(['election_id', 'status']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_registrations');
    }
};
