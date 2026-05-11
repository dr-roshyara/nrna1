<?php

declare(strict_types=1);

namespace App\Domain\Voting\Service;

use App\Domain\Voting\ValueObject\EligibilitySnapshot;

final class EligibilityEvaluator
{
    /**
     * Evaluate eligible voters from snapshot (deterministic, read-only operation)
     *
     * @return array<string>
     */
    public function evaluate(EligibilitySnapshot $snapshot): array
    {
        return $snapshot->eligibleVoters();
    }
}
