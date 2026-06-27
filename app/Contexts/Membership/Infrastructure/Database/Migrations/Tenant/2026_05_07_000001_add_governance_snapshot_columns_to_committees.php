<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add governance snapshot columns to committees table.
     *
     * Phase C: Transactional Hardening — Governance Epoch Snapshot Safety
     *
     * These columns store the immutable governance snapshot captured at committee creation time.
     * Once set, these fields MUST NEVER change - they represent the temporal governance identity
     * of the committee at the moment it was created.
     *
     * Purpose:
     * - G-007: Governance epoch snapshot is transactionally stable
     * - G-008: Committee creation is atomic and captures full governance state
     *
     * Columns:
     * - structure_version: Version of governance structure at creation (temporal identity marker)
     */
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            // Only add structure_version if not already present
            if (!Schema::hasColumn('committees', 'structure_version')) {
                $table->integer('structure_version')->nullable()->after('geo_scope');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            $table->dropColumn('structure_version');
        });
    }
};
