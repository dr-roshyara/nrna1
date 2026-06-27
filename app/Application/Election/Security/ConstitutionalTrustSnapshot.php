<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\TrustLevel;

readonly class ConstitutionalTrustSnapshot
{
    // Immutable projection of VotingTrustResult for presentation/decision
    // Invariant 6: No recalculate(), no grantVotingAccess(), no authority methods
    // This is projection-only — no behavior beyond data holding

    public function __construct(
        public bool       $trusted,
        public TrustLevel $trustLevel,
        public string     $authorizationProtocol,    // from BallotAuthorizationProtocol enum value
        public bool       $requiresViewToken,
        public bool       $requiresSeparateCommit,
        public bool       $attestationValid,
        public string     $attestationSource,        // 'registrar'|'session'|'none'
        public bool       $continuityPreserved,
        public ?string    $activeOverlay,
        public ?string    $OverlaySignalCategory,          // 'elevated'|'denied'|null
        public string     $denialReason,
        public array      $trustProvenance,           // causality lineage: policy outcomes only
    ) {}
}
