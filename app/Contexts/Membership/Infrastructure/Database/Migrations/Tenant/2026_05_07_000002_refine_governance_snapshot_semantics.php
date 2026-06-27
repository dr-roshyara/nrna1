<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Phase C.1: Refine Governance Snapshot Persistence Semantics
     *
     * This migration makes temporal governance semantics explicit and durable.
     *
     * Renaming:
     * - structure_id → created_from_structure_id (clarity: immutable historical reference)
     * - level_index → snapshot_level_index (clarity: temporal snapshot, not operational)
     * - level_name → snapshot_level_name (clarity: temporal snapshot, not operational)
     * - geo_policy → snapshot_geo_policy (clarity: temporal snapshot, not operational)
     * - geo_scope → snapshot_geo_scope (clarity: temporal snapshot, not operational)
     * - structure_version → snapshot_structure_version (clarity: temporal identity)
     *
     * Adding:
     * - snapshot_taken_at (timestamp of governance snapshot capture)
     * - Indexes for archaeology and temporal queries
     * - Schema comments documenting immutability
     *
     * CRITICAL: Snapshot fields are WRITE-ONCE and IMMUTABLE after creation.
     * They represent institutional historical facts, not operational state.
     *
     * ADR: Governance snapshots intentionally avoid foreign key enforcement to preserve
     * archaeological survivability. Archived structures may be cleaned up independently
     * while committees retain their historical snapshot references.
     */
    public function up(): void
    {
        Schema::table('committees', function (Blueprint $table) {
            // Rename existing snapshot columns to semantic names
            if (Schema::hasColumn('committees', 'structure_id')) {
                $table->renameColumn('structure_id', 'created_from_structure_id');
            }

            if (Schema::hasColumn('committees', 'level_index')) {
                $table->renameColumn('level_index', 'snapshot_level_index');
            }

            if (Schema::hasColumn('committees', 'level_name')) {
                $table->renameColumn('level_name', 'snapshot_level_name');
            }

            if (Schema::hasColumn('committees', 'geo_policy')) {
                $table->renameColumn('geo_policy', 'snapshot_geo_policy');
            }

            if (Schema::hasColumn('committees', 'geo_scope')) {
                $table->renameColumn('geo_scope', 'snapshot_geo_scope');
            }

            if (Schema::hasColumn('committees', 'structure_version')) {
                $table->renameColumn('structure_version', 'snapshot_structure_version');
            }

            // Add snapshot timestamp if not already present
            if (!Schema::hasColumn('committees', 'snapshot_taken_at')) {
                $table->timestamp('snapshot_taken_at')
                    ->nullable()
                    ->after('snapshot_structure_version')
                    ->comment('Exact moment the governance snapshot was captured (transaction commit time)');
            }

            // Add snapshot_level_code if not already present (stable temporal identity)
            if (!Schema::hasColumn('committees', 'snapshot_level_code')) {
                $table->string('snapshot_level_code', 100)
                    ->nullable()
                    ->after('snapshot_level_name')
                    ->comment('Immutable level code from governance structure at creation time');
            }
        });

        // Add schema comments to snapshot fields for architectural clarity
        if (DB::getDriverName() === 'pgsql') {
            DB::statement("COMMENT ON COLUMN committees.created_from_structure_id IS 'Immutable governance structure that created this committee - NO FOREIGN KEY by design (archaeological survivability)'");
            DB::statement("COMMENT ON COLUMN committees.snapshot_structure_version IS 'Version number of governance structure at committee creation - immutable temporal identity'");
            DB::statement("COMMENT ON COLUMN committees.snapshot_level_index IS 'Level index in governance structure at creation - immutable'");
            DB::statement("COMMENT ON COLUMN committees.snapshot_level_code IS 'Stable governance level code at creation - canonical identity'");
            DB::statement("COMMENT ON COLUMN committees.snapshot_level_name IS 'Display name of level at creation - immutable snapshot'");
            DB::statement("COMMENT ON COLUMN committees.snapshot_geo_policy IS 'Geographic policy requirement at creation - immutable'");
            DB::statement("COMMENT ON COLUMN committees.snapshot_geo_scope IS 'Geographic scope at creation - immutable'");
        }

        // Add indexes for archaeology and temporal queries
        Schema::table('committees', function (Blueprint $table) {
            // Single column indexes for common archaeology queries
            if (!Schema::hasIndex('committees', 'idx_created_from_structure_id')) {
                $table->index('created_from_structure_id', 'idx_created_from_structure_id');
            }

            if (!Schema::hasIndex('committees', 'idx_snapshot_structure_version')) {
                $table->index('snapshot_structure_version', 'idx_snapshot_structure_version');
            }

            // Compound index for lineage reconstruction
            if (!Schema::hasIndex('committees', 'idx_snapshot_lineage')) {
                $table->index(['created_from_structure_id', 'snapshot_structure_version'], 'idx_snapshot_lineage');
            }

            // Index for temporal queries
            if (!Schema::hasIndex('committees', 'idx_snapshot_temporal')) {
                $table->index(['organisation_id', 'snapshot_taken_at'], 'idx_snapshot_temporal');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes first
        Schema::table('committees', function (Blueprint $table) {
            $table->dropIndexIfExists('idx_created_from_structure_id');
            $table->dropIndexIfExists('idx_snapshot_structure_version');
            $table->dropIndexIfExists('idx_snapshot_lineage');
            $table->dropIndexIfExists('idx_snapshot_temporal');
        });

        // Revert column renames
        Schema::table('committees', function (Blueprint $table) {
            if (Schema::hasColumn('committees', 'created_from_structure_id')) {
                $table->renameColumn('created_from_structure_id', 'structure_id');
            }

            if (Schema::hasColumn('committees', 'snapshot_level_index')) {
                $table->renameColumn('snapshot_level_index', 'level_index');
            }

            if (Schema::hasColumn('committees', 'snapshot_level_name')) {
                $table->renameColumn('snapshot_level_name', 'level_name');
            }

            if (Schema::hasColumn('committees', 'snapshot_geo_policy')) {
                $table->renameColumn('snapshot_geo_policy', 'geo_policy');
            }

            if (Schema::hasColumn('committees', 'snapshot_geo_scope')) {
                $table->renameColumn('snapshot_geo_scope', 'geo_scope');
            }

            if (Schema::hasColumn('committees', 'snapshot_structure_version')) {
                $table->renameColumn('snapshot_structure_version', 'structure_version');
            }

            if (Schema::hasColumn('committees', 'snapshot_taken_at')) {
                $table->dropColumn('snapshot_taken_at');
            }

            if (Schema::hasColumn('committees', 'snapshot_level_code')) {
                $table->dropColumn('snapshot_level_code');
            }
        });
    }
};
