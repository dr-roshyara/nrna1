<?php

namespace App\Application\Election\Security;

use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence;
use App\Domain\Election\Security\TrustEvaluationEnvelope;
use App\Domain\Election\Security\TrustEvidencePrivacyPolicy;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\User;

class TrustPolicyEvaluator
{
    // Thin orchestration: hashes evidence → builds context → runs overlay/policies/recorder
    // Invariant 3: Returns TrustEvaluationEnvelope with result, overlay influence, and snapshot

    public function __construct(
        private OverlayAggregator                  $OverlayAggregator,
        private PolicySequence                      $policySequence,
        private SecurityEventRecorder               $eventRecorder,
        private TrustEvidencePrivacyPolicy         $privacyPolicy,
        private TrustSnapshotAssembler             $assembler,
    ) {}

    public function evaluate(
        ?Election $election,
        ?User     $user,
        string    $rawIp,
        ?string   $rawFingerprint,
        string    $sessionId,
        ?string   $registeredIpHash = null,
        ?int      $votesFromThisIp = null,
    ): TrustEvaluationEnvelope
    {
        // Step 1: Hash evidence (raw IP never reaches any other method)
        $ipHash = $this->privacyPolicy->hashIp($rawIp, $election?->id ?? 'demo');
        $fpHash = $rawFingerprint ? $this->privacyPolicy->hashFingerprint($rawFingerprint, $election?->id ?? 'demo') : null;

        // Step 2: Build TrustCapabilityContext from hashed facts
        $ctx = new TrustCapabilityContext(
            election: $election,
            user: $user,
            network: $this->buildNetworkEvidence($election, $ipHash, $registeredIpHash, $votesFromThisIp),
            device: $this->buildDeviceContext($fpHash, $user),
            attestation: $this->buildAttestationRecord($election, $user),
            sessionContinuity: $this->buildSessionContinuity($sessionId, $ipHash, $registeredIpHash ?? $ipHash, $election),
        );

        // Step 3: Aggregate overlay influence signals (NEVER short-circuits — Resolver interprets)
        $OverlaySignalCategory = $this->OverlayAggregator->aggregate($ctx);

        // Step 4: PolicySequence evaluates constitutional trust (always runs — not short-circuited by overlays)
        $result = $this->policySequence->evaluate($ctx);

        // Step 5: Freeze participation eligibility evidence (TSC-1 — observational, not authority)
        $eligibility = $this->buildEligibilityEvidence($election, $user);

        // Step 6: Assemble snapshot (only place where TrustCapabilityContext exists)
        $snapshot = $this->assembler->assemble($result, $ctx, $OverlaySignalCategory);

        // Step 7: Fire-and-forget eventRecorder (never awaits, never checks return)
        $this->eventRecorder->record($result, $ctx, $OverlaySignalCategory);

        // Step 8: Return TrustEvaluationEnvelope with result, overlay influence, snapshot, and frozen eligibility evidence
        return new TrustEvaluationEnvelope($result, $OverlaySignalCategory, $snapshot, $eligibility);
    }

    /**
     * Build frozen participation eligibility evidence at the evaluation boundary.
     *
     * TSC-1: This is OBSERVATIONAL evidence, NOT authority.
     * The evidence captures the constitutional participation state at the
     * moment of evaluation, enabling replay systems to detect when runtime
     * eligibility diverged from the frozen snapshot.
     *
     * Returns null when user or election is not available (pre-authentication).
     */
    private function buildEligibilityEvidence(?Election $election, ?User $user): ?ParticipationEligibilityEvidence
    {
        if (!$user || !$election) {
            return null;
        }

        $membership = ElectionMembership::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('user_id', $user->id)
            ->first();

        if (!$membership) {
            return null;
        }

        $now = now();
        $isActive = $membership->status === 'active';
        $notExpired = $membership->expires_at === null || $membership->expires_at > $now;
        $hasActiveMembership = $isActive && $notExpired;
        $hasValidAssignment = $membership->role === 'voter';
        $hasApproval = $isActive;
        $isSuspended = in_array($membership->suspension_status, ['proposed', 'confirmed'], true);

        // Deterministic hash of the eligibility state for replay divergence detection
        $eligibilityHash = hash('sha256', implode('|', [
            (string)$hasActiveMembership,
            (string)$hasValidAssignment,
            (string)$hasApproval,
            (string)$isSuspended,
            (string)$membership->expires_at?->toIso8601String(),
            $membership->suspension_status ?? 'none',
            $election->id,
            $user->id,
        ]));

        return new ParticipationEligibilityEvidence(
            hasActiveMembership: $hasActiveMembership,
            hasValidAssignment: $hasValidAssignment,
            hasApproval: $hasApproval,
            isSuspended: $isSuspended,
            eligibilityEvaluatedAt: new \DateTimeImmutable($now->toIso8601String()),
            eligibilitySourceVersion: '1.0.0',
            eligibilityHash: $eligibilityHash,
        );
    }

    private function buildNetworkEvidence(
        ?Election $election,
        string    $ipHash,
        ?string   $registeredIpHash = null,
        ?int      $votesFromThisIp = null,
    ): NetworkTrustEvidence
    {
        $maxVotesPerIp = $election?->max_votes_per_ip ?? 6;
        $votes = $votesFromThisIp ?? 0; // Use provided value or default to 0
        $restrictionEnabled = $election !== null && $election->network_binding_strategy !== 'none';
        $whitelist = $election ? ($election->ip_whitelist ? json_decode($election->ip_whitelist, true) : null) : null;

        return new NetworkTrustEvidence(
            currentIpHash: $ipHash,
            registeredIpHash: $registeredIpHash, // Populated by controller from voter session
            whitelist: $whitelist,
            maxVotesPerIp: $maxVotesPerIp,
            votesFromThisIp: $votes, // Populated by controller from database
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

    private function buildSessionContinuity(string $sessionId, string $ipHash, ?string $registeredIpHash, ?Election $election): VotingSessionTrustContinuity
    {
        return new VotingSessionTrustContinuity(
            sessionId: $sessionId,
            ipHashAtStart: $registeredIpHash ?? $ipHash, // Use registered hash if available, otherwise current
            ipHashCurrent: $ipHash,
            deviceChanged: false, // populated by controller based on fingerprint comparison
            continuityState: 'continuous',
        );
    }
}
