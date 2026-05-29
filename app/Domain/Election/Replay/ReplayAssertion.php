<?php

namespace App\Domain\Election\Replay;

/**
 * ReplayAssertion
 *
 * A deterministic contract: "this evidence set produces this outcome."
 * The assertion binds a specific evidence envelope to a specific outcome,
 * enabling replay certification to verify that the same input still
 * produces the same output.
 *
 * INVARIANT:
 * An assertion once created is immutable. If the evaluation pipeline
 * changes, old assertions are invalidated — they do NOT mutate.
 */
readonly class ReplayAssertion
{
    public string $assertionHash;

    public function __construct(
        public ReplayEvidenceEnvelope $envelope,
        public string                 $expectedOutcome,   // The outcome at original evaluation time
        public string                 $policySequenceHash, // Hash of policy resolver at evaluation time
        public \DateTimeImmutable     $assertedAt,
    ) {
        $this->assertionHash = $this->computeHash();
    }

    /**
     * Deterministic hash binding envelope + outcome + policy version.
     */
    private function computeHash(): string
    {
        return hash('sha256', implode('|', [
            $this->envelope->envelopeHash,
            $this->expectedOutcome,
            $this->policySequenceHash,
            (string)$this->assertedAt->getTimestamp(),
        ]));
    }

    /**
     * Verify the assertion against a current outcome.
     * Returns true if the current outcome matches the expected outcome.
     */
    public function verify(string $currentOutcome): bool
    {
        return $this->expectedOutcome === $currentOutcome;
    }
}
