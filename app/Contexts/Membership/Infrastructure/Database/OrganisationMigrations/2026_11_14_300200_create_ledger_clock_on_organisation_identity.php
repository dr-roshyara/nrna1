<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-345 task 5b (ORGANISATION migration set; tactical design §3.6 step 3, §3.7 L-1): when an organisation
 * database is bound to its organisation (its `organisation_identity` row is inserted), Membership's ledger clock row
 * for that organisation is created with it. Membership owns this behaviour; Platform imports no Membership code.
 * The row is the same one LedgerClockRow::ensure would create (it then finds it present).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE FUNCTION org_membership_ledger_clock_on_identity() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              INSERT INTO org_membership_ledger_clocks (organisation_id) VALUES (NEW.id) ON CONFLICT (organisation_id) DO NOTHING;
              RETURN NEW;
            END $$
            SQL);
        DB::statement('CREATE TRIGGER org_membership_ledger_clock_on_identity AFTER INSERT ON organisation_identity
            FOR EACH ROW EXECUTE FUNCTION org_membership_ledger_clock_on_identity()');
    }

    public function down(): void
    {
        DB::statement('DROP TRIGGER IF EXISTS org_membership_ledger_clock_on_identity ON organisation_identity');
        DB::statement('DROP FUNCTION IF EXISTS org_membership_ledger_clock_on_identity()');
    }
};
