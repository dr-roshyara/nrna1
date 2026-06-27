<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

use DomainException;

/**
 * CommitteeGeoIdentity Value Object
 *
 * Represents ONLY the immutable geographic anchor of a committee.
 * This is a PURE identity carrier — no classification, no admin level,
 * no region/country codes.
 *
 * Identity must be deterministic and stable across ALL governance rules.
 * adminLevel, regionCode, countryCode are DERIVED concerns — they belong
 * in the semantic projection layer, not in identity.
 */
final readonly class CommitteeGeoIdentity
{
    public function __construct(
        public int $geoUnitId,
    ) {
        if ($geoUnitId < 1) {
            throw new DomainException('geoUnitId must be a positive integer');
        }
    }

    /** Two identities are equal when their geoUnitId matches */
    public function equals(self $other): bool
    {
        return $this->geoUnitId === $other->geoUnitId;
    }
}
