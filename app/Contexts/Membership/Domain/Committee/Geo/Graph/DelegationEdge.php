<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Graph;

final readonly class DelegationEdge
{
    public function __construct(
        public string $fromJurisdictionId,
        public string $toJurisdictionId,
        public DelegationType $type,
        public \DateTimeImmutable $validFrom,
        public ?\DateTimeImmutable $validTo,
    ) {}

    public function isValidAt(\DateTimeImmutable $at): bool
    {
        return $at >= $this->validFrom && ($this->validTo === null || $at <= $this->validTo);
    }
}
