<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use App\Contexts\Geography\Domain\ValueObjects\GeoUnitId;

final readonly class GovernanceAssignment
{
    public function __construct(
        public int $governanceLevel,
        public int $geoLevel,
        public GeoUnitId $geoUnitId,
    ) {}

    public function equals(self $other): bool
    {
        return $this->governanceLevel === $other->governanceLevel
            && $this->geoLevel === $other->geoLevel
            && $this->geoUnitId->equals($other->geoUnitId);
    }
}
