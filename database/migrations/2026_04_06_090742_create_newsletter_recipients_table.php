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
        Schema::create('newsletter_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organisation_newsletter_id')->constrained('organisation_newsletters')->cascadeOnDelete();
            $table->uuid('member_id');
            $table->string('email');
            $table->string('name')->nullable();
            $table->enum('status', ['pending','sending','sent','failed','skipped'])->default('pending');
            $table->string('idempotency_key', 64)->nullable()->unique();
            $table->text('error_message')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('member_id')->references('id')->on('members')->cascadeOnDelete();
            $table->index(['organisation_newsletter_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_recipients');
    }
};
