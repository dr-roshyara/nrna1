<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ORGANISATION migration set (PBDIGIT-345 task 9a; tactical design §3.4): the transactional outbox IN the organisation's
 * own database, so a business change (e.g. Membership's C1) and its event commit or roll back together there. AT THE
 * CENTRAL SHAPE (central migrations 2026_05_04_000001 and 2026_07_10_000001 consolidated; PostgreSQL appends the two
 * provenance columns, as it did centrally). The one difference: organisation_id references this database's
 * organisation_identity, never the central `organisations`: an event of another organisation cannot be written here.
 * OrganisationMigrationsTest proves the parity. RULE: a change to the central outbox needs the same change here.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('outbox_events', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('event_id')->unique();
            $table->uuid('organisation_id');
            $table->string('aggregate_type', 100);
            $table->uuid('aggregate_id');
            $table->string('event_type', 150);
            $table->json('payload');
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            $table->integer('attempts')->default(0);
            $table->timestamp('available_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('processed_at')->nullable();
            $table->uuid('correlation_id')->nullable()->index();
            $table->uuid('causation_id')->nullable()->index();
            $table->index(['status', 'available_at', 'organisation_id']);
            $table->index(['organisation_id', 'processed_at']);
            $table->foreign('organisation_id', 'outbox_events_organisation_identity_fk')->references('id')->on('organisation_identity');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('outbox_events');
    }
};
