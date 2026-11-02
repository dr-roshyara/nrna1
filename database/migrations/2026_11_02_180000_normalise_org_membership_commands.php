<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * PBDIGIT-264 F-4b (Principal Reviewer disposition 2026-10-09; requirements:
 * docs/publicdigit/reviews/2026-10-09-membership-pbdigit264-plan-ratification-pack-and-cutover-gate.md §10.2;
 * plan docs/plans/20261102-1750-membership-pbdigit264-part2-postgres-followups-plan.md §4.2).
 *
 * org_membership_commands copied fact_id and recorded_at from the fact it names, and nothing kept the copies
 * consistent. Normalised: the command row keeps (organisation_id, membership_id, seq) only. A composite FK makes
 * it reference an existing fact, and the adapter reads fact_id / recorded_at by join.
 *
 * up():   1. add the FK first (fails on any orphan row); 2. abort if any copied value disagrees with its fact;
 *         3. drop the two columns.
 * down(): re-add the columns, backfill them by join, set NOT NULL, drop the FK.
 * PostgreSQL only (DB-1). Runs inside the migrator's transaction, so an abort changes nothing.
 *
 * RELEASE GATE: before this runs on any persistent database where the 1b tables exist, the operator preflight of
 * §10.2 (orphans and mismatches, expected 0 / 0 / 0) and OQ-01 must be done. Until then it runs only on the
 * PBDIGIT-324 throwaway test cluster.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_commands
              ADD CONSTRAINT org_membership_commands_fact_fk
                FOREIGN KEY (organisation_id, membership_id, seq)
                REFERENCES org_membership_facts (organisation_id, membership_id, seq)
                ON DELETE RESTRICT
            SQL);

        $mismatches = (int) DB::selectOne(<<<'SQL'
            SELECT count(*) AS n
              FROM org_membership_commands c
              JOIN org_membership_facts f
                ON f.organisation_id = c.organisation_id AND f.membership_id = c.membership_id AND f.seq = c.seq
             WHERE c.fact_id <> f.fact_id OR c.recorded_at <> f.recorded_at
            SQL)->n;
        if ($mismatches > 0) {
            throw new RuntimeException("F-4b aborted: {$mismatches} org_membership_commands row(s) mismatch their fact (fact_id or recorded_at); nothing was changed.");
        }

        Schema::table('org_membership_commands', function (Blueprint $table) {
            $table->dropColumn(['fact_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::table('org_membership_commands', function (Blueprint $table) {
            $table->text('fact_id')->nullable();
            $table->timestampTz('recorded_at', 6)->nullable();
        });
        DB::statement(<<<'SQL'
            UPDATE org_membership_commands c
               SET fact_id = f.fact_id, recorded_at = f.recorded_at
              FROM org_membership_facts f
             WHERE f.organisation_id = c.organisation_id AND f.membership_id = c.membership_id AND f.seq = c.seq
            SQL);
        DB::statement('ALTER TABLE org_membership_commands ALTER COLUMN fact_id SET NOT NULL, ALTER COLUMN recorded_at SET NOT NULL');
        DB::statement('ALTER TABLE org_membership_commands DROP CONSTRAINT org_membership_commands_fact_fk');
    }
};
