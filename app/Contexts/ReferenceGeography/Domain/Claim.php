<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * An insert-only assertion about ONE slot (v3.11 §3): value (or none), valid interval [from, to), basis.
 * I-4: every establish claim (EXISTENCE with a value) cites an establishing instrument or a recorded
 * duplicate-search assessment.
 */
final readonly class Claim
{
    public function __construct(
        public ClaimId $id,
        public SlotKey $slot,
        public ClaimValue $value,
        public ValidInterval $interval,
        public Basis $basis,
    ) {
        $establishes = $slot->kind() === ClaimKind::Existence && !$value->isNone();
        if ($establishes && !in_array($basis->kind, [BasisKind::Instrument, BasisKind::DuplicateSearchAssessment], true)) {
            throw new \InvalidArgumentException('I-4: an establish claim cites an establishing instrument or a duplicate-search assessment.');
        }
    }
}
