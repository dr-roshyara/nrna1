<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PBDIGIT-213 C2 — voter_verifications as Admission Evidence (ADR_20261004_1549 D3).
 *
 * - verifier_role     WHO verified, in which role (ELECTION_CHIEF / ELECTION_DEPUTY_CHIEF);
 *                     nullable: legacy rows predate the role being recorded.
 * - method            HOW the requirement was satisfied (v1: officer_attestation).
 * - revocation_reason WHY the evidence's CURRENT admission effect ended
 *                     (e.g. suspension_confirmed). The historical fact is not deleted.
 *
 * The unique (election_id, user_id) is unchanged — single-row evidence is a known
 * technical limitation, not protocol history (gap G1).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('voter_verifications', function (Blueprint $table) {
            $table->string('verifier_role', 40)->nullable();
            $table->string('method', 40)->default('officer_attestation');
            $table->string('revocation_reason', 60)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('voter_verifications', function (Blueprint $table) {
            $table->dropColumn(['verifier_role', 'method', 'revocation_reason']);
        });
    }
};
