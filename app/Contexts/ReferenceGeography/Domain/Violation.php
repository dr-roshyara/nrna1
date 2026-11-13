<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Domain;

/** One invariant a revision's claim breaks (v3.11 §5). A revision with any violation is rejected whole. */
final readonly class Violation
{
    public function __construct(public string $rule, public ClaimId $claim, public string $detail)
    {
    }
}
