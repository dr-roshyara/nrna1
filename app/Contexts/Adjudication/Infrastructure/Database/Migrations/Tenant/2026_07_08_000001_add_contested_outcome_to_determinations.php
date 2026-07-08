<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * ADR-UL-01 / ADR-PL-01 — payload schema version 2: the Determination write row
 * additively stores the contested-outcome reference (a Result/Determination the
 * ruling is about, election-scoped). Nullable: rows written before schema v2
 * have none. No vote content (anonymity, ADR-T11).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('determinations', function (Blueprint $table) {
            $table->string('contested_election_id', 64)->nullable()->after('jurisdiction');
            $table->string('contested_target_type', 32)->nullable()->after('contested_election_id');
            $table->string('contested_target_id', 128)->nullable()->after('contested_target_type');
        });
    }

    public function down(): void
    {
        Schema::table('determinations', function (Blueprint $table) {
            $table->dropColumn(['contested_election_id', 'contested_target_type', 'contested_target_id']);
        });
    }
};
