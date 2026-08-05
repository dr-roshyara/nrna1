<?php

declare(strict_types=1);

namespace App\Contexts\Contestation\Domain\Repository;

use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Exception\ChallengeNotFound;

/**
 * Repository port for the Challenge aggregate (ADR-T6).
 *
 * Interface lives in the Domain; the Eloquent implementation lives in
 * Infrastructure (EloquentChallengeRepository). Speaks the domain language,
 * accepts/returns the whole aggregate root only — never child rows, never a
 * projection.
 */
interface ChallengeRepository
{
    /**
     * Generate a new identity for a Challenge. The generation strategy (UUID,
     * sequence, …) is the repository's concern — callers must not assume it.
     */
    public function nextIdentity(): ChallengeId;

    /**
     * Persist the aggregate (insert or update) under optimistic concurrency
     * (AggregateVersion). A version mismatch must fail loudly, never overwrite.
     *
     * The Application Service owns the transaction boundary; the repository
     * PARTICIPATES in that transaction but does not begin/commit it. Events
     * pulled from the aggregate are enqueued to the outbox within the same
     * transaction by the Application Service (ADR-T1/T3).
     */
    public function save(Challenge $challenge): void;

    /**
     * Load the aggregate by identity.
     *
     * @throws ChallengeNotFound if the Challenge does not exist
     */
    public function get(ChallengeId $id): Challenge;

    /**
     * Find the aggregate by identity, or null if it does not exist.
     * Use get() when existence is required.
     */
    public function find(ChallengeId $id): ?Challenge;

    /**
     * Correlate to the Challenge that was adjudicated with the given determination, or
     * null if none is (used by the resolution reaction, since `ElectionCorrectionApplied`
     * carries no challengeId).
     *
     * This is a CORRELATION capability only — NOT an alternative aggregate identity. The
     * Challenge's identity remains `ChallengeId`; `DeterminationId` is merely a lookup
     * index (a Challenge holds exactly one determination once adjudicated).
     */
    public function findByDeterminationId(DeterminationId $id): ?Challenge;
}
