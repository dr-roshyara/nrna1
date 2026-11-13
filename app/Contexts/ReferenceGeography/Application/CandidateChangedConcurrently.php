<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application;

/** The candidate changed since it was loaded; nothing was saved. Load it again and decide again. */
final class CandidateChangedConcurrently extends \RuntimeException
{
    public function __construct(string $candidateId)
    {
        parent::__construct("Candidate {$candidateId} changed concurrently.");
    }
}
