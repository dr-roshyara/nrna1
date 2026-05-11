<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Lineage;

final readonly class GovernanceLineageEdge
{
    public function __construct(
        public string $fromNodeId,
        public string $toNodeId,
        public string $edgeReason,
    ) {}
}
