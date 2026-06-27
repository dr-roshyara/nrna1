<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Add Parent Structure ID Column
 *
 * Supports governance lineage tracking:
 * - Enables parent-child relationships for structure evolution
 * - Foundation for findLineageChain() queries
 * - Required for UNIQUE (parent_structure_id) WHERE status = 'DRAFT' constraint
 *
 * This migration must run BEFORE enforce_governance_epoch_integrity.php
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('committee_structures', function (Blueprint $table) {
            // Add nullable parent_structure_id column
            // Nullable because v1 structures have no parent (root of lineage)
            // Non-root structures must have a parent
            $table->string('parent_structure_id', 26)->nullable()->after('version');
        });
    }

    public function down(): void
    {
        Schema::table('committee_structures', function (Blueprint $table) {
            $table->dropColumn('parent_structure_id');
        });
    }
};
