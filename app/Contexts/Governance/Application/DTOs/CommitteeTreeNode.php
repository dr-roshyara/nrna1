<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\DTOs;

use App\Contexts\Governance\Domain\Committee\ViewModels\CommitteeGovernanceProjection;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;

final readonly class CommitteeTreeNode
{
    /** @param array<CommitteeTreeNode> $children */
    public function __construct(
        public CommitteeId $id,
        public string $name,
        public int $level,
        public ?CommitteeId $parentId,
        public CommitteeGovernanceProjection $governance,
        public array $children = [],
    ) {}

    public function hasChildren(): bool
    {
        return $this->children !== [];
    }
}
