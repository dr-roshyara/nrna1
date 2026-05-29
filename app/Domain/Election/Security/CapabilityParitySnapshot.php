<?php

namespace App\Domain\Election\Security;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Domain\Election\Enum\ElectionLifecycleState;

/**
 * Constitutional comparison contract for legacy vs resolver parity verification.
 *
 * CRITICAL INVARIANTS:
 * 1. All fields MUST use domain types (enums), NOT strings
 * 2. equals() MUST compare ALL 8 fields (field-by-field equivalence test)
 * 3. trustLegitimate MUST NOT overlap with final authority state
 * 4. Both snapshots must represent ONLY constitutional semantics
 */
readonly class CapabilityParitySnapshot
{
    public function __construct(
        /**
         * Network legitimacy: IP whitelist check, vote count limits, binding strategy compliance.
         * Enum for type safety; null means network legitimacy was not evaluated.
         */
        public ?TrustLevel $networkLegitimate,

        /**
         * Device legitimacy: fingerprint match type, device continuity, stability assessment.
         * Enum for type safety; null means device legitimacy was not evaluated.
         */
        public ?TrustLevel $deviceLegitimate,

        /**
         * Verification legitimacy: registrar attestation, captured evidence, protocol compliance.
         * Enum for type safety; null means verification was not evaluated.
         */
        public ?TrustLevel $verificationLegitimate,

        /**
         * Constitutional trust legitimacy: composite of network + device + verification.
         * DISTINCT from participationAllowed (final authority).
         * Represents whether constitutional trust infrastructure confirms legitimacy.
         * Enum for type safety; null means trust was not fully evaluated.
         */
        public ?TrustLevel $trustLegitimate,

        /**
         * Overlay governance influence: operational suspensions, velocity blocks, review requirements.
         * Enum value from OverlaySignalCategory domain type; null means no overlay was active.
         */
        public ?string $OverlaySignalCategory,

        /**
         * Authorization protocol requirement: single-code or dual-code ballot authorization.
         * Enum value from BallotAuthorizationProtocol; extracted at snapshot creation time.
         */
        public ?string $authorizationProtocol,

        /**
         * Lifecycle state at decision point: VotingActive, Suspended, Completed, etc.
         * Enum from ElectionLifecycleState; immutable from election snapshot.
         */
        public ElectionLifecycleState $lifecycleState,

        /**
         * Final authority decision: can this voter participate in this action?
         * CapabilityDenialReason enum for denial reason, or "allowed" for permission.
         * This is the OUTCOME field: what did the sovereignty engine conclude?
         */
        public string $participationAllowed,  // 'allowed' | CapabilityDenialReason->value
    ) {}

    /**
     * Equality test for constitutional parity verification.
     *
     * CRITICAL: ALL 8 fields must match. This test proves equivalence of
     * constitutional authority semantics, not just HTTP responses.
     *
     * Field-by-field comparison ensures hidden divergences are detected:
     * - Different trust thresholds (network vs verification)
     * - Different overlay interpretation
     * - Different lifecycle handling
     * - Different final authority decision
     */
    public function equals(self $other): bool
    {
        return $this->networkLegitimate === $other->networkLegitimate
            && $this->deviceLegitimate === $other->deviceLegitimate
            && $this->verificationLegitimate === $other->verificationLegitimate
            && $this->trustLegitimate === $other->trustLegitimate
            && $this->OverlaySignalCategory === $other->OverlaySignalCategory
            && $this->authorizationProtocol === $other->authorizationProtocol
            && $this->lifecycleState === $other->lifecycleState
            && $this->participationAllowed === $other->participationAllowed;
    }

    /**
     * Constitutional divergence detection: fields that differ.
     *
     * Used by ConstitutionalDivergenceLedger to classify
     * which constitutional articles diverge between legacy and resolver.
     */
    public function divergentFields(self $other): array
    {
        $divergences = [];

        if ($this->networkLegitimate !== $other->networkLegitimate) {
            $divergences[] = 'networkLegitimate';
        }
        if ($this->deviceLegitimate !== $other->deviceLegitimate) {
            $divergences[] = 'deviceLegitimate';
        }
        if ($this->verificationLegitimate !== $other->verificationLegitimate) {
            $divergences[] = 'verificationLegitimate';
        }
        if ($this->trustLegitimate !== $other->trustLegitimate) {
            $divergences[] = 'trustLegitimate';
        }
        if ($this->OverlaySignalCategory !== $other->OverlaySignalCategory) {
            $divergences[] = 'OverlaySignalCategory';
        }
        if ($this->authorizationProtocol !== $other->authorizationProtocol) {
            $divergences[] = 'authorizationProtocol';
        }
        if ($this->lifecycleState !== $other->lifecycleState) {
            $divergences[] = 'lifecycleState';
        }
        if ($this->participationAllowed !== $other->participationAllowed) {
            $divergences[] = 'participationAllowed';
        }

        return $divergences;
    }
}
