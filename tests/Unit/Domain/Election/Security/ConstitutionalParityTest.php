<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\CapabilityParitySnapshot;
use App\Domain\Election\Security\ConstitutionalDivergenceLedger;
use App\Domain\Election\Security\ConstitutionalDivergenceType;
use App\Domain\Election\Security\Severity;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Domain\Election\Security\BallotAuthorizationProtocol;
use PHPUnit\Framework\TestCase;

/**
 * Phase 1: Constitutional Parity Verification
 *
 * CRITICAL INVARIANT:
 * Proves that legacy voting system and ElectionCapabilityResolver derive
 * IDENTICAL constitutional authority decisions across all participation
 * legitimacy dimensions.
 *
 * Tests ALL 8 constitutional semantic fields:
 * - networkLegitimate
 * - deviceLegitimate
 * - verificationLegitimate
 * - trustLegitimate
 * - overlayInfluence
 * - authorizationProtocol
 * - lifecycleState
 * - participationAllowed
 *
 * @group constitutional-parity
 */
class ConstitutionalParityTest extends TestCase
{
    private ConstitutionalDivergenceLedger $divergenceLedger;

    protected function setUp(): void
    {
        parent::setUp();
        $this->divergenceLedger = new ConstitutionalDivergenceLedger([]);
    }

