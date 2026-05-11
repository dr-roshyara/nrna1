<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Committee\ViewModels;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\ConstitutionalLegitimacy;
use App\Contexts\Membership\Domain\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\ValueObjects\TemporalGovernanceState;
use DateTimeImmutable;

// Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
final readonly class CommitteeGovernanceProjection
{
    public function __construct(
        public CommitteeId $committeeId,
        public StructuralOperationalState $operationalState,
        public TemporalGovernanceState $temporalState,
        public ConstitutionalLegitimacy $legitimacy,
        public DateTimeImmutable $evaluatedAt,
    ) {}

    public function canAct(): bool
    {
        return $this->operationalState === StructuralOperationalState::ACTIVE
            && $this->legitimacy === ConstitutionalLegitimacy::LEGITIMATE
            && $this->temporalState !== TemporalGovernanceState::EXPIRED;
    }

    public function isFullyOperational(): bool
    {
        return $this->operationalState === StructuralOperationalState::ACTIVE
            && $this->temporalState === TemporalGovernanceState::VALID
            && $this->legitimacy === ConstitutionalLegitimacy::LEGITIMATE;
    }
}
