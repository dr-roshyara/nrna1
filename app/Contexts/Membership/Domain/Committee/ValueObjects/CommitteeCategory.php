<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\ValueObjects;

/**
 * CommitteeCategory
 *
 * Type-safe backed enum for committee creation categories.
 * Replaces raw string committeeCategory — eliminates primitive obsession,
 * typo risk, and implicit domain semantics.
 *
 * This is UI-facing vocabulary that maps to domain CommitteeType.
 * The mapping lives in CommitteePolicyResolver.
 *
 * Phase 8C.2 will migrate toward geo_unit_id inference; this enum
 * remains the stable vocabulary for the manual-selection path.
 */
enum CommitteeCategory: string
{
    case CENTRAL = 'central';
    case PROVINCE = 'province';
    case DISTRICT = 'district';
    case WARD = 'ward';
    case YOUTH = 'youth';
    case WOMEN = 'women';
    case STUDENT = 'student';

    public function isWingType(): bool
    {
        return match ($this) {
            self::YOUTH, self::WOMEN, self::STUDENT => true,
            default => false,
        };
    }

    public function isGeographicType(): bool
    {
        return match ($this) {
            self::PROVINCE, self::DISTRICT, self::WARD => true,
            default => false,
        };
    }

    public function isCentral(): bool
    {
        return $this === self::CENTRAL;
    }
}
