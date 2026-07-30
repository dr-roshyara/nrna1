<?php

declare(strict_types=1);

namespace Tests\Support\Adjudication;

use App\Contexts\Adjudication\Application\Port\AdjudicationProcessStore;
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

    public function activeForChallenge(ChallengeRef $challenge): ?AdjudicationProcessState
    {
        $existing = $this->active[$challenge->toString()] ?? null;

        return $existing !== null && !$existing->status()->isTerminal() ? $existing : null;
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
