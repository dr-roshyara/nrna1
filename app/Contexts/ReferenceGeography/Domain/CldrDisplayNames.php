<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * D-87 (and, by the Principal's Option A of 2026-11-14, the five level-1 regions): the name shown to a user for an entry
 * is the Unicode CLDR display name in the user's language. Pure; v3.11 §9:
 * 1. the requested locale and its CLDR parents (CldrParentLocales), ending at root (root is not English);
 * 2. the short form if asked; if no locale in the chain has it, the standard form, marked shortFellBack;
 * 3. if the chain has no name: the PLATFORM DEFAULT LOCALE's chain, marked PLATFORM_FALLBACK (never presented as CLDR);
 * 4. otherwise null. PublicDigit authors no name; a withdrawn name (value none) is no name.
 */
final class CldrDisplayNames
{
    private const STANDARD = 'standard';
    private const SHORT = 'short';

    /**
     * @param \Closure(string $locale, string $form): ?Claim $lookup the CLDR_NAME claim believed for the entry, if any
     * @param string|null $defaultLocale the published platform default locale, if any
     */
    public static function resolve(string $requestedLocale, string $form, \Closure $lookup, CldrParentLocales $parents, ?string $defaultLocale): ?ResolvedDisplayName
    {
        if ($form !== self::STANDARD && $form !== self::SHORT) {
            throw new \InvalidArgumentException("A display-name form is 'standard' or 'short', not '{$form}'.");
        }
        $requested = CldrLocale::normalise($requestedLocale);
        $found = self::inChain($parents->chain($requested), $form, $lookup);
        if ($found !== null) {
            [$claim, $locale, $usedForm] = $found;

            return new ResolvedDisplayName($claim->value->value(), DisplayNameOrigin::Cldr, $requested, $locale,
                CldrRelease::fromBasis($claim->basis)?->value(), $usedForm, $form === self::SHORT && $usedForm === self::STANDARD, null);
        }
        if ($defaultLocale === null) {
            return null;
        }
        $fallback = self::inChain($parents->chain($defaultLocale), $form, $lookup);
        if ($fallback === null) {
            return null;
        }
        [$claim, , $usedForm] = $fallback;

        return new ResolvedDisplayName($claim->value->value(), DisplayNameOrigin::PlatformFallback, $requested, null, null,
            $usedForm, $form === self::SHORT && $usedForm === self::STANDARD, CldrLocale::normalise($defaultLocale));
    }

    /**
     * @param list<string> $chain
     * @return array{Claim, string, string}|null the claim, the locale that held it, the form used
     */
    private static function inChain(array $chain, string $form, \Closure $lookup): ?array
    {
        foreach ($form === self::SHORT ? [self::SHORT, self::STANDARD] : [self::STANDARD] as $tryForm) {
            foreach ($chain as $locale) {
                $claim = $lookup($locale, $tryForm);
                if ($claim !== null && !$claim->value->isNone()) {
                    return [$claim, $locale, $tryForm];
                }
            }
        }

        return null;
    }
}
