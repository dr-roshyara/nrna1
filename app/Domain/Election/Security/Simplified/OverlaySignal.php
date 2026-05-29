<?php

namespace App\Domain\Election\Security\Simplified;

readonly class OverlaySignal
{
    public function __construct(
        public string $signalType,
        public string $overlayIdentifier,
        public string $constitutionalBasis,
        public array $evidenceContext,
    ) {}

    public static function contextStable(string $overlayId, string $observationContext, array $evidence): self
    {
        return new self(
            signalType: 'CONTEXT_STABLE',
            overlayIdentifier: $overlayId,
            constitutionalBasis: $observationContext,
            evidenceContext: $evidence,
        );
    }

    public static function evidenceInconsistent(string $overlayId, string $reason, array $evidence): self
    {
        return new self(
            signalType: 'EVIDENCE_INCONSISTENT',
            overlayIdentifier: $overlayId,
            constitutionalBasis: $reason,
            evidenceContext: $evidence,
        );
    }

    public static function attestationPresent(string $overlayId, string $basis, array $evidence): self
    {
        return new self(
            signalType: 'ADDITIONAL_ATTESTATION_PRESENT',
            overlayIdentifier: $overlayId,
            constitutionalBasis: $basis,
            evidenceContext: $evidence,
        );
    }
}
