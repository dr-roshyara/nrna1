<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Lineage;

final readonly class GovernanceLineageNode
{
    public function __construct(
        public string $decisionId,
        public string $integrityHash,
        public \DateTimeImmutable $decidedAt,
    ) {}
}
