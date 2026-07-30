<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * WP-2 — durable state for the Adjudication Process Manager (EPIC-004K §11).
 *
 * This is NOT an aggregate table: the process is orchestration, and the
 * constitutional record remains `determinations` + the DeterminationIssued event.
 * One truth, two records, one authoritative.
 *
 * The unique index is INV-B1's two-seat pattern mirrored at the process side —
 * the application guard states the rule, this index makes the race impossible.
 * It is PARTIAL, scoped to non-terminal rows, because PM-1 opens "exactly one
 * ACTIVE process per challenge" (EPIC-004K §3) and §6's guard reads "no ACTIVE
 * process exists": a challenge may accumulate processes over time (an expired one
 * returns to Contestation, which may route it again — WP-5), but never two at
 * once. A full unique index would forbid what the guard permits.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('adjudication_processes', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('organisation_id', 64)->index();
            $table->string('challenge_ref', 64)->index();
            $table->string('status', 40);

            // Event-logged conduct: the opaque references admitted into this
            // judgment (ADR-T11 — references only, never linkable content).
            $table->json('admitted_evidence');

            // Conclusion — written as ONE fact with the considered set and the
            // deciding authority (PM-5). Null until the process concludes.
            $table->json('considered_evidence')->nullable();
            $table->string('concluded_by_authority', 128)->nullable();
            $table->string('outcome', 20)->nullable();
            $table->string('legitimacy', 20)->nullable();
            $table->text('reason')->nullable();
            $table->timestampTz('concluded_at')->nullable();

            $table->timestampTz('opened_at');
            $table->timestamps();
        });

        // Partial unique index — PostgreSQL. Terminal rows are excluded so an
        // expired process never blocks a lawfully re-routed challenge.
        DB::statement(<<<'SQL'
            CREATE UNIQUE INDEX uniq_active_adjudication_process_per_challenge
            ON adjudication_processes (organisation_id, challenge_ref)
            WHERE status NOT IN ('concluded_ruling_requested', 'concluded_failure_declared', 'expired')
        SQL);
    }

    public function down(): void
    {
        Schema::dropIfExists('adjudication_processes');
    }
};
