<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-345 task 5c (ORGANISATION migration set; tactical design §3.7 L-1): Membership answers "is my ledger ready
 * for this organisation?" itself, as two booleans: its ledger tables exist, and the organisation's ledger clock row
 * exists. Platform's founding step 5 calls this function and never names or reads a ledger table (R5 boundary,
 * MembershipLedgerIsolationTest). No ledger data leaves Membership through it.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE FUNCTION org_membership_ledger_readiness(p_organisation uuid, OUT schema_present boolean, OUT clock_row_present boolean)
            LANGUAGE plpgsql STABLE AS $$
            BEGIN
              schema_present := to_regclass('public.org_memberships') IS NOT NULL AND to_regclass('public.org_membership_facts') IS NOT NULL
                AND to_regclass('public.org_membership_commands') IS NOT NULL AND to_regclass('public.org_membership_ledger_clocks') IS NOT NULL;
              clock_row_present := false;
              IF to_regclass('public.org_membership_ledger_clocks') IS NOT NULL THEN
                EXECUTE 'SELECT EXISTS (SELECT 1 FROM org_membership_ledger_clocks WHERE organisation_id = $1)' INTO clock_row_present USING p_organisation;
              END IF;
            END $$
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP FUNCTION IF EXISTS org_membership_ledger_readiness(uuid)');
    }
};
