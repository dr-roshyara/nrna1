<?php

declare(strict_types=1);

namespace App\Domain\Voting\QuorumRule\Rules;

use App\Domain\Voting\QuorumRule\QuorumRule;
use App\Domain\Voting\ValueObject\EligibilitySnapshot;

/**
 * MinimumAbsoluteQuorumRule — Atomic Threshold Rule
 *
 * Quorum is met when participation count exceeds or equals a fixed absolute threshold.
 *
 * Pure deterministic evaluation: no eligibility context needed beyond count.
 */
final readonly class MinimumAbsoluteQuorumRule implements QuorumRule
{
    public function __construct(private int $threshold)
    {
        if ($this->threshold <= 0) {
            throw new \InvalidArgumentException('Quorum threshold must be a positive integer');
        }
    }

    public function evaluate(EligibilitySnapshot $eligibility, int $actualParticipation): bool
    {
        return $actualParticipation >= $this->threshold;
    }

    public function description(): string
    {
        return "Minimum {$this->threshold} votes required";
    }

    /**
     * Stable node-level identity hash.
     *
     * Includes schema_version for replay safety.
     * Two instances with same threshold produce identical identity.
     */
    public function identity(): string
    {
        return hash('sha256', json_encode([
            'type'              => 'MinimumAbsoluteQuorumRule',
            'schema_version'    => '1.0',
            'normalization'     => 'atom',
            'threshold'         => $this->threshold,
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
