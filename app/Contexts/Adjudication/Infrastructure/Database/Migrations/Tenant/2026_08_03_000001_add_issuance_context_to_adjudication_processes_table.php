<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * WP-4B — durable issuance context for the CONCLUDE -> ISSUE seam (EPIC-004K SS11).
 *
 * Six nullable columns, all of them RETAINED FACTS rather than derived ones. Each
 * arrives from a different producer and is stored as delivered (R-73, R-75):
 *
 *   contested_* ........... Contestation's fact, arriving on ChallengeRaised
 *   evidence_envelope_ref . Evidence's fact, arriving on its integration event
 *   jurisdiction .......... the deciding authority's fact, arriving with its decision
 *   issuance_requested_at . Adjudication's OWN fact -- when it requested issuance
 *
 * All nullable, and deliberately so: a process opened before this slice has none of
 * them, and the seam's guard reads `issuance_requested_at IS NULL` as *"concluded but
 * not yet requested"* -- the crash window SS11 makes possible by design.
 *
 * NO seventh status. `AdjudicationProcessStatus` declares its set closed, and adding a
 * case is an architectural act this slice is not authorized to take; a timestamp answers
 * the same question without touching the Published Language.
 *
 * NO new index. The redrive query (status + issuance_requested_at IS NULL) is a RECOVERY
 * path, not a hot path, and the existing organisation_id index already narrows it. An
 * index here would be a performance decision taken without performance evidence.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('adjudication_processes', function (Blueprint $table) {
            // ContestedOutcomeRef, stored as its three wire parts. Adjudication holds no
            // foreign key to Contestation or Election -- these are REFERENCES reconstructed
            // locally (ADR-T16), never joins across a context boundary.
            $table->string('contested_election_id', 64)->nullable();
            $table->string('contested_type', 40)->nullable();
            $table->string('contested_target_id', 64)->nullable();

            // Opaque envelope reference -- no evidence content, ever (ADR-T11).
            $table->string('evidence_envelope_ref', 128)->nullable();

            $table->string('jurisdiction', 128)->nullable();

            $table->timestampTz('issuance_requested_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('adjudication_processes', function (Blueprint $table) {
            $table->dropColumn([
                'contested_election_id',
                'contested_type',
                'contested_target_id',
                'evidence_envelope_ref',
                'jurisdiction',
                'issuance_requested_at',
            ]);
        });
    }
};
