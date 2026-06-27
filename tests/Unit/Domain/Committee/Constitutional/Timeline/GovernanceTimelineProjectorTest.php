<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Timeline;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\Timeline\GovernanceTimelineProjection;
use App\Contexts\Membership\Domain\Committee\Constitutional\Timeline\GovernanceTimelineProjector;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\CanonicalConstitutionalSerializer;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
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

final class GovernanceTimelineProjectorTest extends TestCase
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

    public function test_project_returns_timeline_projection(): void
    {
        $projector = new GovernanceTimelineProjector(new CanonicalConstitutionalSerializer());
        $snapshot = $this->makeSnapshot();

        $projection = $projector->project($snapshot);

        $this->assertInstanceOf(GovernanceTimelineProjection::class, $projection);
    }

    public function test_projection_decision_id_matches_snapshot(): void
    {
        $projector = new GovernanceTimelineProjector(new CanonicalConstitutionalSerializer());
        $snapshot = $this->makeSnapshot();

        $projection = $projector->project($snapshot);

        $this->assertSame($snapshot->decisionId, $projection->decisionId);
    }

    public function test_projection_is_deterministic(): void
    {
        $projector = new GovernanceTimelineProjector(new CanonicalConstitutionalSerializer());
        $snapshot = $this->makeSnapshot();

        $projection1 = $projector->project($snapshot);
        $projection2 = $projector->project($snapshot);

        $this->assertSame($projection1->replayFingerprint, $projection2->replayFingerprint);
    }
}
