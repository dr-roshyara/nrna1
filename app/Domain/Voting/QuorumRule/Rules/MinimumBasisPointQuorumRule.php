<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule\Rules;

use App\Domain\Voting\QuorumRule\QuorumRule;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;

/**
 * MinimumBasisPointQuorumRule — Percentage-Based Threshold (Integer Arithmetic)
 *
 * Quorum is met when participation reaches a percentage threshold.
 * Uses integer basis points (10000 = 100%) to eliminate floating-point drift.
 *
 * This is crucial for replay safety: governance systems cannot tolerate float precision issues.
 *
 * Arithmetic: required = ceil(basisPoints × eligible ÷ 10000)
 * All operations are integer-only (no division, only multiply + ceil).
 */
final readonly class MinimumBasisPointQuorumRule implements QuorumRule
{
    public function __construct(private int $basisPoints)
    {
        if ($this->basisPoints <= 0 || $this->basisPoints > 10000) {
            throw new \InvalidArgumentException(
                'Basis points must be between 1 and 10000 (0.01% to 100%)'
            );
        }
    }

    public function evaluate(EligibilitySnapshot $eligibility, int $actualParticipation): bool
    {
        if ($eligibility->count() === 0) {
            return false;
        }

        // Integer-only arithmetic: required = ceil(basisPoints * eligible / 10000)
        // Using: ceil(a/b) = (a + b - 1) / b in integer arithmetic
        $required = (int) ceil(($this->basisPoints * $eligibility->count()) / 10000);
        return $actualParticipation >= $required;
    }

    public function description(): string
    {
        $percent = ($this->basisPoints / 10000) * 100;
        return "Minimum {$percent}% participation ({$this->basisPoints} basis points)";
    }

    /**
     * Stable node-level identity hash.
     *
     * Includes schema_version for replay safety.
     * Two instances with same basis points produce identical identity.
     */
    public function identity(): string
    {
        return hash('sha256', json_encode([
            'type'              => 'MinimumBasisPointQuorumRule',
            'schema_version'    => '1.0',
            'normalization'     => 'atom',
            'basisPoints'       => $this->basisPoints,
        ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }

    /**
     * Atomic rules are already canonical; return self.
     */
    public function normalize(): self
    {
        return $this;
    }
}
