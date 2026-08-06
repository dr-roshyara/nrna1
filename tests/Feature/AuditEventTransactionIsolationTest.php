<?php

namespace Tests\Feature;

use App\Models\ElectionSecurityEvent;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Protects invariant I-1 of ADR_20260806_1340_Audit_Event_Transaction_Boundary:
 *
 *   "A voter's ballot must never be lost because the audit subsystem could not
 *    record telemetry."
 *
 * The defect this guards against was found by PBDIGIT-00's end-to-end walk: the
 * audit INSERT shared the vote's connection, so a failed audit statement aborted
 * the whole PostgreSQL transaction (SQLSTATE 25P02) and the vote was discarded —
 * even though SecurityEventRecorder caught the exception. Catching an exception in
 * PHP cannot recover a transaction the database has already aborted; only writing
 * on a separate connection can.
 *
 * These tests assert the PROPERTY (an audit failure cannot abort a business
 * transaction), not the mechanism, so they remain meaningful if the ADR's
 * reversal conditions are ever met and the outbox option replaces the connection.
 */
class AuditEventTransactionIsolationTest extends TestCase
{
    /**
     * The audit model must not resolve to the connection business writes use.
     */
    public function test_audit_model_is_bound_to_an_isolated_connection(): void
    {
        $auditConnection = (new ElectionSecurityEvent())->getConnectionName();

        $this->assertSame(
            'pgsql_audit',
            $auditConnection,
            'ElectionSecurityEvent must be bound to the audit connection. Binding at the '
            . 'model rather than the call site is deliberate: there is one writer '
            . '(SecurityEventRecorder) and one reader (IpVelocityOverlay), and moving only '
            . 'the write would leave reads on the default connection — silently wrong as '
            . 'soon as DB_AUDIT_* points elsewhere.'
        );

        $this->assertNotSame(
            config('database.default'),
            $auditConnection,
            'The audit connection must differ from the default connection, otherwise audit '
            . 'writes rejoin the business transaction they are required to stay out of.'
        );
    }

    /**
     * The configured audit connection must actually be usable — a config entry that
     * no environment can open would make the isolation illusory.
     */
    public function test_audit_connection_is_configured_and_reachable(): void
    {
        $this->assertIsArray(
            config('database.connections.pgsql_audit'),
            'The pgsql_audit connection must be configured.'
        );

        $this->assertSame(
            1,
            (int) DB::connection('pgsql_audit')->selectOne('select 1 as ok')->ok,
            'The audit connection must be reachable with the ambient credentials; it '
            . 'inherits DB_* by default so no environment needs extra configuration.'
        );
    }

    /**
     * The property that matters: a failing audit write must not be able to abort a
     * business transaction that is in progress on the default connection.
     *
     * The failure is induced the same way production hit it — an INSERT that violates
     * a NOT NULL constraint — rather than by mocking, so the test exercises the real
     * PostgreSQL behaviour that made the original catch insufficient.
     */
    public function test_failing_audit_write_does_not_abort_the_business_transaction(): void
    {
        $electionId = DB::table('elections')->value('id');

        DB::beginTransaction();

        // Stand in for the business write the voter cares about.
        DB::table('organisations')->insert([
            'id' => (string) \Illuminate\Support\Str::uuid(),
            'name' => 'PBDIGIT-38 isolation probe',
            'slug' => 'pbdigit-38-isolation-probe',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Induce an audit failure: omit a required column, exactly as production did.
        try {
            ElectionSecurityEvent::create([
                'event_type' => 'trust_allowed',
                'election_id' => $electionId,
                'network_evidence' => [],
                'device_evidence' => [],
                'trust_level_before' => 'unverified',
                'trust_level_after' => 'unverified',
                'policy_evaluated' => 'isolation-probe',
                'policy_evaluation_sequence' => [],
                'trust_state_transition' => 'unverified->unverified',
                // overlay_influence_chain deliberately omitted -> NOT NULL violation.
            ]);
            $auditFailed = false;
        } catch (\Throwable $e) {
            $auditFailed = true;
        }

        $this->assertTrue(
            $auditFailed,
            'This test is only meaningful while the audit write fails. If PBDIGIT-42 has '
            . 'defined overlay_influence_chain semantics, induce the failure another way '
            . 'rather than deleting this test — the property it protects still holds.'
        );

        // The business transaction must still be usable and committable.
        DB::commit();

        $this->assertDatabaseHas('organisations', [
            'slug' => 'pbdigit-38-isolation-probe',
        ]);
    }
}
