<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\DTOs;

use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use DateTimeImmutable;

// Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
final readonly class CommitteeHierarchyRecord
{
    public function __construct(
        public CommitteeId $id,
        public string $name,
        public int $level,
        public ?CommitteeId $parentId,
        public string $operationalState,
        public string $temporalState,
        public string $legitimacy,
        public bool $canAct,
        public bool $isFullyOperational,
        public int $projectionVersion,
        public ?DateTimeImmutable $termStart,
        public ?DateTimeImmutable $termEnd,
        public int $pendingApprovals = 0,
    ) {}

    public function equals(self $other): bool
    {
        return $this->id->equals($other->id)
            && $this->name === $other->name
            && $this->level === $other->level
            && ($this->parentId?->equals($other->parentId) ?? $other->parentId === null)
            && $this->operationalState === $other->operationalState
            && $this->temporalState === $other->temporalState
            && $this->legitimacy === $other->legitimacy
            && $this->canAct === $other->canAct
            && $this->isFullyOperational === $other->isFullyOperational
            && $this->projectionVersion === $other->projectionVersion
            && $this->termStart == $other->termStart
            && $this->termEnd == $other->termEnd
            && $this->pendingApprovals === $other->pendingApprovals;
    }
}
