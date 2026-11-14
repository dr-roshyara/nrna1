<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * ORGANISATION migration set (PBDIGIT-345 task 4; tactical design §3.5, M-5). Runs only on an organisation's own
 * database, through provisioning (OrganisationMigrations), never with the central `migrate`.
 *
 * organisation_identity: ONE row, the organisation this database belongs to. Written once at provisioning (task 5)
 * with the registry id; immutable afterwards. Every ledger table's organisation_id references it, so the database
 * itself refuses data of any other organisation (F-13, T-8).
 * PRODUCTION: runs only through provisioning, after the migration-approval control exists.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE organisation_identity (
              id uuid PRIMARY KEY,
              one_row boolean NOT NULL DEFAULT true,
              recorded_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              CONSTRAINT organisation_identity_one_row UNIQUE (one_row),
              CONSTRAINT organisation_identity_one_row_true CHECK (one_row)
            )
            SQL);
        DB::unprepared(<<<'SQL'
            CREATE FUNCTION organisation_identity_refuse_change() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              RAISE EXCEPTION 'organisation_identity is written once at provisioning and never changed (ORGANISATION_IDENTITY_IMMUTABLE)';
            END $$;
            CREATE TRIGGER organisation_identity_immutable BEFORE UPDATE OR DELETE ON organisation_identity
              FOR EACH ROW EXECUTE FUNCTION organisation_identity_refuse_change();
            SQL);
    }

    public function down(): void
    {
        DB::unprepared('DROP TABLE IF EXISTS organisation_identity; DROP FUNCTION IF EXISTS organisation_identity_refuse_change();');
    }
};
