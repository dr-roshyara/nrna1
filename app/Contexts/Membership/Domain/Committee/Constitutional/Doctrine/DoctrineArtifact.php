<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Doctrine;

use App\Contexts\Membership\Domain\Committee\Constitutional\ConstitutionalScope;

final readonly class DoctrineArtifact
{
    public function __construct(
        public string $doctrineId,
        public string $version,
        public ConstitutionalScope $constitutionalScope,
        public \DateTimeImmutable $effectiveFrom,
        public ?\DateTimeImmutable $effectiveUntil,
        public array $doctrineRules,
        public string $doctrineHash,
        public DoctrineProvenance $provenance,
    ) {}

    public function isEffectiveAt(\DateTimeImmutable $at): bool
    {
        if ($at < $this->effectiveFrom) {
            return false;
        }

        if ($this->effectiveUntil !== null && $at > $this->effectiveUntil) {
            return false;
        }

        return true;
    }
}
