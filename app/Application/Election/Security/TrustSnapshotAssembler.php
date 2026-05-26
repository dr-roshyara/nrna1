<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\BallotAuthorizationProtocol;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\VotingTrustResult;

final class TrustSnapshotAssembler
{
    // Translates VotingTrustResult + context into immutable projection
    // Invariant 6: Returns readonly ConstitutionalTrustSnapshot — no calculation methods inside

    public function assemble(
        VotingTrustResult      $result,
        TrustCapabilityContext $ctx,
        ?OverlayInfluenceContext $overlayInfluence = null,
    ): ConstitutionalTrustSnapshot
    {
        // Derive protocol from election's ballot_authorization_protocol string
        $protocolString = $ctx->election?->ballot_authorization_protocol ?? 'single_code';
        $protocol = BallotAuthorizationProtocol::tryFrom($protocolString)
            ?? BallotAuthorizationProtocol::UnifiedTokenProtocol;

        // Determine active overlay (first non-continue signal)
        $activeOverlay = null;
        $overlayInfluenceValue = null;
        if ($overlayInfluence && count($overlayInfluence->signals) > 0) {
            foreach ($overlayInfluence->signals as $signal) {
                if ($signal->influence->value !== 'continue_unchanged') {
                    $activeOverlay = $signal->overlayIdentifier;
                    $overlayInfluenceValue = $signal->influence->value;
                    break;
                }
            }
        }

        return new ConstitutionalTrustSnapshot(
            trusted: $result->trusted,
            trustLevel: $result->trustLevel,
            authorizationProtocol: $protocol->value,
            requiresViewToken: $protocol->requiresViewToken(),
            requiresSeparateCommit: $protocol->requiresSeparateCommit(),
            attestationValid: $ctx->attestation->isSatisfied(),
            attestationSource: $ctx->attestation->registrarId ? 'registrar' : ($ctx->attestation->isSatisfied() ? 'session' : 'none'),
            continuityPreserved: $ctx->continuityPreserved(),
            activeOverlay: $activeOverlay,
            overlayInfluence: $overlayInfluenceValue,
            denialReason: $result->reason,
            trustProvenance: $result->policyOutcomeSequence,
        );
    }
}
