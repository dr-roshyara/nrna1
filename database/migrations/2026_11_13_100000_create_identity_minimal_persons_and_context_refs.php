<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-346 G-0 (D-225 IG-1 … IG-3; D-226 Q-G-2 and §5a): Identity's minimal Person and the platform-context
 * person reference, in the CENTRAL database.
 *
 * - identity_persons: the canonical Person, only id + merged_into (I-TDD-02 INV-ID-1). The merge procedure is
 *   deferred (P-I3); nothing in G-0 writes merged_into.
 * - identity_account_persons: the transitional 1:1 mapping of a legacy users row to its Person (I-TDD-02 §18),
 *   created lazily. NOT proof that one human holds one account. No foreign key to users, so legacy user deletion
 *   is unaffected.
 * - identity_context_person_refs: one opaque reference per (Person, context), never changed or deleted (trigger).
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE identity_persons (
              id uuid PRIMARY KEY,
              merged_into uuid NULL REFERENCES identity_persons (id),
              created_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              CHECK (merged_into IS NULL OR merged_into <> id)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE identity_account_persons (
              account_id uuid PRIMARY KEY,
              person_id uuid NOT NULL UNIQUE REFERENCES identity_persons (id),
              created_at timestamptz(6) NOT NULL DEFAULT clock_timestamp()
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE identity_context_person_refs (
              ref char(32) PRIMARY KEY CHECK (ref ~ '^[0-9a-f]{32}$'),
              person_id uuid NOT NULL REFERENCES identity_persons (id),
              context text NOT NULL CHECK (context IN ('reference-geography')),
              created_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              UNIQUE (person_id, context)
            )
            SQL);
        // OR REPLACE: migrate:fresh drops tables but not functions, so a rebuilt test database still has this one.
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION identity_context_person_refs_immutable() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              RAISE EXCEPTION 'identity_context_person_refs: a reference is never changed or deleted (D-225 IG-1)';
            END
            $$
            SQL);
        DB::statement(<<<'SQL'
            CREATE TRIGGER identity_context_person_refs_immutable
              BEFORE UPDATE OR DELETE ON identity_context_person_refs
              FOR EACH ROW EXECUTE FUNCTION identity_context_person_refs_immutable()
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS identity_context_person_refs');
        DB::statement('DROP FUNCTION IF EXISTS identity_context_person_refs_immutable()');
        DB::statement('DROP TABLE IF EXISTS identity_account_persons');
        DB::statement('DROP TABLE IF EXISTS identity_persons');
    }
};
