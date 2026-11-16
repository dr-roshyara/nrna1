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
 * 2. identity_redemption_failures: failed redemption attempts per code (the code's SHA-256 hash) and per client (an
 *    HMAC of the client key under a server secret), counted in a time window; rows whose window has ended are deleted when the next failure is recorded.
 *    Throttling slows; it never disables a claim (D-197). No raw or plainly hashed client key is stored (review R-2).
 * 3. identity_outbox_events: "Identity's outbox" (multi-database boundary design §13.3 step 7, inside the text D-178
 *    accepted): ClaimRedeemed / BindingRefused are committed here atomically with the redemption. The shared central
 *    `outbox_events` cannot carry them: its organisation_id is a foreign key to the LEGACY `organisations` table (with
 *    cascade delete) and registry organisations live in platform_served_organisations (D-160). The columns follow the
 *    shared outbox with differences: no legacy key, no correlation_id / causation_id, aggregate_id is text, payload is
 *    jsonb, times are timestamptz(6). Delivery to the organisation's inbox (a relay, idempotent on event id) is later
 *    work: it needs Organisation's consumer (S4).
 *
 * FORWARD-ONLY (review R-3): down() refuses. Re-adding the foreign keys fails once ended claims have been cleaned up,
 * and dropping identity_outbox_events would destroy undelivered ClaimRedeemed / BindingRefused events. A rollback, if
 * ever needed, is a separate, reviewed migration that first proves no undelivered event exists.
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
        DB::statement('CREATE INDEX identity_redemption_failures_window ON identity_redemption_failures (window_start)');
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

    /** Forward-only (review R-3): changes nothing and refuses. See the header for why. */
    public function down(): void
    {
        throw new \RuntimeException(
            'Migration 2026_11_15_630000 is forward-only: rolling it back could destroy undelivered Identity events '
            . '(identity_outbox_events) and cannot re-add foreign keys after claim cleanup. Nothing was changed.'
        );
    }
};
