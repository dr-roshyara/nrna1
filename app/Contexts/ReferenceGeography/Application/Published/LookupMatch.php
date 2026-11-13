<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/**
 * One place a lookup text may mean (G-5b; published language). A suggestion only: the consumer stores it as supplied,
 * and no match leaves its record unresolved; a lookup never places anyone (D-79, D-80).
 */
final readonly class LookupMatch
{
    /**
     * @param string $quality exact | very_high | high | medium | low
     * @param string $matchedOn name | identifier
     * @param string $detail a name's "language/type", or an identifier's namespace
     * @param bool $partial matched through a run of the name's words, not the whole name
     */
    public function __construct(
        public string $unitId,
        public string $quality,
        public float $score,
        public string $matchedOn,
        public string $matchedValue,
        public string $detail,
        public bool $partial,
        public ?string $mergedInto,
    ) {
    }
}
