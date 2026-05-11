<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Enforce Governance Epoch Integrity
 *
 * Implements Phase A2 temporal invariants:
 * - G-002: Unique versioning per tenant
 * - G-003: Single draft successor per parent
 * - Indexes for temporal query performance
 *
 * CRITICAL: Run only after ensuring no existing data violates these constraints.
 * Use: php artisan governance:verify-invariants --all
 */
return new class extends Migration
{
    public function up(): void
    {
        // Invariant G-002: Unique version per tenant
        // Prevents duplicate versions within same tenant's lineage
        // Example violation prevented: Tenant A has v1, v2, v2 (duplicate)
        DB::statement('
            CREATE UNIQUE INDEX uniq_org_version
            ON committee_structures(organisation_id, version)
        ');

        // Invariant G-003: Single draft successor per parent
        // Prevents governance branching where one structure has multiple DRAFT children
        // Example violation prevented:
        //   v2 ACTIVE
        //    ├── v3 DRAFT (created Monday)
        //    └── v3b DRAFT (created Tuesday)
        DB::statement('
            CREATE UNIQUE INDEX uniq_draft_successor
            ON committee_structures(parent_structure_id)
            WHERE status = \'draft\'
        ');

        // Index for lineage queries (G-003 enabler)
        // Enables: findLineageChain(), findDraftSuccessorOf()
        DB::statement('
            CREATE INDEX idx_parent_structure
            ON committee_structures(parent_structure_id)
        ');
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS uniq_org_version');
        DB::statement('DROP INDEX IF EXISTS uniq_draft_successor');
        DB::statement('DROP INDEX IF EXISTS idx_parent_structure');
    }
};
