<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

final readonly class CommitteeSnapshot
{
    public function __construct(
        public string $id,
        public ?GeoPathChain $geoScope,
        public string $type,
    ) {}

    public function isCentral(): bool
    {
        return $this->type === 'central' && $this->geoScope === null;
    }
}
