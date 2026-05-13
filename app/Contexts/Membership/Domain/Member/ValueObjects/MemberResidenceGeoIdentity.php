<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Member\ValueObjects;

use DomainException;

/**
 * MemberResidenceGeoIdentity — Value object for member's residence geography.
 *
 * Wraps the raw geo_administrative_units.id FK as a typed value object.
 * Used by the member aggregate and eligibility validation pipeline.
 */
final readonly class MemberResidenceGeoIdentity
{
    public function __construct(
        public int $residenceGeoUnitId
    ) {
        if ($residenceGeoUnitId < 1) {
            throw new DomainException('residenceGeoUnitId must be a positive integer');
        }
    }
}
