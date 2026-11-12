<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Organisational\PublishedLanguage;

/** One key's answer in C2: the membership as known at τ. Exactly one of membershipId / personRef is set (the key used). */
final readonly class CoverageKnownAtItem
{
    /**
     * @param list<CoverageInterval> $coverage
     * @param list<CoverageInterval> $inForce
     * @param list<CoverageInterval> $suspended
     */
    public function __construct(
        public ?string $membershipId,
        public ?string $personRef,
        public int $seq,
        public array $coverage,
        public array $inForce,
        public array $suspended,
    ) {
    }
}
