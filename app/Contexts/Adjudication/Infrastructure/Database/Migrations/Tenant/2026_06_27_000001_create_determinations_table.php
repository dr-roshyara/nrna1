<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('determinations', function (Blueprint $table) {
            // Aggregate STATE only — ruling content lives in the
            // DeterminationIssued event (outbox), not this write row.
            $table->string('id', 64)->primary();
            $table->string('organisation_id', 64)->index();
            $table->string('challenge_ref', 64);
            $table->string('state', 20);
            $table->string('evidence_envelope_ref', 128);
            $table->string('issued_by_authority', 128);
            $table->string('jurisdiction', 128);
            $table->timestamps();
            $table->softDeletes();

            // Logical uniqueness reinforced at the DB: one determination per
            // challenge, per tenant (closes the duplicate-determination race).
            $table->unique(['organisation_id', 'challenge_ref'], 'uniq_determination_per_challenge');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('determinations');
    }
};
