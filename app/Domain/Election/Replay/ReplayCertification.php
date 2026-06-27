<?php

namespace App\Domain\Election\Replay;

/**
 * ReplayCertification
 *
 * Immutable result of a replay certification cycle.
 * Certifies whether the replay outcome matched the original outcome.
 *
 * INVARIANTS:
 * 1. Once created, certification is immutable
 * 2. Certification must carry the compatibility version at certification time
 * 3. Certification must reference the evidence envelope hash
 * 4. A matched certification confirms the replay determinism invariant
 */
readonly class ReplayCertification
{
    public string $certificationHash;

    public function __construct(
        public string                    $sessionId,
        public string                    $envelopeHash,
        public string                    $expectedOutcome,
        public string                    $actualOutcome,
        public bool                      $matched,
        public \DateTimeImmutable        $certifiedAt,
        public ReplayCompatibilityVersion $compatibilityVersion,
    ) {
        $this->certificationHash = $this->computeHash();
    }

    /**
     * Deterministic certification hash for audit trail.
     */
    private function computeHash(): string
    {
        return hash('sha256', implode('|', [
            $this->sessionId,
            $this->envelopeHash,
            $this->expectedOutcome,
            $this->actualOutcome,
            $this->matched ? '1' : '0',
            (string)$this->certifiedAt->getTimestamp(),
            $this->compatibilityVersion->toString(),
        ]));
    }

    /**
     * Factory: successful certification.
     */
    public static function matched(
        string                     $sessionId,
        string                     $envelopeHash,
        string                     $outcome,
        ReplayCompatibilityVersion $version,
    ): self {
        return new self(
            sessionId: $sessionId,
            envelopeHash: $envelopeHash,
            expectedOutcome: $outcome,
            actualOutcome: $outcome,
            matched: true,
            certifiedAt: new \DateTimeImmutable(),
            compatibilityVersion: $version,
        );
    }

    /**
     * Factory: divergence detected.
     */
    public static function diverged(
        string                     $sessionId,
        string                     $envelopeHash,
        string                     $expectedOutcome,
        string                     $actualOutcome,
        ReplayCompatibilityVersion $version,
    ): self {
        return new self(
            sessionId: $sessionId,
            envelopeHash: $envelopeHash,
            expectedOutcome: $expectedOutcome,
            actualOutcome: $actualOutcome,
            matched: false,
            certifiedAt: new \DateTimeImmutable(),
            compatibilityVersion: $version,
        );
    }
}
