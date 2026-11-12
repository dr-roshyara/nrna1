<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-343 (build slice 1; plan docs/plans/20261112-1251-release-1-build-slice-1-membership-ledger-clock-and-c1-c2-plan.md
 * D-10; D-209): one ledger clock row per organisation.
 *
 * - last_recorded_at: the latest recordedAt of the organisation's facts; closed_through: the completeness point
 *   (null until the first close). Both are database-clock instants, never adjusted (D-209).
 * - No foreign key to organisations: the same migration must work in a per-organisation database, where the
 *   central registry does not exist (plan §12, slice 2).
 * - up() backfills last_recorded_at from the facts in the database it runs in (idempotent); down() drops the table.
 *   Reversible only together with the code that uses the table.
 * PostgreSQL only (DB-1). Runs inside the migrator's transaction.
 *
 * RELEASE GATE: like the other ledger migrations, it runs only on the PBDIGIT-324 throwaway test cluster until the
 * ledger-migration release gate (gate C-10) is approved by the Principal.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE org_membership_ledger_clocks (
              organisation_id uuid PRIMARY KEY,
              last_recorded_at timestamptz(6) NULL,
              closed_through timestamptz(6) NULL
            )
            SQL);
        DB::statement(<<<'SQL'
            INSERT INTO org_membership_ledger_clocks (organisation_id, last_recorded_at, closed_through)
            SELECT organisation_id, max(recorded_at), NULL
            FROM org_membership_facts
            GROUP BY organisation_id
            ON CONFLICT (organisation_id) DO NOTHING
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS org_membership_ledger_clocks');
    }
};
