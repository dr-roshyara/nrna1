<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\BallotAuthorizationProtocol;
use App\Domain\Election\Security\OverlaySignalCategoryContext;
use App\Domain\Election\Security\TrustEvaluationState;
use App\Domain\Election\Security\VotingTrustResult;

/**
 * SnapshotAssembler
 *
 * Translates VotingTrustResult + context into immutable ConstitutionalTrustSnapshot.
 * Renamed from TrustSnapshotAssembler per C10.
 *
 * INVARIANT: Returns readonly ConstitutionalTrustSnapshot — no calculation methods inside
 */
final class SnapshotAssembler
{
    public function assemble(
        VotingTrustResult      $result,
        EvidenceContext $ctx,
        ?OverlaySignalCategoryContext $OverlaySignalCategory = null,
    ): ConstitutionalTrustSnapshot
    {
        // Derive protocol from election's ballot_authorization_protocol string
        $protocolString = $ctx->election?->ballot_authorization_protocol ?? 'single_code';
        $protocol = BallotAuthorizationProtocol::tryFrom($protocolString)
            ?? BallotAuthorizationProtocol::UnifiedTokenProtocol;

        // Determine active overlay (first non-continue signal)
        $activeOverlay = null;
        $overlayInfluenceValue = null;
        if ($OverlaySignalCategory && count($OverlaySignalCategory->signals) > 0) {
            foreach ($OverlaySignalCategory->signals as $signal) {
                if ($signal->proceduralPath->value !== 'continue') {
                    $activeOverlay = $signal->overlayIdentifier;
                    $overlayInfluenceValue = $signal->proceduralPath->value;
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
