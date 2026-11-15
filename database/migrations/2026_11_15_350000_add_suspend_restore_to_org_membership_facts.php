<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-354 S2-2 (D-163; accepted design D-177 §4): org_membership_facts stores the fact kinds `suspend` and
 * `restore`.
 *
 * up():
 *   - restriction_until_kind ('until' | 'unlimited') and restriction_until (date): the end of a suspension's
 *     restriction [effective_at, restriction_until). A suspend row's target_fact_id is its period (the Admit);
 *   - a restore row's target_fact_id is the Suspend it ends; its effective_at is the restoration date;
 *   - the kind CHECK gains both branches; a restriction CHECK: the two columns are set exactly on suspend rows, the
 *     kind and the date agree, and the restriction is not empty. `restriction_until_kind IS NOT NULL` is stated on
 *     purpose (a CHECK that evaluates to NULL is satisfied; guide 25 pitfall).
 *   "The target is an earlier Admit / Suspend of this Member" is enforced on load (Member::fromLog).
 * down(): refuses while any suspend or restore row exists; otherwise restores the S2-1 shape.
 *
 * PARITY (developer_guide/membership/23): the organisation set carries the same change in
 * app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_15_300600_add_suspend_restore_to_org_membership_facts.php.
 *
 * RELEASE GATE: as the S2-1 migration. Not on any persistent database before the migration-approval control exists;
 * code and migration are deployed together. Until then the throwaway test cluster only.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              ADD COLUMN restriction_until_kind text NULL,
              ADD COLUMN restriction_until date NULL,
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
                    AND consequences IS NOT NULL AND jsonb_typeof(consequences) = 'array' AND g2_version IS NOT NULL)
                OR (kind IN ('renew', 'suspend', 'restore') AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)),
              ADD CONSTRAINT org_membership_facts_restriction_check CHECK (
                (kind <> 'suspend' AND restriction_until_kind IS NULL AND restriction_until IS NULL)
                OR (kind = 'suspend' AND restriction_until_kind IS NOT NULL AND (
                      (restriction_until_kind = 'unlimited' AND restriction_until IS NULL)
                      OR (restriction_until_kind = 'until' AND restriction_until IS NOT NULL AND restriction_until > effective_at))))
            SQL);
    }

    public function down(): void
    {
        $rows = (int) DB::selectOne("SELECT count(*) AS n FROM org_membership_facts WHERE kind IN ('suspend', 'restore')")->n;
        if ($rows > 0) {
            throw new RuntimeException("S2-2 down() refused: {$rows} suspend / restore row(s) exist; dropping their columns would destroy history. Nothing was changed.");
        }
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              DROP CONSTRAINT org_membership_facts_restriction_check,
              DROP CONSTRAINT org_membership_facts_kind_check,
              DROP COLUMN restriction_until,
              DROP COLUMN restriction_until_kind,
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND effective_at IS NOT NULL AND initial_term IS NOT NULL
                    AND target_fact_id IS NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'end' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NOT NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind IN ('annul', 'void') AND effective_at IS NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'correct' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL
                    AND consequences IS NOT NULL AND jsonb_typeof(consequences) = 'array' AND g2_version IS NOT NULL)
                OR (kind = 'renew' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL))
            SQL);
    }
};
