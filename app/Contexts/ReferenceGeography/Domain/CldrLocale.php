<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * A CLDR locale identifier as CLDR writes it (D-87; v3.11 §9): "root", or a lowercase language of 2-3 letters followed
 * by underscore-separated subtags (script, region, variant), e.g. de, de_AT, en_001, zh_Hant_TW. A caller's BCP 47
 * hyphen form (de-AT) is normalised to the underscore form before resolution.
 */
final class CldrLocale
{
    public const ROOT = 'root';

    public static function isValid(string $locale): bool
    {
        return $locale === self::ROOT || preg_match('/^[a-z]{2,3}(_[A-Za-z0-9]{2,8})*$/', $locale) === 1;
    }

    public static function normalise(string $locale): string
    {
        return str_replace('-', '_', trim($locale));
    }
}
