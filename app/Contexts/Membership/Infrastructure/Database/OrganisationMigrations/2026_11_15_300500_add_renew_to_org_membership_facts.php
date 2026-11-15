<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-354 S2-1 (ORGANISATION migration set; D-163; accepted design D-177 §4): org_membership_facts stores the
 * fact kind `renew`. The same change as the central migration
 * database/migrations/2026_11_15_340000_add_renew_to_org_membership_facts.php (parity rule,
 * developer_guide/membership/23; compared by OrganisationMigrationsTest).
 *
 * Applied only by the provisioning process (PBDIGIT-345): to a new organisation database when it is prepared, and
 * to an existing one through `platform:operate <organisation> migration-repair`. Never by the central
 * `php artisan migrate`. No operational organisation exists yet (D-235, D-236): development and test only.
 *
 * down(): refuses while any renew row exists.
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
