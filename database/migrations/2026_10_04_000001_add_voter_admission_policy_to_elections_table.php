<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * PBDIGIT-213 C2 — the election-owned Voter Admission Policy.
 *
 * Default {"requires":[]} = no additional admission requirement (C2-D1: OFF for
 * every existing and new election). Additive only; existing behaviour unchanged.
 * Changes are locked from ReadyForVoting onward (Election model guard + handler).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->json('voter_admission_policy')->default('{"requires":[]}');
        });
    }

    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('voter_admission_policy');
        });
    }
};
