<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-354 S2-5b (ORGANISATION migration set; D-237, D-242): `correct_value_unlimited`, so a Correct of a Renew can
 * store its `until` as open-ended. The same SQL as the central migration
 * database/migrations/2026_11_16_370000_add_correct_value_unlimited_to_org_membership_facts.php (parity rule,
 * developer_guide/membership/23; compared by OrganisationMigrationsTest).
 *
 * Applied only by the provisioning process (PBDIGIT-345): to a new organisation database when it is prepared, and to
 * an existing one through `platform:operate <organisation> migration-repair`. Never by the central `php artisan
 * migrate`. No operational organisation exists yet (D-235, D-236): development and test only.
 *
 * down(): refuses while any open-ended correction exists.
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
