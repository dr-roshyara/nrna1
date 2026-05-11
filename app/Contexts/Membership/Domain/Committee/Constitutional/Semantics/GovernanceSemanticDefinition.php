<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Semantics;

final readonly class GovernanceSemanticDefinition
{
    public function __construct(
        public GovernanceSemanticCode $code,
        public GovernanceSemanticCategory $category,
        public string $canonicalMeaning,
        public string $replayImplications,
        public string $legitimacyImplications,
        public string $introducedInDoctrineVersion,
    ) {
        if (empty(trim($this->canonicalMeaning))) {
            throw new \InvalidArgumentException('Canonical meaning cannot be empty');
        }

        if (empty(trim($this->replayImplications))) {
            throw new \InvalidArgumentException('Replay implications cannot be empty');
        }
    }
}
