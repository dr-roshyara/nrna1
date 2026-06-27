<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Snapshot;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotIdentity;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotDecisionData;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotTraceData;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\SnapshotIntegrityData;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class SnapshotDecompositionTest extends TestCase
{
    private function makeSnapshot(): GovernanceDecisionSnapshot
    {
        $gov = new GovernanceDecision(
            id: GovernanceDecisionId::generate(),
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            evaluation: CapabilityEvaluation::allow('test'),
            classification: new AuthorityClassification(
                exceptions: [],
                overrides: [],
                direct: new JurisdictionNode('node-1', 'national', null, true, false),
                delegated: [],
            ),
            detectedConflicts: AuthorityConflictCollection::empty(),
            precedenceRanking: AuthorityPrecedenceRanking::empty(),
            producedEvent: new GovernanceDecisionProduced(
                GovernanceDecisionId::generate()->toString(),
                'tenant-1',
                CapabilityType::COMMITTEE_CREATION->value,
                'direct',
                'node-1',
            ),
        );

        $constitutional = new ConstitutionalDecision(
            winner: $gov->classification->direct,
            legitimacy: GovernanceLegitimacy::LEGITIMATE,
            reason: ConstitutionalReason::resolved('direct', 'Direct authority'),
            evaluatedAt: $gov->decidedAt,
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: ['node-1'],
                selectedNodeId: 'node-1',
                precedenceReason: 'direct',
                doctrineRulesApplied: ['direct'],
            ),
        );

        $decision = new ConstitutionalGovernanceDecision($gov, $constitutional);

        return GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            new \DateTimeImmutable('2026-05-08T11:00:00Z'),
        );
    }

    public function test_identity_groups_correct_fields(): void
    {
        $snapshot = $this->makeSnapshot();
        $identity = $snapshot->identity();

        $this->assertInstanceOf(SnapshotIdentity::class, $identity);
        $this->assertSame($snapshot->decisionId, $identity->decisionId);
        $this->assertSame($snapshot->capabilityType, $identity->capabilityType);
        $this->assertSame($snapshot->constitutionalScope, $identity->constitutionalScope);
    }

    public function test_decision_data_groups_correct_fields(): void
    {
        $snapshot = $this->makeSnapshot();
        $decisionData = $snapshot->decisionData();

        $this->assertInstanceOf(SnapshotDecisionData::class, $decisionData);
        $this->assertSame($snapshot->winningAuthorityId, $decisionData->winningAuthorityId);
        $this->assertSame($snapshot->legitimacy, $decisionData->legitimacy);
        $this->assertSame($snapshot->constitutionalReasonJson, $decisionData->constitutionalReasonJson);
    }

    public function test_trace_data_groups_correct_fields(): void
    {
        $snapshot = $this->makeSnapshot();
        $traceData = $snapshot->traceData();

        $this->assertInstanceOf(SnapshotTraceData::class, $traceData);
        $this->assertSame($snapshot->arbitrationTraceJson, $traceData->arbitrationTraceJson);
        $this->assertSame($snapshot->metadata->doctrineVersion, $traceData->doctrineVersion);
        $this->assertSame($snapshot->metadata->arbitrationPolicyVersion, $traceData->arbitrationPolicyVersion);
    }

    public function test_integrity_data_groups_correct_fields(): void
    {
        $snapshot = $this->makeSnapshot();
        $integrityData = $snapshot->integrityData();

        $this->assertInstanceOf(SnapshotIntegrityData::class, $integrityData);
        $this->assertSame($snapshot->integrityHash, $integrityData->integrityHash);
        $this->assertSame($snapshot->metadata->schemaVersion, $integrityData->schemaVersion);
        $this->assertSame($snapshot->metadata->replayEngineVersion, $integrityData->replayEngineVersion);
        $this->assertSame($snapshot->metadata->replayCompatibilityVersion, $integrityData->replayCompatibilityVersion);
    }
}
