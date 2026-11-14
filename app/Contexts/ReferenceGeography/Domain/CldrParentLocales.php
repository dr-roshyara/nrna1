<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * CLDR locale inheritance of one release (v3.11 §9 "Inheritance: resolved at display time using that release's
 * parent-locale data, stored as a claim"; slot CLDR_INHERITANCE, value a JSON object {child: parent}).
 * The parent of a locale is the release's explicit parent if it has one, otherwise the locale with its last subtag
 * removed; a bare language's parent is root; root ends the chain. A repeated locale also ends it (never a loop).
 */
final readonly class CldrParentLocales
{
    /** @param array<string, string> $parents */
    private function __construct(private array $parents)
    {
    }

    public static function none(): self
    {
        return new self([]);
    }

    /** @throws \InvalidArgumentException if the value is not a {locale: locale} object */
    public static function fromJson(string $json): self
    {
        return self::tryFromJson($json) ?? throw new \InvalidArgumentException('A CLDR parent-locale map is a JSON object of CLDR locales.');
    }

    public static function tryFromJson(string $json): ?self
    {
        $decoded = json_decode($json, true);
        if (!is_array($decoded) || ($decoded !== [] && array_is_list($decoded))) {
            return null;
        }
        foreach ($decoded as $child => $parent) {
            if (!is_string($parent) || !CldrLocale::isValid((string) $child) || !CldrLocale::isValid($parent)) {
                return null;
            }
        }

        return new self($decoded);
    }

    /** @return list<string> the locale, then its parents, ending with root */
    public function chain(string $locale): array
    {
        $current = CldrLocale::normalise($locale);
        $chain = [];
        while (!in_array($current, $chain, true)) {
            $chain[] = $current;
            if ($current === CldrLocale::ROOT) {
                return $chain;
            }
            $cut = strrpos($current, '_');
            $current = $this->parents[$current] ?? ($cut === false ? CldrLocale::ROOT : substr($current, 0, $cut));
        }
        $chain[] = CldrLocale::ROOT;   // a cyclic map: stop, and still end at root

        return array_values(array_unique($chain));
    }
}
