<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('organisation_invitations', function (Blueprint $table) {
            $table->id();
            $table->uuid('organisation_id');
            $table->string('email');
            $table->string('role')->default('member');
            $table->string('token', 64)->unique();
            $table->enum('status', ['pending', 'accepted', 'expired', 'cancelled'])->default('pending');
            $table->uuid('invited_by');
            $table->uuid('accepted_by')->nullable();
            $table->timestamp('expires_at');
            $table->timestamp('accepted_at')->nullable();
            $table->unsignedTinyInteger('resend_count')->default(0);
            $table->text('message')->nullable();
            $table->timestamps();

            $table->foreign('organisation_id')
                  ->references('id')
                  ->on('organisations')
                  ->onDelete('cascade');

            $table->foreign('invited_by')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('accepted_by')
                  ->references('id')
                  ->on('users')
                  ->nullOnDelete();

            $table->index(['organisation_id', 'status']);
            $table->index(['email', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('organisation_invitations');
    }
};
