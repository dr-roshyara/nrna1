<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Timeline;

use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;
use App\Contexts\Membership\Domain\Committee\Constitutional\Projection\ProjectionAntiCorruptionBoundary;

final readonly class GovernanceTimelineProjection implements ProjectionAntiCorruptionBoundary
{
    public function __construct(
        public string             $decisionId,
        public GovernanceLegitimacy $legitimacy,
        public ?string            $winningAuthorityId,
        public string             $capabilityType,
        public \DateTimeImmutable $decidedAt,
        public \DateTimeImmutable $persistedAt,
        public string             $replayFingerprint,
        public string             $doctrineVersion,
        public string             $schemaVersion,
    ) {}

    public function wasConstitutionallyValid(): bool
    {
        return $this->legitimacy->isValid();
    }
}
