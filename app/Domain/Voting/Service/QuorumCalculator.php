<?php

declare(strict_types=1);

namespace App\Domain\Voting\Service;

use App\Domain\Voting\ValueObject\QuorumDefinition;

final class QuorumCalculator
{
    /**
     * Evaluate whether quorum has been met (deterministic comparison)
     *
     * @param array<array{voter: string, selection: string}> $validVotes
     */
    public function evaluate(array $validVotes, QuorumDefinition $quorum): bool
    {
        return $quorum->isMet(count($validVotes));
    }
}