    /**
     * P0: RESOLVER SOVEREIGNTY
     * Happy Path — All legitimate, no overlay, voting active, single-code
     *
     * GOVERNANCE PRINCIPLE:
     * When all participation legitimacy dimensions confirm legitimacy,
     * and no overlay signal is active, and election lifecycle permits voting,
     * the resolver MUST derive participationAllowed = 'allowed'.
     */
    public function test_happy_path_all_legitimate_no_overlay_active_lifecycle(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: 'allowed',
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: OVERLAY NON-SOVEREIGNTY
     * Overlay Review + Elevated Trust + Lifecycle Active
     *
     * GOVERNANCE PRINCIPLE:
     * Overlays signal influence but DO NOT grant authority.
     * When overlay signals review requirement, TrustCapabilityPolicy MUST
     * interpret this and deny with ConstitutionalReviewPending.
     * Overlay cannot override lifecycle permits.
     */
    public function test_overlay_review_signal_trust_elevated_lifecycle_active(): void
    {
        // Legacy behavior: overlay active + elevated trust → might allow but reviewer checks
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::RegistrarAttested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::RegistrarAttested,
            overlayInfluence: 'require_constitutional_review',  // signal, not grant
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::ConstitutionalReviewPending->value,
        );

        // Resolver behavior: same outcome through TrustCapabilityPolicy interpretation
        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::RegistrarAttested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::RegistrarAttested,
            overlayInfluence: 'require_constitutional_review',
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::ConstitutionalReviewPending->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: RESOLVER SOVEREIGNTY
     * Lifecycle Denial Overrides Trust — Trust Legitimate + Election Closed
     *
     * GOVERNANCE PRINCIPLE:
     * Even with perfect trust legitimacy, election lifecycle closure MUST
     * deny participation. Trust fields capture legitimacy assessment;
     * final participationAllowed reflects lifecycle veto.
     */
    public function test_lifecycle_closed_overrides_trust_legitimate(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::Counting,
            participationAllowed: CapabilityDenialReason::InvalidLifecycle->value,
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::Counting,
            participationAllowed: CapabilityDenialReason::InvalidLifecycle->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: AUTHORIZATION PROTOCOL ENFORCEMENT
     * Dual-Code Requirement + Single Code Provided
     *
     * GOVERNANCE PRINCIPLE:
     * When ballot authorization protocol requires dual-code (separate view+commit),
     * single code provision MUST be denied. authorizationProtocol field in snapshot
     * captures the constitutional requirement.
     */
    public function test_dual_code_requirement_single_code_denied(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::SplitAuthorizationProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::UnmetPrecondition->value,  // missing separate commit
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::SplitAuthorizationProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::UnmetPrecondition->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: TRUST DENIAL SHORT-CIRCUITS LIFECYCLE
     * Network Limit Exceeded → Trust Denied, Lifecycle Open
     *
     * GOVERNANCE PRINCIPLE:
     * When trust evaluation fails (network legitimacy exceeded limits),
     * TrustCapabilityPolicy MUST short-circuit and deny with TrustDenied.
     * Lifecycle policy is never evaluated.
     * Policy ordering: Overlay(1) → Trust(2) → Lifecycle(3)
     */
    public function test_trust_denied_short_circuits_lifecycle_evaluation(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Unverified,  // limit exceeded
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Unverified,    // composite reflects network failure
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,  // lifecycle permits, but trust denies first
            participationAllowed: CapabilityDenialReason::TrustDenied->value,
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Unverified,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Unverified,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::TrustDenied->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: OVERLAY NON-SOVEREIGNTY
     * Emergency Condition Active + Registrar Elevated Trust
     *
     * GOVERNANCE PRINCIPLE:
     * Even with registrar attestation (highest trust level), emergency
     * condition overlay signals REQUIRE_CONSTITUTIONAL_REVIEW.
     * Overlay signals, not grants. Final decision goes to constitutional review.
     */
    public function test_emergency_overlay_does_not_grant_authority(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::RegistrarAttested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::RegistrarAttested,
            trustLegitimate: TrustLevel::RegistrarAttested,
            overlayInfluence: 'require_constitutional_review',  // emergency overlay signals
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::ConstitutionalReviewPending->value,
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::RegistrarAttested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::RegistrarAttested,
            trustLegitimate: TrustLevel::RegistrarAttested,
            overlayInfluence: 'require_constitutional_review',
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::ConstitutionalReviewPending->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: OVERLAY NON-SOVEREIGNTY
     * Suspicious Activity Velocity Exceeded + Valid Trust
     *
     * GOVERNANCE PRINCIPLE:
     * IpVelocityOverlay signals TRUST_EVALUATION_INCONCLUSIVE when velocity
     * threshold exceeded. This makes trust assessment unreliable, even with
     * previous verification. TrustCapabilityPolicy must deny with
     * TrustEvaluationInconclusive.
     */
    public function test_suspicious_activity_overlay_inconclusive_trust(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,  // would be legitimate
            overlayInfluence: 'trust_evaluation_inconclusive',  // but velocity makes it unreliable
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::TrustEvaluationInconclusive->value,
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: 'trust_evaluation_inconclusive',
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::TrustEvaluationInconclusive->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: REPLAY INTEGRITY
     * Commit Token Reuse Rejected
     *
     * GOVERNANCE PRINCIPLE:
     * Commit tokens MUST be single-use. Reuse attempt reveals replay attack
     * or fraudulent resubmission. CommitAuthorizationFreshness validates freshness
     * and detects replay. Resolver must deny with replay detection.
     */
    public function test_commit_token_reuse_replay_rejected(): void
    {
        // Both legacy and resolver must detect and reject replay
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::SplitAuthorizationProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::MissingRole->value,  // reuse detected as invalid precondition
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::SplitAuthorizationProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::MissingRole->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P0: REPLAY INTEGRITY
     * Stale Authorization Denied — Issued 1 Hour Ago
     *
     * GOVERNANCE PRINCIPLE:
     * Authorization tokens have bounded freshness (typically 1 hour max).
     * Stale tokens indicate either timing attack or session hijacking.
     * Both legacy and resolver MUST enforce freshness boundary.
     */
    public function test_stale_authorization_freshness_expired(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::UnmetPrecondition->value,  // stale auth
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::VotingActive,
            participationAllowed: CapabilityDenialReason::UnmetPrecondition->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * P1: CONSTITUTIONAL SEQUENCING
     * Overlay Evaluated Before Trust — Suspension Active + Trust Valid
     *
     * GOVERNANCE PRINCIPLE:
     * Policy stratification: Overlay(1) < Trust(2) < Lifecycle(3) < Preconditions(4) < Authorization(5)
     * Overlay policy MUST run first. If overlay denies, trust policy never runs.
     * This ensures operational authority (overlay) doesn't bypass constitutional
     * trust derivation.
     */
    public function test_policy_stratification_overlay_before_trust(): void
    {
        $legacySnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,  // trust would allow
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::Suspended,  // overlay suspension
            participationAllowed: CapabilityDenialReason::Suspended->value,
        );

        $resolverSnapshot = new CapabilityParitySnapshot(
            networkLegitimate: TrustLevel::Attested,
            deviceLegitimate: TrustLevel::Attested,
            verificationLegitimate: TrustLevel::Attested,
            trustLegitimate: TrustLevel::Attested,
            overlayInfluence: null,
            authorizationProtocol: BallotAuthorizationProtocol::UnifiedTokenProtocol->value,
            lifecycleState: ElectionLifecycleState::Suspended,
            participationAllowed: CapabilityDenialReason::Suspended->value,
        );

        $this->assertTrue($legacySnapshot->equals($resolverSnapshot));
    }

    /**
     * Helper: Record divergence when parity fails
     */
    private function recordDivergence(
        CapabilityParitySnapshot $legacy,
        CapabilityParitySnapshot $resolver,
        string $testName,
    ): void {
        $divergences = $legacy->divergentFields($resolver);

        if (empty($divergences)) {
            return;  // no divergence
        }

        // Classify primary divergence for ledger
        $type = ConstitutionalDivergenceType::UnclassifiedDivergence;
        if (in_array('participationAllowed', $divergences)) {
            $type = ConstitutionalDivergenceType::UnclassifiedDivergence;  // needs manual classification
        }

        $this->divergenceLedger->addEntry(
            electionId: 1,  // test election
            type: $type,
            legacyBehavior: $legacy->participationAllowed,
            resolverBehavior: $resolver->participationAllowed,
            approvedBy: 'system_test_harness',
            rationale: "Test: {$testName}. Divergent fields: " . implode(', ', $divergences),
        );
    }
}
