<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional;

final readonly class GovernanceArchaeologyRecord
{
    public function __construct(
        public GovernanceDecisionSnapshot $snapshot,
        public \DateTimeImmutable         $replayedAt,
    ) {}

    public function decisionId(): string { return $this->snapshot->decisionId; }

    public function legitimacy(): GovernanceLegitimacy
    {
        return GovernanceLegitimacy::from($this->snapshot->legitimacy);
    }

    public function wasConstitutionallyValid(): bool { return $this->legitimacy()->isValid(); }
    public function winningAuthorityId(): ?string    { return $this->snapshot->winningAuthorityId; }
    public function originallyDecidedAt(): \DateTimeImmutable { return $this->snapshot->decidedAt; }
    public function persistedAt(): \DateTimeImmutable { return $this->snapshot->metadata->generatedAt; }
}
