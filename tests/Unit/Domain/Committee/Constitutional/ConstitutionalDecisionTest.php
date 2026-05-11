<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalDecision;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalReason;
use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalArbitrationTrace;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;

final class ConstitutionalDecisionTest extends TestCase
{
    private function makeTrace(?string $selectedNodeId = null): ConstitutionalArbitrationTrace
    {
        return new ConstitutionalArbitrationTrace(
            evaluatedNodeIds: $selectedNodeId !== null ? [$selectedNodeId] : [],
            selectedNodeId: $selectedNodeId,
            precedenceReason: 'test_reason',
            doctrineRulesApplied: [],
        );
    }

    public function test_winner_node_preserved(): void
    {
        $winner = new JurisdictionNode('node-1', 'national', null, true);
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');

        $decision = new ConstitutionalDecision(
            winner: $winner,
            legitimacy: GovernanceLegitimacy::LEGITIMATE,
            reason: ConstitutionalReason::resolved('direct', 'Direct authority'),
            evaluatedAt: $now,
            trace: $this->makeTrace('node-1'),
        );

        $this->assertSame('node-1', $decision->winner->id);
    }

    public function test_legitimacy_set_correctly(): void
    {
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');

        $decision = new ConstitutionalDecision(
            winner: null,
            legitimacy: GovernanceLegitimacy::EXPIRED,
            reason: ConstitutionalReason::noAuthority(),
            evaluatedAt: $now,
            trace: $this->makeTrace(),
        );

        $this->assertSame(GovernanceLegitimacy::EXPIRED, $decision->legitimacy);
    }

    public function test_evaluated_at_preserved(): void
    {
        $now = new \DateTimeImmutable('2026-05-08T10:00:00Z');

        $decision = new ConstitutionalDecision(
            winner: null,
            legitimacy: GovernanceLegitimacy::LEGITIMATE,
            reason: ConstitutionalReason::resolved('override', 'Override applied'),
            evaluatedAt: $now,
            trace: $this->makeTrace(),
        );

        $this->assertSame($now, $decision->evaluatedAt);
    }
}
