<?php

declare(strict_types=1);

namespace App\Contexts\ReferenceGeography\Application\Ports;

use App\Contexts\ReferenceGeography\Application\CandidateChangedConcurrently;
use App\Contexts\ReferenceGeography\Application\StoredCandidate;
use App\Contexts\ReferenceGeography\Domain\Candidate;
use App\Contexts\ReferenceGeography\Domain\CandidateId;

/** Candidates (G-6a): operational records, outside the knowledge stream (v3.11 §15). */
interface CandidateRepository
{
    /** @throws \RuntimeException if a candidate with this id exists */
    public function add(Candidate $candidate): void;

    /** @throws \OutOfBoundsException if there is none */
    public function get(CandidateId $id): StoredCandidate;

    /**
     * @return int the new row version
     * @throws CandidateChangedConcurrently if the row changed since it was loaded
     */
    public function save(Candidate $candidate, int $expectedRowVersion): int;
}
