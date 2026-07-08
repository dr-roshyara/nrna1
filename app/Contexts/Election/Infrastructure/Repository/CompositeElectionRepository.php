<?php

declare(strict_types=1);

namespace App\Contexts\Election\Infrastructure\Repository;

use App\Contexts\Election\Application\Port\AppliedDeterminationStore;
use App\Contexts\Election\Application\Port\ElectionExistencePort;
use App\Contexts\Election\Domain\Election;
use App\Contexts\Election\Domain\ElectionId;
use App\Contexts\Election\Domain\Repository\ElectionRepository;

/**
 * Realises the Election Domain Port `ElectionRepository` by reconstructing the aggregate
 * from TWO independent persistence sources (Aggregate Reconstruction Invariant):
 *   1. existence — the {@see ElectionExistencePort} (legacy today, behind the ACL), and
 *   2. reaction state — the greenfield {@see AppliedDeterminationStore} (idempotency set).
 *
 * `Election ≠ legacy row`; the aggregate = legacy existence + greenfield reaction state.
 * The domain never learns where existence comes from, nor that tenancy exists.
 *
 * Failure classification: `existence->exists()` returning false is *business absence*
 * (→ null → unknown election). An infrastructure failure while determining existence is
 * NOT converted to false here — the exception propagates (transient; retried by the
 * inbox relay), so it is never mistaken for a permanent business absence.
 */
final class CompositeElectionRepository implements ElectionRepository
{
    public function __construct(
        private readonly ElectionExistencePort $existence,
        private readonly AppliedDeterminationStore $corrections,
    ) {
    }

    public function find(ElectionId $id): ?Election
    {
        if (!$this->existence->exists($id)) {
            return null;
        }

        return Election::reconstitute($id, $this->corrections->appliedDeterminations($id));
    }

    public function save(Election $election): void
    {
        $this->corrections->remember($election->id(), ...$election->appliedDeterminationIds());
    }
}
