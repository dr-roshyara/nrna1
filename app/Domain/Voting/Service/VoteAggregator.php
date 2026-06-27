<?php

declare(strict_types=1);

namespace App\Domain\Voting\Service;

use App\Domain\Voting\ValueObject\BallotCollection;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;

final class VoteAggregator
{
    /**
     * Aggregate votes: filter eligible, normalize, deduplicate deterministically
     *
     * @return array<array{voter: string, selection: string}>
     */
    public function aggregate(
        BallotCollection $ballots,
        EligibilitySnapshot $eligibility
    ): array {
        // Step 1: Filter by eligible voters
        $filtered = $ballots->filterByEligible($eligibility);

        // Step 2: Deduplicate by voter (keep last vote, deterministically)
        $deduplicated = [];
        foreach ($filtered->ballots() as $ballot) {
            $deduplicated[$ballot['voter']] = $ballot;
        }

        // Step 3: Normalize (sort by voter ID for deterministic ordering)
        $normalized = array_values($deduplicated);
        usort($normalized, fn($a, $b) => strcmp($a['voter'], $b['voter']));

        return $normalized;
    }
}
