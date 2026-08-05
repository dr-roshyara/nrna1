<?php

declare(strict_types=1);

namespace Tests\Support\Contestation;

use App\Contexts\Contestation\Domain\Challenge\Challenge;
use App\Contexts\Contestation\Domain\Challenge\ChallengeId;
use App\Contexts\Contestation\Domain\Challenge\DeterminationId;
use App\Contexts\Contestation\Domain\Repository\ChallengeRepository;

/**
 * In-memory ChallengeRepository for unit tests. `findByDeterminationId` is a CORRELATION
 * capability only (ARB invariant) — aggregate identity remains `ChallengeId`; the
 * determination is merely a lookup index populated once a Challenge is adjudicated.
 */
final class InMemoryChallengeRepository implements ChallengeRepository
{
    /** @var array<string, Challenge> */
    private array $byId = [];

    public function __construct(Challenge ...$seed)
    {
        foreach ($seed as $challenge) {
            $this->byId[$challenge->id()->toString()] = $challenge;
        }
    }

    public function nextIdentity(): ChallengeId
    {
        return ChallengeId::fromString('ch-generated');
    }

    public function save(Challenge $challenge): void
    {
        $this->byId[$challenge->id()->toString()] = $challenge;
    }

    public function get(ChallengeId $id): Challenge
    {
        return $this->byId[$id->toString()];
    }

    public function find(ChallengeId $id): ?Challenge
    {
        return $this->byId[$id->toString()] ?? null;
    }

    public function findByDeterminationId(DeterminationId $id): ?Challenge
    {
        foreach ($this->byId as $challenge) {
            $determination = $challenge->adjudicatedDeterminationId();
            if ($determination !== null && $determination->toString() === $id->toString()) {
                return $challenge;
            }
        }

        return null;
    }

    public function saved(string $id): Challenge
    {
        return $this->byId[$id];
    }
}
