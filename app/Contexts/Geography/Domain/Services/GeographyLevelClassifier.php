<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Domain\Services;

/**
 * Classifies geographic administrative levels into machine-readable type labels.
 *
 * This is a domain policy service: the mapping from integer level to admin type
 * is classification policy, not intrinsic to the level value itself.
 * Keeping it in a dedicated service avoids coupling GeographyLevel (a value object)
 * to application-specific naming conventions.
 */
final class GeographyLevelClassifier
{
    private const MAP = [
        0 => 'international',
        1 => 'continent',
        2 => 'country',
        3 => 'province',
        4 => 'district',
        5 => 'local_level',
        6 => 'ward',
        7 => 'neighborhood',
        8 => 'street',
        9 => 'house_number',
        10 => 'block',
    ];

    public function resolve(int $level): string
    {
        return self::MAP[$level] ?? "level_{$level}";
    }
}
