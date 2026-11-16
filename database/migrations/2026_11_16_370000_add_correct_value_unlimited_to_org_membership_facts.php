<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-354 S2-5b (D-237, D-242): a Correct of a Renew may set its `until` to open-ended (∞). A `correct` row keeps
 * its value in `effective_at` (a date), which cannot hold ∞.
 *
 * up():
 *   - correct_value_unlimited boolean NOT NULL DEFAULT false: true exactly on a `correct` row whose value is ∞, and then
 *     its effective_at is NULL. An explicit flag, never "NULL means ∞" (a CHECK that evaluates to NULL is satisfied;
 *     guide 25 pitfall);
 *   - the kind CHECK's `correct` branch: exactly one of a date or the flag;
 *   - org_membership_facts_correct_value_check: the flag only on `correct` rows.
 *   "Only a Renew chain may be open-ended" needs the chain, so it is enforced on load (Member::fromLog) and in decide.
 * down(): refuses while any open-ended correction exists; otherwise restores the S2-2 shape.
 *
 * PARITY (developer_guide/membership/23): the organisation set carries the same SQL in
 * app/Contexts/Membership/Infrastructure/Database/OrganisationMigrations/2026_11_16_300800_add_correct_value_unlimited_to_org_membership_facts.php.
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
              ADD COLUMN correct_value_unlimited boolean NOT NULL DEFAULT false,
              DROP CONSTRAINT org_membership_facts_kind_check,
              ADD CONSTRAINT org_membership_facts_kind_check CHECK (
                (kind = 'admit' AND effective_at IS NOT NULL AND initial_term IS NOT NULL
                    AND target_fact_id IS NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'end' AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NOT NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind IN ('annul', 'void') AND effective_at IS NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)
                OR (kind = 'correct' AND (effective_at IS NOT NULL) <> correct_value_unlimited
                    AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL
                    AND consequences IS NOT NULL AND jsonb_typeof(consequences) = 'array' AND g2_version IS NOT NULL)
                OR (kind IN ('renew', 'suspend', 'restore') AND effective_at IS NOT NULL AND initial_term IS NULL AND initial_term_until IS NULL
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL)),
              ADD CONSTRAINT org_membership_facts_correct_value_check CHECK (kind = 'correct' OR correct_value_unlimited = false)
            SQL);
    }

    public function down(): void
    {
        $rows = (int) DB::selectOne('SELECT count(*) AS n FROM org_membership_facts WHERE correct_value_unlimited')->n;
        if ($rows > 0) {
            throw new RuntimeException("S2-5b down() refused: {$rows} open-ended correction(s) exist; dropping the column would destroy history. Nothing was changed.");
        }
        DB::statement(<<<'SQL'
            ALTER TABLE org_membership_facts
              DROP CONSTRAINT org_membership_facts_correct_value_check,
              DROP CONSTRAINT org_membership_facts_kind_check,
              DROP COLUMN correct_value_unlimited,
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
                    AND target_fact_id IS NOT NULL AND end_kind IS NULL AND consequences IS NULL AND g2_version IS NULL))
            SQL);
    }
};
