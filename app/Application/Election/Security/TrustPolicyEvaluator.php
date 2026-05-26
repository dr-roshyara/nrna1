<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use App\Models\User;

final class TrustPolicyEvaluator
{
    // Thin orchestration: hashes evidence → builds context → runs overlay/policies/recorder
    // Invariant 3: Returns VotingTrustResult only (never can_vote, capability arrays, authority decisions)

    public function __construct(
        private OverlayCoordinator                  $overlayCoordinator,
        private PolicySequence                      $policySequence,
        private SecurityEventRecorder               $eventRecorder,
        private TrustEvidencePrivacyPolicy         $privacyPolicy,
    ) {}

    public function evaluate(
        ?Election $election,
        ?User     $user,
        string    $rawIp,
        ?string   $rawFingerprint,
        string    $sessionId,
    ): VotingTrustResult
    {
        // Step 1: Hash evidence (raw IP never reaches any other method)
        $ipHash = $this->privacyPolicy->hashIp($rawIp, $election?->id ?? 'demo');
        $fpHash = $rawFingerprint ? $this->privacyPolicy->hashFingerprint($rawFingerprint, $election?->id ?? 'demo') : null;

        // Step 2: Build TrustCapabilityContext from hashed facts
        $ctx = new TrustCapabilityContext(
            election: $election,
            user: $user,
            network: $this->buildNetworkEvidence($election, $ipHash),
            device: $this->buildDeviceContext($fpHash, $user),
            attestation: $this->buildAttestationRecord($election, $user),
            sessionContinuity: $this->buildSessionContinuity($sessionId, $ipHash, $election),
        );

        // Step 3: Aggregate overlay influence signals (NEVER short-circuits — Resolver interprets)
        $overlayInfluence = $this->overlayCoordinator->aggregate($ctx);

        // Step 4: PolicySequence evaluates constitutional trust (always runs — not short-circuited by overlays)
        $result = $this->policySequence->evaluate($ctx);

        // Step 5: Fire-and-forget eventRecorder (never awaits, never checks return)
        $this->eventRecorder->record($result, $ctx, $overlayInfluence);

        // Step 6: Return VotingTrustResult — NEVER can_vote, capability array, or authority decision
        return $result;
    }

    private function buildNetworkEvidence(
        ?Election $election,
        string    $ipHash,
    ): NetworkTrustEvidence
    {
        $maxVotesPerIp = $election?->max_votes_per_ip ?? 6;
        $votesFromThisIp = 0; // populated by controller from database
        $restrictionEnabled = $election?->network_binding_strategy !== 'none' ?? true;
        $whitelist = $election ? ($election->ip_whitelist ? json_decode($election->ip_whitelist, true) : null) : null;

        return new NetworkTrustEvidence(
            currentIpHash: $ipHash,
            registeredIpHash: null, // populated by controller from voter session
            whitelist: $whitelist,
            maxVotesPerIp: $maxVotesPerIp,
            votesFromThisIp: $votesFromThisIp,
            restrictionEnabled: $restrictionEnabled,
            bindingStrategy: $election?->network_binding_strategy ?? 'ip_count',
        );
    }

    private function buildDeviceContext(?string $fpHash, ?User $user): DeviceTrustContext
    {
        $matchType = is_null($fpHash) ? FingerprintMatchType::NotRequired : FingerprintMatchType::ExactMatch;

        return new DeviceTrustContext(
            fingerprintHash: $fpHash,
            registeredFingerprintHash: null, // populated by controller from voter session
            matchType: $matchType,
            captureMethod: $fpHash ? 'canvas' : 'none',
            volatility: 'stable', // populated by controller based on history
        );
    }

    private function buildAttestationRecord(?Election $election, ?User $user): VerificationAttestationRecord
    {
        $required = $election ? ($election->voter_verification_required ?? false) : false;
        $attested = false; // populated by controller from VoterVerification record
        $registrarId = null; // populated by controller from VoterVerification

        return new VerificationAttestationRecord(
            required: $required,
            attested: $attested,
            registrarId: $registrarId,
            attestationTimestamp: null,
            protocol: $required ? 'both' : 'none',
            networkEvidenceHash: null,
            deviceEvidenceHash: null,
            revoked: false,
            validityScope: \App\Domain\Election\Security\TrustValidityScope::ElectionScoped,
        );
    }

    private function buildSessionContinuity(string $sessionId, string $ipHash, ?Election $election): VotingSessionTrustContinuity
    {
        return new VotingSessionTrustContinuity(
            sessionId: $sessionId,
            ipHashAtStart: $ipHash, // populated by controller on session start
            ipHashCurrent: $ipHash,
            deviceChanged: false, // populated by controller based on fingerprint comparison
            continuityState: 'continuous',
        );
    }
}
