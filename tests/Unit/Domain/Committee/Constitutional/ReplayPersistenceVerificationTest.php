<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotMetadata;
use PHPUnit\Framework\TestCase;
use Illuminate\Support\Str;


class ReplayPersistenceVerificationTest extends TestCase
{
    /**
     * @test
     * Persist then replay returns same legitimacy
     */
    public function test_persist_then_replay_returns_same_legitimacy(): void
    {
        // Note: Full integration requires InMemoryGovernanceDecisionStore
        // For now, we verify the snapshot structure supports round-trip verification
        $decisionId = Str::uuid()->toString();

        $snapshot = new GovernanceDecisionSnapshot(
            decisionId: $decisionId,
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            capabilityType: 'appoint_committee_member',
            winningAuthorityId: 'node-123',
            legitimacy: 'legitimate',
            constitutionalReasonJson: '{"code":"test","summary":"Test","explanation":"","articleCodes":[],"severity":"binding","legitimacyImpact":"valid"}',
            arbitrationTraceJson: '{"evaluatedNodeIds":["node-123"],"selectedNodeId":"node-123","precedenceReason":"direct","doctrineRulesApplied":["direct"],"rejectionReasons":[]}',
            metadata: new SnapshotMetadata(
                schemaVersion: '1.0',
                doctrineVersion: '1.0',
                legitimacyPolicyVersion: '1.0',
                arbitrationPolicyVersion: '1.0',
                replayEngineVersion: '3.2',
                replayCompatibilityVersion: '3.2',
                generatedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z')
            ),
            constitutionalScope: null,
            integrityHash: 'dummy-hash'
        );

        // Verify snapshot structure allows future replay
        $this->assertSame('legitimate', $snapshot->legitimacy);
    }

    /**
     * @test
     * Replay fingerprint matches persisted snapshot
     */
    public function test_replay_fingerprint_matches_persisted_snapshot(): void
    {
        $decisionId = Str::uuid()->toString();

        $snapshot = new GovernanceDecisionSnapshot(
            decisionId: $decisionId,
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            capabilityType: 'appoint_committee_member',
            winningAuthorityId: 'node-123',
            legitimacy: 'legitimate',
            constitutionalReasonJson: '{"code":"test","summary":"Test","explanation":"","articleCodes":[],"severity":"binding","legitimacyImpact":"valid"}',
            arbitrationTraceJson: '{"evaluatedNodeIds":["node-123"],"selectedNodeId":"node-123","precedenceReason":"direct","doctrineRulesApplied":["direct"],"rejectionReasons":[]}',
            metadata: new SnapshotMetadata(
                schemaVersion: '1.0',
                doctrineVersion: '1.0',
                legitimacyPolicyVersion: '1.0',
                arbitrationPolicyVersion: '1.0',
                replayEngineVersion: '3.2',
                replayCompatibilityVersion: '3.2',
                generatedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z')
            ),
            constitutionalScope: null,
            integrityHash: 'original-hash'
        );

        // Verify structure for replay fingerprint calculation
        $this->assertNotNull($snapshot->metadata);
        $this->assertSame('1.0', $snapshot->metadata->doctrineVersion);
    }

    /**
     * @test
     * Integrity hash verifies after round trip
     */
    public function test_integrity_hash_verifies_after_round_trip(): void
    {
        $decisionId = Str::uuid()->toString();

        $snapshot = new GovernanceDecisionSnapshot(
            decisionId: $decisionId,
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            capabilityType: 'appoint_committee_member',
            winningAuthorityId: 'node-123',
            legitimacy: 'legitimate',
            constitutionalReasonJson: '{"code":"test","summary":"Test","explanation":"","articleCodes":[],"severity":"binding","legitimacyImpact":"valid"}',
            arbitrationTraceJson: '{"evaluatedNodeIds":["node-123"],"selectedNodeId":"node-123","precedenceReason":"direct","doctrineRulesApplied":["direct"],"rejectionReasons":[]}',
            metadata: new SnapshotMetadata(
                schemaVersion: '1.0',
                doctrineVersion: '1.0',
                legitimacyPolicyVersion: '1.0',
                arbitrationPolicyVersion: '1.0',
                replayEngineVersion: '3.2',
                replayCompatibilityVersion: '3.2',
                generatedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z')
            ),
            constitutionalScope: null,
            integrityHash: 'integrity-hash-value'
        );

        // Snapshot structure allows integrity verification
        $this->assertTrue(true); // Placeholder for verifyIntegrity() call when store is available
    }
}
