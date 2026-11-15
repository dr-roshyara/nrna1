<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * PBDIGIT-349 step I-3 (I-TDD-02 §15, §14 cases 3 and 5, PI-45 / PI-48; INV-ID-9), CENTRAL database.
 *
 * 1. CORRECTION of I-2's schema: ended claim rows are deleted after their retention period (§15), while a binding
 *    "remains" (INV-BND-3) and a consumed authority request must keep refusing replays. I-2 made three columns foreign
 *    keys to identity_claims, which would BLOCK that deletion. They become plain provenance identifiers:
 *    identity_bindings.claim_id, identity_consumed_authority_requests.claim_id, identity_claims.replaces_claim_id.
 * 2. identity_redemption_failures: failed redemption attempts per code (code hash) and per client (client-key hash),
 *    counted in a time window. Throttling slows; it never disables a claim (D-197). No raw client key is stored.
 * 3. identity_outbox_events: "Identity's outbox" (multi-database boundary design §13.3 step 7): ClaimRedeemed /
 *    BindingRefused are committed here atomically with the redemption. The shared central `outbox_events` cannot be
 *    used: its organisation_id is a foreign key to the LEGACY `organisations` table, and registry organisations live in
 *    platform_served_organisations (D-160: the two paths never share an organisation). Same columns, no legacy key.
 *    Delivery to the organisation's inbox (a relay, idempotent on event id) is later work: it needs Organisation's
 *    consumer (S4).
 *
 * PostgreSQL only. RELEASE GATE: runs only on test databases until the Principal approves its rollout.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::statement('ALTER TABLE identity_bindings DROP CONSTRAINT IF EXISTS identity_bindings_claim_id_fkey');
        DB::statement('ALTER TABLE identity_consumed_authority_requests DROP CONSTRAINT IF EXISTS identity_consumed_authority_requests_claim_id_fkey');
        DB::statement('ALTER TABLE identity_claims DROP CONSTRAINT IF EXISTS identity_claims_replaces_claim_id_fkey');
        DB::statement(<<<'SQL'
            CREATE TABLE identity_redemption_failures (
              scope text NOT NULL CHECK (scope IN ('code', 'client')),
              key_hash char(64) NOT NULL CHECK (key_hash ~ '^[0-9a-f]{64}$'),
              window_start timestamptz(6) NOT NULL,
              failures integer NOT NULL CHECK (failures >= 1),
              PRIMARY KEY (scope, key_hash)
            )
            SQL);
        DB::statement(<<<'SQL'
            CREATE TABLE identity_outbox_events (
              id uuid PRIMARY KEY,
              event_id uuid NOT NULL UNIQUE,
              organisation_id uuid NOT NULL,
              aggregate_type text NOT NULL,
              aggregate_id text NOT NULL,
              event_type text NOT NULL,
              payload jsonb NOT NULL,
              status text NOT NULL DEFAULT 'pending' CHECK (status IN ('pending', 'processing', 'completed', 'failed')),
              attempts integer NOT NULL DEFAULT 0,
              available_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              created_at timestamptz(6) NOT NULL DEFAULT clock_timestamp(),
              processed_at timestamptz(6) NULL
            )
            SQL);
        DB::statement('CREATE INDEX identity_outbox_events_due ON identity_outbox_events (status, available_at, organisation_id)');
    }

    public function down(): void
    {
        DB::statement('DROP TABLE IF EXISTS identity_outbox_events');
        DB::statement('DROP TABLE IF EXISTS identity_redemption_failures');
        DB::statement('ALTER TABLE identity_claims ADD CONSTRAINT identity_claims_replaces_claim_id_fkey FOREIGN KEY (replaces_claim_id) REFERENCES identity_claims (claim_id)');
        DB::statement('ALTER TABLE identity_consumed_authority_requests ADD CONSTRAINT identity_consumed_authority_requests_claim_id_fkey FOREIGN KEY (claim_id) REFERENCES identity_claims (claim_id)');
        DB::statement('ALTER TABLE identity_bindings ADD CONSTRAINT identity_bindings_claim_id_fkey FOREIGN KEY (claim_id) REFERENCES identity_claims (claim_id)');
    }
};
