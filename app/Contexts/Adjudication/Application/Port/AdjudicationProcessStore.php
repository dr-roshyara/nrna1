<?php

declare(strict_types=1);

namespace App\Contexts\Adjudication\Application\Port;

use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use DateTimeImmutable;

/**
 * Durable store for adjudication-process state.
 *
 * NOT a domain repository (EPIC-004K §11): the process is orchestration, not an
 * aggregate (Candidate-2 ruling), so RMSP's repository rules apply only by
 * analogy — the surface stays exactly as large as the conduct requires and no
 * larger. Reads for humans go through read models (house CQRS-light); there is
 * deliberately no query zoo here.
 *
 * The four methods cover §11's five named operations: identity minting and
 * create-on-open/append/record-conclusion collapse into `save()` because the
 * state is immutable and PM-5 requires the conclusion to commit as ONE write.
 *
 * Traceability: EPIC-004K §11 · roadmap §WP-2 · RMSP · `DeterminationRepository`
 * precedent for `nextIdentity()`.
 */
interface AdjudicationProcessStore
{
    /** Mint the next process identity (the store owns identity, as for aggregates). */
    public function nextIdentity(): AdjudicationProcessId;

    /**
     * The ACTIVE process for this challenge, or null.
     *
     * "Active" is load-bearing: PM-1 opens "exactly one **active** process per
     * challenge", so a terminal process must never be returned here (a challenge
     * may accumulate processes over time, but never two at once).
     */
    public function activeForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState;

    /** Persist the process — create-on-open, appended admissions, or the conclusion (one write). */
    /**
     * The process for this challenge REGARDLESS of status — including terminal ones.
     *
     * Deliberately distinct from {@see activeForChallenge()}, which answers "is one
     * running?". EPIC-004K §197 requires distinguishing a **redelivered** decision on a
     * concluded process (an idempotent no-op, ADR-T3) from a **late** decision on an
     * EXPIRED one (a conflict). Both are invisible to `activeForChallenge()`, so the
     * conduct genuinely requires this second question — it is not a query zoo.
     */
    public function latestForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState;

    public function save(AdjudicationProcessState $state): void;

    /**
     * Non-terminal processes whose adjudication horizon has elapsed as of $asOf.
     *
     * The horizon's DURATION is Q-2's business policy (bootstrap: MAD 60 days);
     * this store only answers which processes are due. Timer execution is WP-6.
     *
     * @return list<AdjudicationProcessState>
     */
    public function dueForHorizon(DateTimeImmutable $asOf): array;
}
