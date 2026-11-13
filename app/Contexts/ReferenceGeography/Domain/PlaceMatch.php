<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A place's best match for a lookup text (G-5b). partial: matched through a run of the name's words, not the whole. */
final readonly class PlaceMatch
{
    public function __construct(
        public ReferenceUnitId $unit,
        public MatchQuality $quality,
        public float $score,
        public LookupCandidate $candidate,
        public bool $partial,
    ) {
    }
}
