<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Timeline;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceDecisionSnapshot;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\CanonicalConstitutionalSerializer;
use App\Contexts\Membership\Domain\Committee\Constitutional\Snapshot\ConstitutionalReplayFingerprint;

final class GovernanceTimelineProjector
{
    public function __construct(
        private readonly CanonicalConstitutionalSerializer $serializer,
    ) {}

    public function project(GovernanceDecisionSnapshot $snapshot): GovernanceTimelineProjection
    {
        return new GovernanceTimelineProjection(
            decisionId:         $snapshot->decisionId,
            legitimacy:         GovernanceLegitimacy::from($snapshot->legitimacy),
            winningAuthorityId: $snapshot->winningAuthorityId,
            capabilityType:     $snapshot->capabilityType,
            decidedAt:          $snapshot->decidedAt,
            persistedAt:        $snapshot->metadata->generatedAt,
            replayFingerprint:  ConstitutionalReplayFingerprint::fromSnapshot($snapshot)->toString(),
            doctrineVersion:    $snapshot->metadata->doctrineVersion,
            schemaVersion:      $snapshot->metadata->schemaVersion,
        );
    }
}
