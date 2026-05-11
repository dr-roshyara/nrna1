<?php

declare(strict_types=1);

namespace App\Domain\Voting\ValueObject;

final readonly class BallotCollection
{
    /** @var array<array{voter: string, selection: string}> */
    private array $ballots;

    /**
     * @param array<array{voter: string, selection: string}> $ballots
     */
    public function __construct(array $ballots)
    {
        // Normalize: sort deterministically by voter ID
        $normalized = $ballots;
        usort($normalized, fn($a, $b) => strcmp($a['voter'], $b['voter']));

        $this->ballots = array_values($normalized);
    }

    /**
     * @return array<array{voter: string, selection: string}>
     */
    public function ballots(): array
    {
        return $this->ballots;
    }

    /**
     * Filter ballots by eligible voters
     */
    public function filterByEligible(EligibilitySnapshot $eligibility): BallotCollection
    {
        $filtered = array_filter(
            $this->ballots,
            fn($ballot) => $eligibility->isEligible($ballot['voter'])
        );

        return new BallotCollection(array_values($filtered));
    }

    public function count(): int
    {
        return count($this->ballots);
    }

    public function isEmpty(): bool
    {
        return count($this->ballots) === 0;
    }
}
