<?php

namespace App\Domain\Election\Security;

readonly class OverlayInfluenceContext
{
    public function __construct(
        public array $signals,             // OverlaySignal[]
        public bool $hasInfluence,        // true if any non-CONTINUE signals present
        public bool $requiresReview,      // true if any REQUIRE_CONSTITUTIONAL_REVIEW present
        public bool $requiresReverification, // true if any REQUIRE_RE_VERIFICATION present
        public bool $isInconclusive,      // true if any TRUST_EVALUATION_INCONCLUSIVE present
        public ?TrustLevel $elevationRequest, // highest suggested elevation (for Resolver to accept/reject)
    ) {}

    public static function noInfluence(): self
    {
        return new self(
            signals: [],
            hasInfluence: false,
            requiresReview: false,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: null,
        );
    }
}
