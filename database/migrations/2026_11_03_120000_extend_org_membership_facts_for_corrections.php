<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * COR-4 (PBDIGIT-329; plan docs/plans/20261103-0440-membership-cor4-correction-persistence-tdd-plan.md §4, Q-1, Q-4,
 * Q-5; correction contract §8): org_membership_facts stores the five fact kinds.
 *
 * up():
 *   - Q-1 (D-COR-4): period_fact_id → target_fact_id ("the fact this fact refers to"), with its same-Member FK;
 *   - Q-4: effective_at nullable (Annul / Void carry no valid time; a Correct's value is stored there);
 *   - Q-5: consequences jsonb + g2_version smallint, on the Correct's own row (FP §12.4 option C);
 *   - one kind CHECK with five branches, a g2_version CHECK and a no-self-target CHECK.
 *   "Target is an earlier fact" is enforced on load (Member::fromLog, Q-3), not here.
 * down(): refuses while any annul / void / correct row exists (it would destroy history); otherwise restores the
 *   Slice 1b shape exactly.
 * PostgreSQL only (DB-1). Runs inside the migrator's transaction, so an abort changes nothing.
 *
 * RELEASE GATE: this migration joins the ledger-migration release gate. It must not run on any persistent database
 * before OQ-01, the §10.2 operator preflight, the lc_messages check and ARB-MEM-01 (COR-1…COR-4 accepted), and
 * code and migration must be deployed together: after the column rename, code that reads period_fact_id breaks
 * (COR-4 review condition C-3). Until then it runs only on the PBDIGIT-324 throwaway test cluster.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE org_membership_facts RENAME COLUMN period_fact_id TO target_fact_id');
        DB::statement('ALTER TABLE org_membership_facts RENAME CONSTRAINT org_membership_facts_period_fk TO org_membership_facts_target_fk');
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              ALTER COLUMN effective_at DROP NOT NULL,
              ADD COLUMN consequences jsonb NULL,
              ADD COLUMN g2_version smallint NULL,
              DROP CONSTRAINT org_membership_facts_kind_check,
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND effective_at IS NOT NULL AND initial_term IS NOT NULL
                    AND target_fact_id IS NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'end' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NOT NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind IN ('annul', 'void') AND effective_at IS NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'correct' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL
                    AND consequences IS NOT NULL AND jsonb_typeof(consequences) = 'array' AND g2_version IS NOT NULL)),
              ADD CONSTRAINT org_membership_facts_g2_version_check CHECK (g2_version IS NULL OR g2_version >= 1),
              ADD CONSTRAINT org_membership_facts_target_not_self_check CHECK (target_fact_id IS NULL OR target_fact_id <> fact_id)
            SQL);
    }

    public function down(): void
    {
        $corrections = (int) DB::selectOne("SELECT count(*) AS n FROM org_membership_facts WHERE kind IN ('annul', 'void', 'correct')")->n;
        if ($corrections > 0) {
            throw new RuntimeException("COR-4 down() refused: {$corrections} correction fact row(s) (annul / void / correct) exist; dropping their columns would destroy history. Nothing was changed.");
        }
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              DROP CONSTRAINT org_membership_facts_target_not_self_check,
              DROP CONSTRAINT org_membership_facts_g2_version_check,
              DROP CONSTRAINT org_membership_facts_kind_check,
              DROP COLUMN g2_version,
              DROP COLUMN consequences,
              ALTER COLUMN effective_at SET NOT NULL
            SQL);
        DB::statement('ALTER TABLE org_membership_facts RENAME CONSTRAINT org_membership_facts_target_fk TO org_membership_facts_period_fk');
        DB::statement('ALTER TABLE org_membership_facts RENAME COLUMN target_fact_id TO period_fact_id');
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND initial_term IS NOT NULL AND period_fact_id IS NULL AND end_kind IS NULL)
                OR (kind = 'end' AND initial_term IS NULL AND initial_term_until IS NULL
                    AND period_fact_id IS NOT NULL AND end_kind IS NOT NULL))
            SQL);
    }
};
