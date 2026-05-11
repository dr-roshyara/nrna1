<?php

declare(strict_types=1);

namespace App\Domain\Voting\ValueObject;

final readonly class VotingOutcome
{
    /** @var array<string, int> */
    private array $results;

    /** @var array<array{voter: string, selection: string}> */
    private array $validBallots;

    /**
     * @param array<array{voter: string, selection: string}> $validBallots
     */
    public function __construct(array $validBallots)
    {
        $this->validBallots = $validBallots;

        // Compute results deterministically
        $results = [];
        foreach ($validBallots as $ballot) {
            $selection = $ballot['selection'];
            $results[$selection] = ($results[$selection] ?? 0) + 1;
        }

        // Sort by selection name for deterministic ordering
        ksort($results);
        $this->results = $results;
    }

    /**
     * @return array<array{voter: string, selection: string}>
     */
    public function validBallots(): array
    {
        return $this->validBallots;
    }

    /**
     * @return array<string, int>
     */
    public function results(): array
    {
        return $this->results;
    }

    public function countFor(string $selection): int
    {
        return $this->results[$selection] ?? 0;
    }

    public function winner(): ?string
    {
        if (empty($this->results)) {
            return null;
        }

        return array_key_first($this->results) ??
               array_keys($this->results)[0];
    }

    public function totalVotes(): int
    {
        return count($this->validBallots);
    }
}
