<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PB-003-C2 — consumer-side inbox (ADR-T4): per-consumer dedupe + park state.
 *
 * Traceability: Blueprint §6 · D-03 (UNIQUE(event_id, consumer_context)) ·
 * D-06 (organisation/correlation/causation propagation) · Matrix: Inbox
 *
 * Conventions MIRRORED from create_outbox_events_table (compared 2026-07-06):
 * uuid PK · organisation FK with cascade (shared-messaging-tier convention;
 * context tenant-tier tables like determinations use string+index instead) ·
 * enum status · created_at useCurrent · NO updated_at (each transition writes
 * its own explicit timestamp: processed_at / parked_until / park_deadline).
 *
 * INTENTIONAL DIFFERENCE from outbox: status has NO default. Outbox rows are
 * legitimately created "pending, awaiting relay"; inbox rows are created and
 * finalized inside ONE consumption transaction (Blueprint §5), so an implicit
 * initial state would invent a lifecycle state the Blueprint does not define.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inbox_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id');                                              // producer EventId (dedupe key part 1)
            $table->string('consumer_context', 100);                               // consuming BC (dedupe key part 2, D-03)
            $table->string('event_type', 150);                                     // canonical Catalog v1.0 name
            $table->json('payload');                                               // delivered payload
            $table->uuid('organisation_id');                                       // tenant isolation (D-06)
            $table->uuid('correlation_id')->nullable();                            // loop identity (D-06)
            $table->uuid('causation_id')->nullable();                              // causing EventId (D-06)
            $table->enum('status', ['processed', 'parked', 'dead']);               // explicit, no default (Blueprint §6)
            $table->integer('park_attempts')->default(0);
            $table->timestamp('parked_until')->nullable();                         // next re-drive attempt (§7 F4)
            $table->timestamp('park_deadline')->nullable();                        // park-timeout -> dead (§7 F4)
            $table->timestamp('processed_at')->nullable();                         // terminal-state audit timestamp
            $table->timestamp('created_at')->useCurrent();

            $table->unique(['event_id', 'consumer_context']);                      // THE dedupe key (D-03)
            $table->index(['status', 'parked_until']);                             // re-drive polling
            $table->index(['organisation_id', 'created_at']);                      // tenant-scoped investigation (§14)
            $table->foreign('organisation_id')->references('id')->on('organisations')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inbox_events');
    }
};
