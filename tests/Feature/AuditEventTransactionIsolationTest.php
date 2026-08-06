<?php

namespace Tests\Feature;

use App\Models\ElectionSecurityEvent;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Invariant I-1: a voter's ballot must never be lost because the audit subsystem
 * could not record telemetry.
 *
 * Asserts the property — an audit failure cannot abort a business transaction —
 * rather than the mechanism, so it survives a change of mechanism.
 *
 * ADR: docs/publicdigit/adr/ADR_20260806_1340_Audit_Event_Transaction_Boundary.md
 */
class AuditEventTransactionIsolationTest extends TestCase
{
    /** The audit model must not resolve to the connection business writes use. */
    public function test_audit_model_is_bound_to_an_isolated_connection(): void
    {
        $auditConnection = (new ElectionSecurityEvent())->getConnectionName();

        $this->assertSame(
            'pgsql_audit',
            $auditConnection,
            'ElectionSecurityEvent must be bound to the audit connection, at the model so '
            . 'that readers and writers stay together.'
        );

        $this->assertNotSame(
            config('database.default'),
            $auditConnection,
            'The audit connection must differ from the default, or audit writes rejoin the '
            . 'business transaction.'
        );
    }

    /** A connection no environment can open would make the isolation illusory. */
    public function test_audit_connection_is_configured_and_reachable(): void
    {
        $this->assertIsArray(
            config('database.connections.pgsql_audit'),
            'The pgsql_audit connection must be configured.'
        );

        $this->assertSame(
            1,
            (int) DB::connection('pgsql_audit')->selectOne('select 1 as ok')->ok,
            'The audit connection must be reachable; it inherits DB_* by default.'
        );
    }

    /**
     * The property that matters. The failure is induced with a real constraint
     * violation rather than a mock, so the database's own behaviour is exercised.
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

        // Induce an audit failure by omitting a required column.
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
            'Only meaningful while this write fails. If PBDIGIT-42 defines '
            . 'overlay_influence_chain, induce the failure another way rather than '
            . 'deleting this test.'
        );

        // The business transaction must still be usable and committable.
        DB::commit();

        $this->assertDatabaseHas('organisations', [
            'slug' => 'pbdigit-38-isolation-probe',
        ]);
    }
}
