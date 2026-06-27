<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Context;

final readonly class CommitteeLineageView
{
    public function __construct(
        public string $committeeId,
        public ?string $parentCommitteeId = null,
        public int $depth = 0,
    ) {}

    public function isRoot(): bool
    {
        return $this->parentCommitteeId === null && $this->depth === 0;
    }

    public static function root(string $committeeId = 'pending'): self
    {
        return new self($committeeId, null, 0);
    }
}
