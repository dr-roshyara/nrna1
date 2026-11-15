<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-349 step I-1 (I-TDD-02 §4 IssuedReference; P-I2 = L1, Principal 2026-11-15): the organisation PersonRef,
 * in the CENTRAL database (D-164).
 *
 * - ref: R, 32 lower-case hex, the primary key: one R names one organisation (INV-REF-1);
 * - (organisation_id, entry_ref) unique: one R per local entry (INV-REF-2);
 * - entry_ref is the organisation's own opaque identifier; nothing behind it is stored centrally (no name, no contact);
 * - never changed or deleted (trigger).
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE identity_issued_references (
              ref char(32) PRIMARY KEY CHECK (ref ~ '^[0-9a-f]{32}$'),
              organisation_id uuid NOT NULL,
              entry_ref text NOT NULL CHECK (entry_ref ~ '^\S{1,128}$'),
              issued_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              UNIQUE (organisation_id, entry_ref)
            )
            SQL);
        // OR REPLACE: migrate:fresh drops tables but not functions, so a rebuilt test database still has this one.
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION identity_issued_references_immutable() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              RAISE EXCEPTION 'identity_issued_references: a reference is never changed or deleted (I-TDD-02 INV-REF-1, INV-REF-2)';
            END
            $$
            SQL);
        DB::statement(<<<'SQL'
            CREATE TRIGGER identity_issued_references_immutable
              BEFORE UPDATE OR DELETE ON identity_issued_references
              FOR EACH ROW EXECUTE FUNCTION identity_issued_references_immutable()
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS identity_issued_references');
        DB::statement('DROP FUNCTION IF EXISTS identity_issued_references_immutable()');
    }
};
