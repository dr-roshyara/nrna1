<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain;

use App\Contexts\Governance\Domain\ValueObjects\ConstitutionalBasis;
use App\Contexts\Governance\Domain\ValueObjects\DecisionTrace;
use App\Contexts\Governance\Domain\ValueObjects\GovernanceDecisionId;
use App\Contexts\Governance\Domain\ValueObjects\Legitimacy;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use DateTimeImmutable;
use DomainException;

final readonly class GovernanceDecision
{
    private function __construct(
        private GovernanceDecisionId $decisionId,
        private CommitteeId $committeeId,
        private string $capabilityType,
        private Legitimacy $legitimacy,
        private ConstitutionalBasis $constitutionalBasis,
        private DecisionTrace $trace,
        private DateTimeImmutable $effectiveFrom,
        private DateTimeImmutable $decidedAt,
    ) {}

    public static function generateId(): GovernanceDecisionId
    {
        return GovernanceDecisionId::generate();
    }

    public static function record(
        GovernanceDecisionId $decisionId,
        CommitteeId $committeeId,
        string $capabilityType,
        Legitimacy $legitimacy,
        ConstitutionalBasis $constitutionalBasis,
        DecisionTrace $trace,
        DateTimeImmutable $effectiveFrom,
        DateTimeImmutable $decidedAt,
        DateTimeImmutable $now,
    ): self {
        if (empty(trim($capabilityType))) {
            throw new DomainException('Capability type cannot be empty');
        }

        if ($effectiveFrom > $decidedAt) {
            throw new DomainException('Effective date cannot be after decision date');
        }

        if ($decidedAt > $now) {
            throw new DomainException('Decision cannot be dated in the future');
        }

        return new self(
            $decisionId,
            $committeeId,
            $capabilityType,
            $legitimacy,
            $constitutionalBasis,
            $trace,
            $effectiveFrom,
            $decidedAt
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

    public function constitutionalBasis(): ConstitutionalBasis
    {
        return $this->constitutionalBasis;
    }

    public function trace(): DecisionTrace
    {
        return $this->trace;
    }

    public function effectiveFrom(): DateTimeImmutable
    {
        return $this->effectiveFrom;
    }

    public function decidedAt(): DateTimeImmutable
    {
        return $this->decidedAt;
    }

    public function equals(self $other): bool
    {
        return $this->decisionId->equals($other->decisionId)
            && $this->committeeId->equals($other->committeeId)
            && $this->capabilityType === $other->capabilityType
            && $this->legitimacy === $other->legitimacy
            && $this->constitutionalBasis->equals($other->constitutionalBasis)
            && $this->trace->equals($other->trace)
            && $this->effectiveFrom == $other->effectiveFrom
            && $this->decidedAt == $other->decidedAt;
    }
}
