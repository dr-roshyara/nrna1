<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** A merged identity (v3.11 §12): B resolves to the canonical A, known since the reconciliation's position. */
final readonly class IdentityResolution
{
    public function __construct(public ReferenceUnitId $canonical, public int $knownAtPosition, public ClaimId $claim)
    {
    }
}
