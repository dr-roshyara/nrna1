<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Published;

/** A place as believed at a valid date and position (published language; v3.11 §4). */
final readonly class Place
{
    /**
     * @param array<string, string> $parents scheme => parent unit id
     * @param array<string, string> $classifications scheme => class
     * @param array<string, string> $identifiers namespace => code
     * @param list<array{source: string, language: string, type: string, value: string}> $names source names (CLDR display names are G-7)
     * @param list<string> $disputedKinds kinds of fact whose current claim is disputed (the value is still the published one)
     */
    public function __construct(
        public string $unitId,
        public string $validAt,
        public int $position,
        public bool $exists,
        public array $parents,
        public array $classifications,
        public array $identifiers,
        public array $names,
        public array $disputedKinds,
        public ?string $mergedInto,
    ) {
    }
}
