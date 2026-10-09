<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Membership Slice 1b (PBDIGIT-260): persistence for the organisational Member aggregate.
 * Contract: docs/publicdigit/reviews/2026-10-09-membership-slice1b-contract.md §5.
 *
 * A normal deployable migration (PostgreSQL only, DB-1). In Slice 1b it is applied
 * only by the test suite; it is not yet to be run on development or production.
 *
 * - org_memberships          one row per Member: tenant-scoped identity (INV-M) and version
 * - org_membership_facts     append-only fact history (seq order), calendar-date valid time (EP-Q6 = T1)
 * - org_membership_commands  idempotency: appended outcomes per tenant-scoped key
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('org_memberships', function (Blueprint $table) {
            $table->uuid('organisation_id');
            $table->text('id');
            $table->text('person_ref');
            $table->integer('version')->default(0);
            $table->timestampTz('created_at', 6);

            $table->primary(['organisation_id', 'id']);
            $table->unique(['organisation_id', 'person_ref']);
            $table->foreign('organisation_id')->references('id')->on('organisations')->restrictOnDelete();
        });
        DB::statement('ALTER TABLE org_memberships ADD CONSTRAINT org_memberships_version_check CHECK (version >= 0)');

        Schema::create('org_membership_facts', function (Blueprint $table) {
            $table->uuid('organisation_id');
            $table->text('membership_id');
            $table->integer('seq');
            $table->text('fact_id');
            $table->text('kind');
            $table->date('effective_at');
            $table->text('initial_term')->nullable();
            $table->date('initial_term_until')->nullable();
            $table->text('period_fact_id')->nullable();
            $table->text('end_kind')->nullable();
            $table->timestampTz('decided_at', 6)->nullable();
            $table->timestampTz('recorded_at', 6);
            $table->jsonb('provenance');

            $table->primary(['organisation_id', 'membership_id', 'seq']);
            $table->unique(['organisation_id', 'membership_id', 'fact_id']);
            $table->foreign(['organisation_id', 'membership_id'])
                ->references(['organisation_id', 'id'])->on('org_memberships')->restrictOnDelete();
        });
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              ADD CONSTRAINT org_membership_facts_period_fk
                FOREIGN KEY (organisation_id, membership_id, period_fact_id)
                REFERENCES org_membership_facts (organisation_id, membership_id, fact_id),
              ADD CONSTRAINT org_membership_facts_seq_check CHECK (seq >= 1),
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND initial_term IS NOT NULL AND period_fact_id IS NULL AND end_kind IS NULL)
                OR (kind = 'end' AND initial_term IS NULL AND initial_term_until IS NULL
                    AND period_fact_id IS NOT NULL AND end_kind IS NOT NULL)),
              ADD CONSTRAINT org_membership_facts_initial_term_check CHECK (
                initial_term IS NULL
                OR (initial_term IN ('none', 'unlimited') AND initial_term_until IS NULL)
                OR (initial_term = 'until' AND initial_term_until IS NOT NULL))
            SQL);

        Schema::create('org_membership_commands', function (Blueprint $table) {
            $table->uuid('organisation_id');
            $table->text('idempotency_key');
            $table->text('fingerprint');
            $table->text('membership_id');
            $table->text('fact_id');
            $table->integer('seq');
            $table->timestampTz('recorded_at', 6);

            $table->primary(['organisation_id', 'idempotency_key']);
            $table->foreign(['organisation_id', 'membership_id'])
                ->references(['organisation_id', 'id'])->on('org_memberships')->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('org_membership_commands');
        Schema::dropIfExists('org_membership_facts');
        Schema::dropIfExists('org_memberships');
    }
};
