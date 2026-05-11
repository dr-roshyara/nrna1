<?php

declare(strict_types=1);

namespace App\Domain\Voting\ValueObject;

final readonly class EligibilitySnapshot
{
    /** @var array<string> */
    private array $eligibleVoters;

    /**
     * @param array<string> $eligibleVoters
     */
    public function __construct(array $eligibleVoters)
    {
        if (empty($eligibleVoters)) {
            throw new \InvalidArgumentException('At least one eligible voter is required');
        }

        // Normalize: sort for deterministic ordering
        $sorted = $eligibleVoters;
        sort($sorted);
        $this->eligibleVoters = array_values($sorted);
    }

    /**
     * @return array<string>
     */
    public function eligibleVoters(): array
    {
        return $this->eligibleVoters;
    }

    public function isEligible(string $voterId): bool
    {
        return in_array($voterId, $this->eligibleVoters, true);
    }

    public function count(): int
    {
        return count($this->eligibleVoters);
    }
}
