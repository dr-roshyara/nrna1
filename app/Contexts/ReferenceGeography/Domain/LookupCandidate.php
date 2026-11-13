<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** One published name or code of a place, as believed at (t, p): what PlaceLookup may match (G-5b). */
final readonly class LookupCandidate
{
    public const NAME = 'name';
    public const IDENTIFIER = 'identifier';

    /** @param string $detail a name's "language/type", or an identifier's namespace */
    public function __construct(public ReferenceUnitId $unit, public string $kind, public string $value, public string $detail)
    {
        if (!in_array($kind, [self::NAME, self::IDENTIFIER], true)) {
            throw new \InvalidArgumentException("Unknown lookup candidate kind '{$kind}'.");
        }
    }
}
