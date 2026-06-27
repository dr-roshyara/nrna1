<?php

declare(strict_types=1);

namespace Tests\Support\Adjudication;

use App\Contexts\Adjudication\Domain\Determination\ChallengeRef;
use App\Contexts\Adjudication\Domain\Determination\Determination;
use App\Contexts\Adjudication\Domain\Determination\DeterminationId;
use App\Contexts\Adjudication\Domain\Exception\DeterminationNotFound;
use App\Contexts\Adjudication\Domain\Repository\DeterminationRepository;

/** In-memory test double for DeterminationRepository (no persistence). */
final class InMemoryDeterminationRepository implements DeterminationRepository
{
    /** @var array<string, Determination> keyed by determination id */
    private array $byId = [];

    private int $counter = 0;

    public function nextIdentity(): DeterminationId
    {
        return DeterminationId::fromString('det-' . (++$this->counter));
    }

    public function save(Determination $determination): void
    {
        $this->byId[$determination->id()->toString()] = $determination;
    }

    public function get(DeterminationId $id): Determination
    {
        return $this->byId[$id->toString()] ?? throw DeterminationNotFound::withId($id);
    }

    public function find(DeterminationId $id): ?Determination
    {
        return $this->byId[$id->toString()] ?? null;
    }

    public function findByChallengeRef(ChallengeRef $challengeRef): ?Determination
    {
        foreach ($this->byId as $determination) {
            if ($determination->challengeRef()->toString() === $challengeRef->toString()) {
                return $determination;
            }
        }

        return null;
    }

    public function count(): int
    {
        return count($this->byId);
    }
}
