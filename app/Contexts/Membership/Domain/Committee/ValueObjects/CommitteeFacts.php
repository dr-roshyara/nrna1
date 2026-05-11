<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

final readonly class CommitteeFacts
{
    public function __construct(
        public CommitteeId $id,
        public string $operationalState,
        public ?TermPeriod $term,
        public ?CommitteeId $parentId,
    ) {}
}
