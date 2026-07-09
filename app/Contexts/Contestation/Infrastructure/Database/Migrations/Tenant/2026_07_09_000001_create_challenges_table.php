<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The `challenges` table carries the FULL Challenge aggregate shape (ARB ruling: the
 * repository persists the aggregate, not the current backlog scope). The reaction slice
 * (PB-005) writes only `state` + `determination_id` + timestamps; the raise-time columns
 * are nullable and are populated by a later backlog item — no future migration change.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('challenges', function (Blueprint $table) {
            $table->string('id', 64)->primary();
            $table->string('organisation_id', 64)->index();
            $table->string('state', 20);

            // Correlation index for the resolution reaction (findByDeterminationId).
            // NOT an identity — a Challenge holds one determination once adjudicated.
            $table->string('determination_id', 128)->nullable()->index();

            // Raise-time aggregate attributes — nullable; owned by a later backlog item.
            $table->string('raiser_standing_ref', 128)->nullable();
            $table->string('contested_election_id', 128)->nullable();
            $table->string('contested_target_type', 40)->nullable();
            $table->string('contested_target_id', 128)->nullable();
            $table->text('submitted_content')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
