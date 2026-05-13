<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

/**
 * JurisdictionInstance represents a validated governance-geo allocation.
 *
 * This VO holds a snapshot of a valid matrix cell (governance level + geo level + geo code).
 * It has ZERO validation logic and ZERO string parsing — it is a pure data snapshot.
 *
 * Invariants enforced BEFORE construction:
 * - Matrix.isAllowed(governanceLevel, geoLevel) must return true
 * - governanceLevel and geoLevel are in valid range (0-10)
 *
 * After construction, this VO is immutable and provides only identity comparison.
 */
final readonly class JurisdictionInstance
{
    public function __construct(
        public int $governanceLevel,
        public int $geoLevel,
        public string $geoCode,
    ) {}

    public function equals(self $other): bool
    {
        return $this->governanceLevel === $other->governanceLevel
            && $this->geoLevel === $other->geoLevel
            && $this->geoCode === $other->geoCode;
    }
}
