<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-354 S2-1 (D-163; accepted design D-177 §4): org_membership_facts stores a sixth fact kind, `renew`.
 *
 * up():
 *   - renew_until_kind ('until' | 'unlimited') and renew_until (date): the end of the renewed term segment
 *     [effective_at, renew_until). Its period is target_fact_id (the Admit), as for an End;
 *   - the kind CHECK gains the `renew` branch;
 *   - a renew CHECK: the two columns are set exactly on renew rows, the kind and the date agree, and the segment is
 *     not empty.
 *   "The period is an earlier Admit of this Member" is enforced on load (Member::fromLog), as for an End.
 * down(): refuses while any renew row exists (it would destroy history); otherwise restores the previous shape.
 * PostgreSQL only. Runs inside the migrator's transaction, so an abort changes nothing.
 *
 * PARITY (developer_guide/membership/23): the organisation set carries the same change in
 * app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_15_300500_add_renew_to_org_membership_facts.php.
 *
 * RELEASE GATE: this migration joins the ledger-migration release gate. It must not run on any persistent database
 * before the migration-approval control exists, and code and migration must be deployed together (code that writes a
 * Renew needs these columns). Until then it runs only on the throwaway test cluster.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              ADD COLUMN renew_until_kind text NULL,
              ADD COLUMN renew_until date NULL,
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
                OR (kind = 'renew' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)),
              ADD CONSTRAINT org_membership_facts_renew_check CHECK (
                (kind <> 'renew' AND renew_until_kind IS NULL AND renew_until IS NULL)
                OR (kind = 'renew' AND renew_until_kind IS NOT NULL AND (
                      (renew_until_kind = 'unlimited' AND renew_until IS NULL)
                      OR (renew_until_kind = 'until' AND renew_until IS NOT NULL AND renew_until > effective_at))))
            SQL);
        // `renew_until_kind IS NOT NULL` is stated on purpose: with a NULL kind the comparisons are NULL, and a CHECK
        // that evaluates to NULL is satisfied (found by PostgresRenewTest "no term stated").
    }

    public function down(): void
    {
        $renewals = (int) DB::selectOne("SELECT count(*) AS n FROM org_membership_facts WHERE kind = 'renew'")->n;
        if ($renewals > 0) {
            throw new RuntimeException("S2-1 down() refused: {$renewals} renew fact row(s) exist; dropping their columns would destroy history. Nothing was changed.");
        }
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              DROP CONSTRAINT org_membership_facts_renew_check,
              DROP CONSTRAINT org_membership_facts_kind_check,
              DROP COLUMN renew_until,
              DROP COLUMN renew_until_kind,
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND effective_at IS NOT NULL AND initial_term IS NOT NULL
                    AND target_fact_id IS NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'end' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NOT NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind IN ('annul', 'void') AND effective_at IS NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'correct' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL
                    AND consequences IS NOT NULL AND jsonb_typeof(consequences) = 'array' AND g2_version IS NOT NULL))
            SQL);
    }
};
