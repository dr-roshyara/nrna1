<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * The name to show a user for a country, area or level-1 region (G-7; D-87, extended to the regions by Option A of
 * 2026-11-14; v3.11 §9), as officially known at a knowledge position. Published language: strings, ints and a bool.
 *
 * - origin CLDR: the Unicode CLDR display name; resolvedLocale is the CLDR locale that held it (the requested one or
 *   a parent), cldrRelease the release it comes from;
 * - origin PLATFORM_FALLBACK: the user's locale chain had no CLDR name, so the platform default locale's chain was
 *   used (fallbackLocale). Never present this as a translation into the user's language;
 * - form: the form used (standard | short); shortFellBack: a short form was asked and only the standard existed.
 * PublicDigit authors no name. Attributed official names (ISO, UN M49, …) are in Place::$names.
 */
final readonly class DisplayName
{
    public function __construct(
        public string $unitId,
        public string $value,
        public string $origin,
        public string $requestedLocale,
        public ?string $resolvedLocale,
        public ?string $cldrRelease,
        public string $form,
        public bool $shortFellBack,
        public ?string $fallbackLocale,
        public string $validAt,
        public int $knownAt,
    ) {
    }
}
