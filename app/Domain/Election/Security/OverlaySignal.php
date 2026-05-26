<?php

namespace App\Domain\Election\Security;

readonly class OverlaySignal
{
    public function __construct(
        public OverlayInfluence $influence,
        public string $overlayIdentifier,
        public string $constitutionalBasis,
        public array $evidenceContext,           // NO raw PII — hashed/minimized only
        public ?string $requiredReviewRole,
        public ?TrustLevel $suggestedElevatedTrustLevel, // ONLY for TRUST_ELEVATION_REQUEST
    ) {}

    public function requiresConstitutionalReview(): bool
    {
        return $this->influence === OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW;
    }

    public static function continue(string $overlayIdentifier): self
    {
        return new self(
            OverlayInfluence::CONTINUE_UNCHANGED,
            $overlayIdentifier,
            'no_condition_triggered',
            [],
            null,
            null,
        );
    }
}
