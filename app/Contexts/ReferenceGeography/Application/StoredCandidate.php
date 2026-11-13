<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

use App\Contexts\ReferenceGeography\Domain\Candidate;

/** A candidate as loaded, with the row version a save must match. */
final readonly class StoredCandidate
{
    public function __construct(public Candidate $candidate, public int $rowVersion)
    {
    }
}
