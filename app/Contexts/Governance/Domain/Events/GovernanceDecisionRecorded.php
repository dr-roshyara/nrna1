<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Events;

use App\Contexts\Governance\Domain\ValueObjects\AuthorityChain;
use App\Contexts\Governance\Domain\ValueObjects\ConstitutionalBasis;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Governance\Domain\ValueObjects\Legitimacy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;

final readonly class GovernanceDecisionRecorded
{
    private function __construct(
        private GovernanceDecisionId $decisionId,
        private CommitteeId $committeeId,
        private string $capabilityType,
        private Legitimacy $legitimacy,
        private AuthorityChain $authorityChain,
        private ConstitutionalBasis $constitutionalBasis,
        private DateTimeImmutable $effectiveFrom,
        private DateTimeImmutable $occurredAt,
    ) {}

    public static function from(
        GovernanceDecisionId $decisionId,
        CommitteeId $committeeId,
        string $capabilityType,
        Legitimacy $legitimacy,
        AuthorityChain $authorityChain,
        ConstitutionalBasis $constitutionalBasis,
        DateTimeImmutable $effectiveFrom,
        DateTimeImmutable $occurredAt,
    ): self {
        return new self(
            $decisionId,
            $committeeId,
            $capabilityType,
            $legitimacy,
            $authorityChain,
            $constitutionalBasis,
            $effectiveFrom,
            $occurredAt,
        );
    }

    public function decisionId(): GovernanceDecisionId
    {
        return $this->decisionId;
    }

    public function committeeId(): CommitteeId
    {
        return $this->committeeId;
    }

    public function capabilityType(): string
    {
        return $this->capabilityType;
    }

    public function legitimacy(): Legitimacy
    {
        return $this->legitimacy;
    }

    public function authorityChain(): AuthorityChain
    {
        return $this->authorityChain;
    }

    public function constitutionalBasis(): ConstitutionalBasis
    {
        return $this->constitutionalBasis;
    }

    public function effectiveFrom(): DateTimeImmutable
    {
        return $this->effectiveFrom;
    }

    public function occurredAt(): DateTimeImmutable
    {
        return $this->occurredAt;
    }
}
