<?php

declare(strict_types=1);

namespace Tests\Support\Adjudication;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessId;
use App\Contexts\Adjudication\Application\Process\AdjudicationProcessState;
use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use DateTimeImmutable;

/**
 * In-memory double for the APM's process store (WP-2 unit tests).
 *
 * The store is orchestration infrastructure, NOT a domain repository
 * (EPIC-004K §11 · RMSP by analogy) — its surface is exactly what the process
 * needs: load the active process for reaction, write it (create-on-open /
 * append / record-conclusion as ONE write per PM-5), and answer the horizon's
 * due query. No query zoo.
 *
 * Records every write so tests can assert "exactly one conclusion".
 */
final class InMemoryAdjudicationProcessStore implements AdjudicationProcessStore
{
    /** @var array<string, AdjudicationProcessState> keyed by challenge ref */
    private array $active = [];

    /** @var list<AdjudicationProcessState> every write, in order */
    public array $writes = [];

    private int $minted = 0;

    public function nextIdentity(): AdjudicationProcessId
    {
        return AdjudicationProcessId::fromString('apm-' . ++$this->minted);
    }

    public function activeForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState
    {
        $existing = $this->active[$challenge->toString()] ?? null;

        return $existing !== null && !$existing->status()->isTerminal() ? $existing : null;
    }

    /**
     * The LATEST process for a challenge, terminal or not — deliberately unfiltered,
     * unlike `activeForChallenge()`. WP-6's late-decision guard needs to distinguish a
     * redelivered decision on a concluded process (idempotent no-op, ADR-T3) from a late
     * decision on an EXPIRED one (a conflict); both are invisible to the active query.
     *
     * Added 2026-08-01 as a mechanical repair: WP-6 GREEN added this method to the port
     * and to the production adapter but not to this double, which left the class
     * abstract and fatal-errored the whole unit test from 2026-07-31 onward.
     */
    public function latestForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState
    {
        return $this->active[$challenge->toString()] ?? null;
    }

    public function save(AdjudicationProcessState $state): void
    {
        $this->active[$state->challengeRef()->toString()] = $state;
        $this->writes[] = $state;
    }

    /** @return list<AdjudicationProcessState> */
    public function dueForHorizon(DateTimeImmutable $asOf): array
    {
        return array_values(array_filter(
            $this->active,
            static fn (AdjudicationProcessState $s): bool => !$s->status()->isTerminal(),
        ));
    }
}
