<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\BallotAuthorizationProtocol;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\VotingTrustResult;

final class TrustSnapshotAssembler
{
    // Translates VotingTrustResult + context into immutable projection
    // Invariant 6: Returns readonly ConstitutionalTrustSnapshot — no calculation methods inside

    public function assemble(
        VotingTrustResult      $result,
        TrustCapabilityContext $ctx,
        ?ConstitutionalObservationContext $OverlaySignalCategory = null,
    ): ConstitutionalTrustSnapshot
    {
        // Derive protocol from election's ballot_authorization_protocol string
        $protocolString = $ctx->election?->ballot_authorization_protocol ?? 'single_code';
        $protocol = BallotAuthorizationProtocol::tryFrom($protocolString)
            ?? BallotAuthorizationProtocol::UnifiedTokenProtocol;

        // Determine active overlay (first non-stable signal indicates influence)
        $activeOverlay = null;
        $overlayInfluenceValue = null;
        if ($OverlaySignalCategory && count($OverlaySignalCategory->observations) > 0) {
            foreach ($OverlaySignalCategory->observations as $signal) {
                // Non-stable signals (EVIDENCE_INCONSISTENT, ADDITIONAL_ATTESTATION_PRESENT) indicate overlay influence
                if ($signal->signalType !== 'CONTEXT_STABLE') {
                    $activeOverlay = $signal->overlayIdentifier;
                    $overlayInfluenceValue = $signal->signalType;
                    break;
                }
            }
        }

        return new ConstitutionalTrustSnapshot(
            trusted: $result->evaluationState === TrustEvaluationState::SUFFICIENT_EVIDENCE,
            trustLevel: $result->trustLevel,
            authorizationProtocol: $protocol->value,
            requiresViewToken: $protocol->requiresViewToken(),
            requiresSeparateCommit: $protocol->requiresSeparateCommit(),
            attestationValid: $ctx->attestation->isSatisfied(),
            attestationSource: $ctx->attestation->registrarId ? 'registrar' : ($ctx->attestation->isSatisfied() ? 'session' : 'none'),
            continuityPreserved: $ctx->continuityPreserved(),
            activeOverlay: $activeOverlay,
            OverlaySignalCategory: $overlayInfluenceValue,
            denialReason: $result->reason,
            trustProvenance: $result->policyOutcomeSequence,
        );
    }
}
