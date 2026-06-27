<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Domain\Repository;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Exception\DeterminationNotFound;

/**
 * Repository port for the Determination aggregate (ADR-T6). Interface in Domain;
 * Eloquent implementation in Infrastructure. Returns/accepts whole aggregates.
 *
 * `findByChallengeRef` exposes a persistence capability only — it embeds NO
 * business intent. The application service decides whether the presence of a
 * determination means "already issued". Logical uniqueness (one determination
 * per challenge) is enforced in the service; infrastructure later reinforces it
 * with a UNIQUE(challenge_ref) constraint to close the race window.
 */
interface DeterminationRepository
{
    public function nextIdentity(): DeterminationId;

    /**
     * Persist under optimistic concurrency; the Application service owns the
     * transaction boundary, the repository participates (ADR-T1/T3).
     */
    public function save(Determination $determination): void;

    /** @throws DeterminationNotFound */
    public function get(DeterminationId $id): Determination;

    public function find(DeterminationId $id): ?Determination;

    public function findByChallengeRef(ChallengeRef $challengeRef): ?Determination;
}
