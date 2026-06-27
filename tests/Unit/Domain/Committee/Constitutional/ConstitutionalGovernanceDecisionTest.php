<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalGovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class ConstitutionalGovernanceDecisionTest extends TestCase
{
    private function makeGovernanceDecision(\DateTimeImmutable $at, ?GovernanceDecisionId $id = null): GovernanceDecision
    {
        $id ??= GovernanceDecisionId::generate();

        return new GovernanceDecision(
            id: $id,
            decidedAt: $at,
            evaluation: CapabilityEvaluation::allow('test'),
            classification: new AuthorityClassification(null, [], [], []),
            detectedConflicts: AuthorityConflictCollection::empty(),
            precedenceRanking: AuthorityPrecedenceRanking::empty(),
            producedEvent: new GovernanceDecisionProduced(
                decisionId: $id->toString(),
                tenantId: 'tenant-1',
                capabilityType: 'committee_creation',
                resolutionType: 'none',
                winningAuthorityId: null,
            ),
        );
    }

    private function makeConstitutionalDecision(?JurisdictionNode $winner = null, GovernanceLegitimacy $legitimacy = GovernanceLegitimacy::LEGITIMATE): ConstitutionalDecision
    {
        return new ConstitutionalDecision(
            winner: $winner,
            legitimacy: $legitimacy,
            reason: ConstitutionalReason::resolved('direct', 'Direct authority'),
            evaluatedAt: new \DateTimeImmutable(),
            trace: new ConstitutionalArbitrationTrace(
                evaluatedNodeIds: [],
                selectedNodeId: $winner?->id,
                precedenceReason: 'test',
                doctrineRulesApplied: [],
            ),
        );
    }

    public function test_id_delegates_to_governance_decision(): void
    {
        $id = GovernanceDecisionId::generate();
        $now = new \DateTimeImmutable();
        $governance = $this->makeGovernanceDecision($now, $id);
        $constitutional = $this->makeConstitutionalDecision();

        $wrapper = new ConstitutionalGovernanceDecision($governance, $constitutional);

        $this->assertSame($id, $wrapper->id());
    }

    public function test_winner_delegates_to_constitutional_decision(): void
    {
        $winner = new JurisdictionNode('node-win', 'national', null, true, false);
        $now = new \DateTimeImmutable();
        $governance = $this->makeGovernanceDecision($now);
        $constitutional = $this->makeConstitutionalDecision($winner);

        $wrapper = new ConstitutionalGovernanceDecision($governance, $constitutional);

        $this->assertSame($winner, $wrapper->winner());
    }

    public function test_is_constitutionally_valid_when_legitimate(): void
    {
        $now = new \DateTimeImmutable();
        $governance = $this->makeGovernanceDecision($now);
        $constitutional = $this->makeConstitutionalDecision(null, GovernanceLegitimacy::LEGITIMATE);

        $wrapper = new ConstitutionalGovernanceDecision($governance, $constitutional);

        $this->assertTrue($wrapper->isConstitutionallyValid());
    }
}
