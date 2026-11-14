<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** Where a display name came from (v3.11 §9). A platform fallback is never presented as a CLDR result. */
enum DisplayNameOrigin: string
{
    case Cldr = 'CLDR';
    case PlatformFallback = 'PLATFORM_FALLBACK';
}
