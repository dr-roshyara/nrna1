<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisation_participants', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('organisation_id');
            $table->uuid('user_id');
            $table->enum('participant_type', ['staff', 'guest', 'election_committee']);
            $table->string('role')->nullable();
            $table->timestamp('assigned_at')->useCurrent();
            $table->timestamp('expires_at')->nullable();
            $table->json('permissions')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('organisation_id')
                  ->references('id')->on('organisations')->onDelete('cascade');
            $table->foreign('user_id')
                  ->references('id')->on('users')->onDelete('cascade');

            $table->index(['organisation_id', 'participant_type']);
            $table->index(['organisation_id', 'user_id']);
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_participants');
    }
};
