<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\ConflictResolutionPolicy;

final class DefaultConstitutionalArbitrationPolicy implements ConstitutionalArbitrationPolicy
{
    public function __construct(
        private readonly ConflictResolutionPolicy $resolutionPolicy,
        // LegitimacyPolicy NOT injected here — extension point for GEO-3.2+
        // when administrative signals (suspension, emergency declarations) materialize
    ) {}

    public function arbitrate(
        AuthorityClassification $classification,
        \DateTimeImmutable $at,
    ): ConstitutionalDecision {
        $finalDecision = $this->resolutionPolicy->resolve($classification);

        $legitimacy = $finalDecision->winningNode !== null
            ? GovernanceLegitimacy::LEGITIMATE
            : GovernanceLegitimacy::EXPIRED;

        $reason = $finalDecision->winningNode !== null
            ? ConstitutionalReason::resolved($finalDecision->type->value, $finalDecision->resolutionReason)
            : ConstitutionalReason::noAuthority();

        $evaluatedNodeIds = $this->collectAllNodeIds($classification);

        $trace = new ConstitutionalArbitrationTrace(
            evaluatedNodeIds:     $evaluatedNodeIds,
            selectedNodeId:       $finalDecision->winningNode?->id,
            precedenceReason:     $finalDecision->resolutionReason,
            doctrineRulesApplied: [$finalDecision->type->value],
        );

        return new ConstitutionalDecision(
            winner:      $finalDecision->winningNode,
            legitimacy:  $legitimacy,
            reason:      $reason,
            evaluatedAt: $at,
            trace:       $trace,
        );
    }

    /** @return string[] */
    private function collectAllNodeIds(AuthorityClassification $classification): array
    {
        $ids = [];
        foreach ($classification->exceptions as $node) { $ids[] = $node->id; }
        foreach ($classification->overrides  as $node) { $ids[] = $node->id; }
        if ($classification->direct !== null)           { $ids[] = $classification->direct->id; }
        foreach ($classification->delegated  as $node) { $ids[] = $node->id; }
        return $ids;
    }
}
