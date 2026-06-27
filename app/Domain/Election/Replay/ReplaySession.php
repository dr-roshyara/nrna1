<?php

namespace App\Domain\Election\Replay;

/**
 * ReplaySession
 *
 * Root entity of the ReplayAggregate. A session represents a single
 * replay certification cycle — from evidence sealing through to
 * certification or divergence detection.
 *
 * LIFECYCLE:
 *   Created → Evidence Sealed → Replayed → Certified (or Divergence Detected)
 *
 * INVARIANTS:
 * 1. A session can be used for exactly one certification cycle
 * 2. Evidence is sealed at session creation — no mutation
 * 3. Certification produces an immutable outcome
 * 4. Same evidence → identical certification output across runtimes
 */
final class ReplaySession
{
    private string $sessionId;
    private ReplayEvidenceEnvelope $envelope;
    private ?ReplayAssertion $assertion;
    private ?ReplayCertification $certification;
    private \DateTimeImmutable $createdAt;
    private string $state;  // 'sealed', 'replayed', 'certified', 'diverged'

    public function __construct(
        ReplayEvidenceEnvelope $envelope,
    ) {
        $this->sessionId = self::generateSessionId($envelope);
        $this->envelope = $envelope;
        $this->assertion = null;
        $this->certification = null;
        $this->createdAt = new \DateTimeImmutable();
        $this->state = 'sealed';
    }

    public function sessionId(): string { return $this->sessionId; }
    public function envelope(): ReplayEvidenceEnvelope { return $this->envelope; }
    public function assertion(): ?ReplayAssertion { return $this->assertion; }
    public function certification(): ?ReplayCertification { return $this->certification; }
    public function createdAt(): \DateTimeImmutable { return $this->createdAt; }
    public function state(): string { return $this->state; }

    /**
     * Deterministic session ID from envelope identity.
     */
    private static function generateSessionId(ReplayEvidenceEnvelope $envelope): string
    {
        return hash('sha256', 'replay_session|' . $envelope->envelopeHash);
    }

    /**
     * Record an assertion: bind the sealed evidence to the expected outcome.
     * Transitions state to 'replayed'.
     */
    public function recordAssertion(
        string $expectedOutcome,
        string $policySequenceHash,
    ): ReplayAssertion {
        if ($this->state !== 'sealed') {
            throw new \RuntimeException(
                "Cannot record assertion in state '{$this->state}'. Expected 'sealed'."
            );
        }

        $this->assertion = new ReplayAssertion(
            envelope: $this->envelope,
            expectedOutcome: $expectedOutcome,
            policySequenceHash: $policySequenceHash,
            assertedAt: new \DateTimeImmutable(),
        );
        $this->state = 'replayed';

        return $this->assertion;
    }

    /**
     * Certify the replay: compare current outcome to expected outcome.
     * Returns ReplayCertification and transitions state.
     */
    public function certify(string $currentOutcome): ReplayCertification
    {
        if ($this->state !== 'replayed') {
            throw new \RuntimeException(
                "Cannot certify in state '{$this->state}'. Expected 'replayed'."
            );
        }

        if ($this->assertion === null) {
            throw new \RuntimeException('Cannot certify without assertion.');
        }

        $matched = $this->assertion->verify($currentOutcome);

        $this->certification = new ReplayCertification(
            sessionId: $this->sessionId,
            envelopeHash: $this->envelope->envelopeHash,
            expectedOutcome: $this->assertion->expectedOutcome,
            actualOutcome: $currentOutcome,
            matched: $matched,
            certifiedAt: new \DateTimeImmutable(),
            compatibilityVersion: $this->envelope->compatibility,
        );

        $this->state = $matched ? 'certified' : 'diverged';

        return $this->certification;
    }

    /**
     * Check whether the session completed with matched outcomes.
     */
    public function isCertified(): bool
    {
        return $this->state === 'certified';
    }

    /**
     * Check whether the session detected divergence.
     */
    public function hasDiverged(): bool
    {
        return $this->state === 'diverged';
    }
}
