<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceReplayService;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionStore;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceClock;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceArchaeologyRecord;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionNotFoundException;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotIntegrityViolationException;
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

final class GovernanceReplayServiceTest extends TestCase
{
    private function makeConstitutionalGovernanceDecision(): ConstitutionalGovernanceDecision
    {
        $governanceDecision = new GovernanceDecision(
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

        $constitutionalDecision = new ConstitutionalDecision(
            winner: $governanceDecision->classification->direct,
            legitimacy: GovernanceLegitimacy::LEGITIMATE,
            reason: ConstitutionalReason::resolved('direct', 'Direct authority'),
            evaluatedAt: $governanceDecision->decidedAt,
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: ['node-1'],
                selectedNodeId: 'node-1',
                precedenceReason: 'direct',
                doctrineRulesApplied: ['direct'],
            ),
        );

        return new ConstitutionalGovernanceDecision(
            $governanceDecision,
            $constitutionalDecision,
        );
    }

    public function test_persist_stores_snapshot_and_returns_it(): void
    {
        $store = $this->createMock(GovernanceDecisionStore::class);
        $store->expects($this->once())
            ->method('store')
            ->with($this->isInstanceOf(GovernanceDecisionSnapshot::class));

        $clock = $this->createMock(GovernanceClock::class);
        $clock->method('now')->willReturn(new \DateTimeImmutable('2026-05-08T11:00:00Z'));

        $service = new GovernanceReplayService($store, $clock);

        $decision = $this->makeConstitutionalGovernanceDecision();

        $snapshot = $service->persist($decision, CapabilityType::COMMITTEE_CREATION);

        $this->assertInstanceOf(GovernanceDecisionSnapshot::class, $snapshot);
        $this->assertTrue($snapshot->verifyIntegrity());
    }

    public function test_replay_returns_archaeology_record_for_known_decision(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $store = $this->createMock(GovernanceDecisionStore::class);
        $store->method('findById')->willReturn($snapshot);

        $clock = $this->createMock(GovernanceClock::class);
        $clock->method('now')->willReturn(new \DateTimeImmutable('2026-05-08T15:00:00Z'));

        $service = new GovernanceReplayService($store, $clock);

        $record = $service->replay($decision->id());

        $this->assertInstanceOf(GovernanceArchaeologyRecord::class, $record);
        $this->assertSame($snapshot->decisionId, $record->decisionId());
    }

    public function test_replay_throws_governance_decision_not_found(): void
    {
        $store = $this->createMock(GovernanceDecisionStore::class);
        $store->method('findById')->willReturn(null);

        $clock = $this->createMock(GovernanceClock::class);

        $service = new GovernanceReplayService($store, $clock);

        $decisionId = GovernanceDecisionId::generate();

        $this->expectException(GovernanceDecisionNotFoundException::class);
        $service->replay($decisionId);
    }

    public function test_replay_throws_integrity_violation_on_tampered_snapshot(): void
    {
        $decision = $this->makeConstitutionalGovernanceDecision();
        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        // Corrupt the snapshot by changing the legitimacy
        $tamperedSnapshot = new GovernanceDecisionSnapshot(
            decisionId: $snapshot->decisionId,
            decidedAt: $snapshot->decidedAt,
            capabilityType: $snapshot->capabilityType,
            winningAuthorityId: $snapshot->winningAuthorityId,
            legitimacy: 'expired',  // Changed from legitimate
            constitutionalReasonJson: $snapshot->constitutionalReasonJson,
            arbitrationTraceJson: $snapshot->arbitrationTraceJson,
            metadata: $snapshot->metadata,
            constitutionalScope: $snapshot->constitutionalScope,
            integrityHash: $snapshot->integrityHash,  // Hash is now wrong
        );

        $store = $this->createMock(GovernanceDecisionStore::class);
        $store->method('findById')->willReturn($tamperedSnapshot);

        $clock = $this->createMock(GovernanceClock::class);

        $service = new GovernanceReplayService($store, $clock);

        $this->expectException(SnapshotIntegrityViolationException::class);
        $service->replay($decision->id());
    }
}
