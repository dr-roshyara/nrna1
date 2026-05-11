<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceArchaeologyRecord;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\SnapshotMetadata;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\CapabilityType;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class GovernanceArchaeologyRecordTest extends TestCase
{
    private function makeSnapshot(): GovernanceDecisionSnapshot
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

        $decision = new ConstitutionalGovernanceDecision(
            $governanceDecision,
            $constitutionalDecision,
        );

        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        return GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );
    }

    public function test_decision_id_accessor_returns_snapshot_value(): void
    {
        $snapshot = $this->makeSnapshot();
        $replayedAt = new \DateTimeImmutable();

        $record = new GovernanceArchaeologyRecord($snapshot, $replayedAt);

        $this->assertSame($snapshot->decisionId, $record->decisionId());
    }

    public function test_was_constitutionally_valid_true_for_legitimate(): void
    {
        $snapshot = $this->makeSnapshot();
        $replayedAt = new \DateTimeImmutable();

        $record = new GovernanceArchaeologyRecord($snapshot, $replayedAt);

        $this->assertTrue($record->wasConstitutionallyValid());
    }

    public function test_was_constitutionally_valid_false_for_expired(): void
    {
        $governanceDecision = new GovernanceDecision(
            id: GovernanceDecisionId::generate(),
            decidedAt: new \DateTimeImmutable('2026-05-08T10:00:00Z'),
            evaluation: CapabilityEvaluation::allow('test'),
            classification: new AuthorityClassification(
                exceptions: [],
                overrides: [],
                direct: null,
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
            winner: null,
            legitimacy: GovernanceLegitimacy::EXPIRED,
            reason: ConstitutionalReason::noAuthority(),
            evaluatedAt: $governanceDecision->decidedAt,
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: [],
                selectedNodeId: null,
                precedenceReason: 'none',
                doctrineRulesApplied: [],
            ),
        );

        $decision = new ConstitutionalGovernanceDecision(
            $governanceDecision,
            $constitutionalDecision,
        );

        $persistedAt = new \DateTimeImmutable('2026-05-08T11:00:00Z');

        $snapshot = GovernanceDecisionSnapshot::fromDecision(
            $decision,
            CapabilityType::COMMITTEE_CREATION,
            $persistedAt,
        );

        $replayedAt = new \DateTimeImmutable();

        $record = new GovernanceArchaeologyRecord($snapshot, $replayedAt);

        $this->assertFalse($record->wasConstitutionallyValid());
    }

    public function test_temporal_accessors_distinguish_decided_at_from_replayed_at(): void
    {
        $snapshot = $this->makeSnapshot();
        $decidedAt = $snapshot->decidedAt;
        $replayedAt = new \DateTimeImmutable('2026-05-09T15:30:00Z');

        $record = new GovernanceArchaeologyRecord($snapshot, $replayedAt);

        $this->assertNotSame($decidedAt->format('c'), $replayedAt->format('c'));
        $this->assertSame($decidedAt, $record->originallyDecidedAt());
        $this->assertSame($replayedAt, $record->replayedAt);
    }
}
