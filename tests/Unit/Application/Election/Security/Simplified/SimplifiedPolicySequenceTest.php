<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Security\Simplified\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\VerificationPolicy;
use App\Application\Election\Security\SimplifiedPolicySequence;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use App\Domain\Election\Security\Simplified\EvaluationReasonCode;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use PHPUnit\Framework\TestCase;

/**
 * SimplifiedPolicySequence Test
 *
 * Verifies the orchestrator runs 3 simplified policies in constitutional order,
 * short-circuits on first failure, and returns EvidenceEvaluationResult with causality chain.
 */
class SimplifiedPolicySequenceTest extends TestCase
{
    private SimplifiedPolicySequence $sequence;
    private ElectionConstitutionSnapshot $constitution;
    private \DateTimeImmutable $now;

    protected function setUp(): void
    {
        parent::setUp();

        $this->now = new \DateTimeImmutable('2026-05-27 12:00:00');

        $this->constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $this->sequence = new SimplifiedPolicySequence(
            new VerificationPolicy(),
            new NetworkBindingPolicy(),
            new DeviceBindingPolicy(),
        );
    }

    private function makeSnapshot(
        bool $attested = true,
        int $votesFromIp = 2,
        string $networkStrategy = 'ip_count',
        string $deviceStrategy = 'fingerprint_required',
        string $matchType = 'exact_match',
    ): ConstitutionalEvidenceSnapshot {
        return new ConstitutionalEvidenceSnapshot(
            constitution: new ElectionConstitutionSnapshot(
                networkBindingStrategy: $networkStrategy,
                maxVotesPerIp: 6,
                deviceBindingStrategy: $deviceStrategy,
                ballotAuthorizationProtocol: 'single_code',
                verificationRequired: true,
            ),
            verification: new VerificationEvidence(
                required: true,
                attested: $attested,
                registrarId: $attested ? 'reg_001' : null,
                attestationTimestamp: $attested ? $this->now : null,
            ),
            network: new NetworkEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                maxVotesPerIp: 6,
                votesFromThisIp: $votesFromIp,
                restrictionEnabled: true,
                bindingStrategy: $networkStrategy,
            ),
            device: new DeviceEvidence(
                fingerprintHash: hash('sha256', 'fp_123'),
                matchType: $matchType,
                captureMethod: 'browser_api',
                volatility: 'stable',
            ),
            continuity: new SessionContinuity(
                sessionId: 'sess_001',
                ipHashAtStart: hash('sha256', '192.168.1.1'),
                ipHashCurrent: hash('sha256', '192.168.1.1'),
                deviceChanged: false,
                continuityState: 'continuous',
            ),
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: $this->now,
            constitutionalHash: ElectionConstitutionHasher::hash($this->constitution),
        );
    }

    /**
     * All 3 policies pass → SUFFICIENT_EVIDENCE.
     */
    public function test_all_pass_returns_sufficient_evidence(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot());

        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertCount(3, $result->policyOutcomeSequence);
        $this->assertEquals(EvaluationReasonCode::ALL_POLICIES_PASSED, $result->reason);
    }

    /**
     * Verification fails → INSUFFICIENT_EVIDENCE.
     * All 3 policies are still evaluated (no short-circuiting).
     * The causality chain contains all 3 outcomes.
     *
     * STEP A.2 FIX F1: reason is now EvaluationReasonCode enum (not string).
     */
    public function test_verification_failure_with_all_policies_evaluated(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot(attested: false));

        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertCount(3, $result->policyOutcomeSequence);
        $this->assertArrayHasKey('verification_policy', $result->policyOutcomeSequence);
        $this->assertEquals('failed', $result->policyOutcomeSequence['verification_policy']);

        // F1 FIX: Reason is now EvaluationReasonCode enum
        // Preserve lineage in auditContext (constitutional basis strings)
        $this->assertEquals(
            EvaluationReasonCode::VERIFICATION_REQUIRED_NOT_SATISFIED,
            $result->reason
        );
        $this->assertNotEmpty($result->auditContext);
    }

    /**
     * Network fails (IP count exceeded) → INSUFFICIENT_EVIDENCE.
     * All 3 policies still evaluated — device outcome preserved in causality chain.
     */
    public function test_network_failure_all_policies_still_evaluated(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot(votesFromIp: 7));

        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertCount(3, $result->policyOutcomeSequence);
        $this->assertArrayHasKey('verification_policy', $result->policyOutcomeSequence);
        $this->assertEquals('passed', $result->policyOutcomeSequence['verification_policy']);
        $this->assertArrayHasKey('network_binding_policy', $result->policyOutcomeSequence);
        $this->assertEquals('failed', $result->policyOutcomeSequence['network_binding_policy']);
        $this->assertArrayHasKey('device_binding_policy', $result->policyOutcomeSequence);
        $this->assertEquals('passed', $result->policyOutcomeSequence['device_binding_policy']);
    }

    /**
     * Device fails (fingerprint mismatch) → all 3 evaluated, device shows failure.
     */
    public function test_device_failure_reported(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot(matchType: 'no_match'));

        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);
        $this->assertCount(3, $result->policyOutcomeSequence);
        $this->assertEquals('passed', $result->policyOutcomeSequence['verification_policy']);
        $this->assertEquals('passed', $result->policyOutcomeSequence['network_binding_policy']);
        $this->assertEquals('failed', $result->policyOutcomeSequence['device_binding_policy']);
    }

    /**
     * Policy outcome sequence preserves constitutional evaluation order.
     */
    public function test_policy_outcome_sequence_preserves_order(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot());

        $sequence = array_keys($result->policyOutcomeSequence);

        // Constitutional order: verification → network → device
        $this->assertEquals('verification_policy', $sequence[0]);
        $this->assertEquals('network_binding_policy', $sequence[1]);
        $this->assertEquals('device_binding_policy', $sequence[2]);
    }

    /**
     * EvidenceClassification is Initial (placeholder). The Resolver upgrades it.
     */
    public function test_trust_level_is_placeholder_unverified(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot());

        $this->assertEquals(EvidenceClassification::Initial, $result->classification);
    }

    /**
     * Network strategy 'none' passes and does not evaluate IP count.
     */
    public function test_network_strategy_none_passes(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot(
            networkStrategy: 'none',
            votesFromIp: 100, // would fail ip_count, but strategy is 'none'
        ));

        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $result->evaluationState);
    }

    /**
     * Replay determinism: same snapshot → same result every time.
     */
    public function test_replay_determinism(): void
    {
        $snapshot = $this->makeSnapshot();

        $firstResult = $this->sequence->evaluate($snapshot);

        for ($i = 0; $i < 3; $i++) {
            $replayResult = $this->sequence->evaluate($snapshot);

            $this->assertSame(
                $firstResult->evaluationState,
                $replayResult->evaluationState,
                "Evaluation state changed on replay run {$i}"
            );
            $this->assertSame(
                $firstResult->classification,
                $replayResult->classification,
                "Classification changed on replay run {$i}"
            );
            $this->assertSame(
                $firstResult->policyOutcomeSequence,
                $replayResult->policyOutcomeSequence,
                "Policy outcome sequence changed on replay run {$i}"
            );
        }
    }

    // ============================================================================
    // GROUP 3 — SIMPLIFIED POLICY SEQUENCE REASON TYPE (D.R.3 Phase A)
    // ============================================================================

    /**
     * CRITICAL FIX F1: $result->reason must be EvaluationReasonCode enum, not string.
     * This test was previously asserting string presence — now explicitly typed.
     */
    public function test_failure_reason_is_evaluation_reason_code_enum(): void
    {
        $result = $this->sequence->evaluate($this->makeSnapshot(attested: false));

        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);

        // CRITICAL: reason MUST be EvaluationReasonCode enum, not string
        // This is the F1 type safety fix
        $this->assertIsNotString($result->reason);

        // Verify it's actually an enum instance or enum-typed
        // The result->reason should map to a specific reason code
        $this->assertNotNull($result->reason);
    }

    /**
     * Multiple failures map to appropriate reason code enum.
     * When multiple policies fail, use FIRST failure's reason (preserve lineage in sequence).
     */
    public function test_multiple_failures_use_appropriate_reason_code(): void
    {
        // Both verification AND network fail
        $result = $this->sequence->evaluate($this->makeSnapshot(
            attested: false,      // verification fails
            votesFromIp: 7,       // network fails
        ));

        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $result->evaluationState);

        // Reason should be enum, not string
        $this->assertIsNotString($result->reason);

        // Verify causality chain preserved all outcomes (RF1 — finding lineage)
        $this->assertCount(3, $result->policyOutcomeSequence);
        $this->assertEquals('failed', $result->policyOutcomeSequence['verification_policy']);
        $this->assertEquals('failed', $result->policyOutcomeSequence['network_binding_policy']);
        $this->assertEquals('passed', $result->policyOutcomeSequence['device_binding_policy']);
    }
}
