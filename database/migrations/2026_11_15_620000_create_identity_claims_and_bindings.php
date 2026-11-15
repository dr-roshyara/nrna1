<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-349 step I-2 (I-TDD-02 §4, §5, §15): ordinary claims, in the CENTRAL database (D-164).
 *
 * - identity_claim_slots: one row per (O, R), no data; serialises every claim operation for R (§6.2);
 * - identity_claims: code HASH only (INV-ID-7); expiry time-derived (active = issued ∧ t_check < expires_at); window
 *   with its source and cap (D-199); one per (O, issuance id) (PI-12); `kind` is 'ordinary' only (bootstrap is I-6);
 * - identity_consumed_authority_requests: the only central trace of a Governance answer: its request id, made single
 *   use by the primary key (§10.1, PI-21);
 * - identity_bindings: "R of O is Person P" (INV-BND-1 / -2). CREATED here because issuance must check, under the
 *   slot lock, that R is unbound (INV-ID-6); WRITTEN only by redemption (I-3). Never changed or deleted (INV-BND-3);
 * - identity_claim_audit: content-free events (no code, no address, no answer content).
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement(<<<'SQL'
            CREATE TABLE identity_claim_slots (
              organisation_id uuid NOT NULL,
              person_ref char(32) NOT NULL REFERENCES identity_issued_references (ref),
              PRIMARY KEY (organisation_id, person_ref)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE identity_claims (
              claim_id uuid PRIMARY KEY,
              organisation_id uuid NOT NULL,
              person_ref char(32) NOT NULL REFERENCES identity_issued_references (ref),
              kind text NOT NULL CHECK (kind IN ('ordinary')),
              code_hash char(64) NOT NULL UNIQUE CHECK (code_hash ~ '^[0-9a-f]{64}$'),
              issued_at timestamptz(6) NOT NULL,
              expires_at timestamptz(6) NOT NULL CHECK (expires_at > issued_at),
              window_days integer NOT NULL CHECK (window_days BETWEEN 1 AND 30),
              window_source text NOT NULL CHECK (window_source IN ('rules', 'platform-default')),
              window_capped boolean NOT NULL,
              state text NOT NULL CHECK (state IN ('issued', 'redeemed', 'revoked')),
              issuance_id text NOT NULL CHECK (issuance_id ~ '^\S{1,128}$'),
              authority_request_id text NOT NULL,
              replaces_claim_id uuid NULL REFERENCES identity_claims (claim_id),
              ended_at timestamptz(6) NULL,
              UNIQUE (organisation_id, issuance_id),
              CHECK ((state = 'issued') = (ended_at IS NULL))
            )
            SQL);
        DB::statement('CREATE INDEX identity_claims_by_ref ON identity_claims (organisation_id, person_ref)');
        DB::statement(<<<'SQL'
            CREATE TABLE identity_consumed_authority_requests (
              request_id text PRIMARY KEY,
              organisation_id uuid NOT NULL,
              act_kind text NOT NULL,
              claim_id uuid NOT NULL REFERENCES identity_claims (claim_id),
              consumed_at timestamptz(6) NOT NULL DEFAULT clock_timestamp()
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE identity_bindings (
              organisation_id uuid NOT NULL,
              person_ref char(32) NOT NULL REFERENCES identity_issued_references (ref),
              person_id uuid NOT NULL REFERENCES identity_persons (id),
              bound_at timestamptz(6) NOT NULL,
              claim_id uuid NOT NULL REFERENCES identity_claims (claim_id),
              PRIMARY KEY (organisation_id, person_ref),
              UNIQUE (organisation_id, person_id)
            )
            SQL);
        // OR REPLACE: migrate:fresh drops tables but not functions.
        DB::statement(<<<'SQL'
            CREATE OR REPLACE FUNCTION identity_bindings_immutable() RETURNS trigger LANGUAGE plpgsql AS $$
            BEGIN
              RAISE EXCEPTION 'identity_bindings: a binding is never edited; it ends only by Identity''s explicit acts (I-TDD-02 INV-BND-3)';
            END
            $$
            SQL);
        DB::statement(<<<'SQL'
            CREATE TRIGGER identity_bindings_immutable
              BEFORE UPDATE OR DELETE ON identity_bindings
              FOR EACH ROW EXECUTE FUNCTION identity_bindings_immutable()
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE identity_claim_audit (
              id bigserial PRIMARY KEY,
              event text NOT NULL,
              organisation_id uuid NOT NULL,
              claim_id uuid NULL,
              reason text NULL,
              recorded_at timestamptz(6) NOT NULL DEFAULT clock_timestamp()
            )
            SQL);
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS identity_claim_audit');
        DB::statement('DROP TABLE IF EXISTS identity_bindings');
        DB::statement('DROP FUNCTION IF EXISTS identity_bindings_immutable()');
        DB::statement('DROP TABLE IF EXISTS identity_consumed_authority_requests');
        DB::statement('DROP TABLE IF EXISTS identity_claims');
        DB::statement('DROP TABLE IF EXISTS identity_claim_slots');
    }
};
