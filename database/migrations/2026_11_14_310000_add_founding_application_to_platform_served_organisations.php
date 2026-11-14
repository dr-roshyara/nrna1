<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-345 task 5a (tactical design §3.6 step 1; D-148, D-174, D-185; plan finding F-T1-1): every registry entry
 * records its founding application: the founding evidence, the registrar and the initial platform contacts (Identity
 * account ids). Written once at registration; the repository never rewrites it.
 *
 * Existing entries cannot be given founding evidence after the fact: if the table already holds entries, this
 * migration STOPS and names how many; nothing is changed (they must be founded again, or the Principal decides).
 * CENTRAL migration. PRODUCTION: not before the migration-approval control exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        $existing = (int) DB::selectOne('SELECT count(*) AS n FROM platform_served_organisations')->n;
        if ($existing > 0) {
            throw new RuntimeException("platform_served_organisations holds {$existing} entr(y/ies) without a founding application; founding evidence is never invented. Nothing was changed.");
        }
        DB::statement(<<<'SQL'
            ALTER TABLE platform_served_organisations
              ADD COLUMN founding_evidence_ref text NOT NULL,
              ADD COLUMN registrar_account uuid NOT NULL,
              ADD COLUMN initial_contact_accounts jsonb NOT NULL,
              ADD CONSTRAINT platform_served_organisations_contacts_present
                CHECK (jsonb_typeof(initial_contact_accounts) = 'array' AND jsonb_array_length(initial_contact_accounts) >= 1)
            SQL);
    }

    public function down(): void
    {
        DB::statement('ALTER TABLE platform_served_organisations DROP CONSTRAINT IF EXISTS platform_served_organisations_contacts_present,
            DROP COLUMN IF EXISTS initial_contact_accounts, DROP COLUMN IF EXISTS registrar_account, DROP COLUMN IF EXISTS founding_evidence_ref');
    }
};
