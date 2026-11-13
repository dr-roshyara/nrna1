<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * What may be believed about one place at valid date t, as known at position p (G-5a; v3.11 §4, §12). Built only by
 * PlaceBelief, from published slots.
 */
final readonly class PlaceFacts
{
    /**
     * @param array<string, string> $parents scheme => parent unit id
     * @param array<string, string> $classifications scheme => class
     * @param array<string, string> $identifiers namespace => value
     * @param list<array{source: string, language: string, type: string, value: string}> $names
     * @param list<string> $disputedKinds claim kinds whose claim in force is disputed at p
     */
    public function __construct(
        public ReferenceUnitId $unit,
        public bool $known,
        public bool $exists,
        public array $parents,
        public array $classifications,
        public array $identifiers,
        public array $names,
        public array $disputedKinds,
        public ?ReferenceUnitId $mergedInto,
    ) {
    }
}
