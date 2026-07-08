<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Greenfield reaction-state ledger for the Election context: which determinations have
 * already corrected which election, per organisation. Append-only idempotency guard.
 *
 * NOTE: this is NOT the elections table (owned by the legacy platform) — greenfield does
 * not own election lifecycle yet (Strangler). This table holds only reaction state.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('election_applied_determinations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('organisation_id', 64)->index();
            $table->string('election_id', 128);
            $table->string('determination_id', 128);
            $table->timestamps();

            $table->unique(
                ['organisation_id', 'election_id', 'determination_id'],
                'uniq_applied_determination_per_election',
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('election_applied_determinations');
    }
};
