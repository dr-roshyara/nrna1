<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/**
 * An insert-only assertion about ONE slot (v3.11 §3): value (or none), valid interval [from, to), basis.
 * I-4 (as amended by D-229, Q-DS option A): every establish claim (EXISTENCE with a value) cites a competent
 * establishing instrument. A duplicate search is supplementary, recorded as a Revision assessment, never the basis.
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
        if ($establishes && $basis->kind !== BasisKind::Instrument) {
            throw new \InvalidArgumentException('I-4 (D-229): an establish claim cites a competent establishing instrument; a duplicate search is a supplementary assessment.');
        }
    }
}
