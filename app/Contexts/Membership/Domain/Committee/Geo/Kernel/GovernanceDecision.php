<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Kernel;

use App\Contexts\Membership\Domain\Committee\Capability\CapabilityEvaluation;
use App\Contexts\Membership\Domain\Committee\Geo\Authority\AuthorityClassification;
use App\Contexts\Membership\Domain\Committee\Geo\Conflict\AuthorityConflictCollection;
use App\Contexts\Membership\Domain\Committee\Geo\Precedence\AuthorityPrecedenceRanking;
use App\Contexts\Membership\Domain\Committee\Geo\Resolution\FinalAuthorityDecision;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final readonly class GovernanceDecision
{
    public function __construct(
        public GovernanceDecisionId $id,
        public \DateTimeImmutable $decidedAt,
        public CapabilityEvaluation $evaluation,
        public AuthorityClassification $classification,
        public AuthorityConflictCollection $detectedConflicts,
        public AuthorityPrecedenceRanking $precedenceRanking,
        public GovernanceDecisionProduced $producedEvent,
        public ?FinalAuthorityDecision $finalDecision = null,
    ) {}

    public function hasConflicts(): bool
    {
        return !$this->detectedConflicts->isEmpty();
    }

    /**
     * Get the highest-ranked authority from precedence ranking, or null if none.
     */
    public function topRankedAuthority(): ?array
    {
        return $this->precedenceRanking->top();
    }
}
