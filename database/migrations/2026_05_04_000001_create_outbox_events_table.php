<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbox_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id')->unique();                                    // idempotency guard
            $table->uuid('organisation_id');                                       // tenant isolation
            $table->string('aggregate_type', 100);                                 // Fee, Application, etc.
            $table->uuid('aggregate_id');                                          // which FeeId, etc.
            $table->string('event_type', 150);                                     // FeePaid, FeeWaived
            $table->json('payload');                                               // full event data
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('attempts')->default(0);                              // retry count
            $table->timestamp('available_at')->useCurrent();                       // for retry backoff
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();

            $table->index(['status', 'available_at', 'organisation_id']);
            $table->index(['organisation_id', 'processed_at']);
            $table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbox_events');
    }
};
