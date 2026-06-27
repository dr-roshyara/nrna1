<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecision;
use App\Contexts\Membership\Domain\Committee\Geo\Kernel\GovernanceDecisionId;

final readonly class ConstitutionalGovernanceDecision
{
    public function __construct(
        public GovernanceDecision     $governanceDecision,
        public ConstitutionalDecision $constitutionalDecision,
    ) {}

    public function id(): GovernanceDecisionId
    {
        return $this->governanceDecision->id;
    }

    public function decidedAt(): \DateTimeImmutable
    {
        return $this->governanceDecision->decidedAt;
    }

    public function winner(): ?JurisdictionNode
    {
        return $this->constitutionalDecision->winner;
    }

    public function legitimacy(): GovernanceLegitimacy
    {
        return $this->constitutionalDecision->legitimacy;
    }

    public function isConstitutionallyValid(): bool
    {
        return $this->constitutionalDecision->legitimacy->isValid();
    }
}
