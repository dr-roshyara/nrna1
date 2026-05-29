<?php

namespace Tests\Unit\Application\Election\Security\Simplified;

use App\Application\Election\Capabilities\Policies\EvidenceCapabilityPolicy;
use App\Application\Election\Security\Simplified\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\VerificationPolicy;
use App\Application\Election\Security\SimplifiedPolicySequence;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\ConstitutionalObservationContext;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\EvidenceEvaluationState;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use PHPUnit\Framework\TestCase;

/**
 * Simplified Trust Chain Integration Test (D.R.3 Phase A, Group 5)
 *
 * Verifies end-to-end flow:
 * ConstitutionalEvidenceSnapshot → SimplifiedPolicySequence → ConstitutionalObservationContext
 * → EvidenceCapabilityPolicy → CapabilityDecision
 *
 * Tests that entire Simplified pipeline works correctly from evidence snapshot to capability decision.
 */
class SimplifiedTrustChainIntegrationTest extends TestCase
{
    private SimplifiedPolicySequence $sequence;
    private EvidenceCapabilityPolicy $policy;
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

        $this->policy = new EvidenceCapabilityPolicy();
    }

    private function makeSnapshot(
        bool $attested = true,
        int $votesFromIp = 2,
        string $deviceMatch = 'exact_match',
    ): ConstitutionalEvidenceSnapshot {
        return new ConstitutionalEvidenceSnapshot(
            constitution: $this->constitution,
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
                bindingStrategy: 'ip_count',
            ),
            device: new DeviceEvidence(
                fingerprintHash: hash('sha256', 'fp_123'),
                matchType: $deviceMatch,
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
     * FULL CHAIN: Valid evidence → policies pass → no overlay signals → abstain.
     * All evidence valid → resolver abstains (lets other policies run).
     */
    public function test_full_chain_abstains_when_all_evidence_valid(): void
    {
        // Step 1: Evaluate evidence with SimplifiedPolicySequence
        $snapshot = $this->makeSnapshot(attested: true, votesFromIp: 2);
        $evaluationResult = $this->sequence->evaluate($snapshot);

        // Step 2: Evidence evaluation passes (all policies pass)
        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $evaluationResult->evaluationState);

        // Step 3: Resolver interprets result with empty observation context (no overlays)
        $observationContext = new ConstitutionalObservationContext([]);
        $capabilityDecision = $this->policy->evaluateSimplifiedOverlay(
            $evaluationResult->evaluationState,
            $observationContext,
        );

        // Step 4: Resolver abstains (lets other policies run)
        $this->assertNull($capabilityDecision);
    }

    /**
     * FULL CHAIN: Verification fails → resolver sees INSUFFICIENT_EVIDENCE.
     * No attestation signal added (verification failure is not attestation).
     * Resolver decides: insufficient evidence → prohibit.
     */
    public function test_full_chain_prohibits_when_verification_fails(): void
    {
        // Step 1: Evaluation fails (verification not attested)
        $snapshot = $this->makeSnapshot(attested: false);
        $evaluationResult = $this->sequence->evaluate($snapshot);

        // Step 2: Evaluation state is insufficient
        $this->assertEquals(EvidenceEvaluationState::INSUFFICIENT_EVIDENCE, $evaluationResult->evaluationState);

        // Step 3: Resolver interprets result
        $observationContext = new ConstitutionalObservationContext([]);
        $capabilityDecision = $this->policy->evaluateSimplifiedOverlay(
            $evaluationResult->evaluationState,
            $observationContext,
        );

        // Step 4: Resolver prohibits (insufficient evidence)
        $this->assertNotNull($capabilityDecision);
        $this->assertTrue($capabilityDecision->denies());
    }

    /**
     * CHAIN DETERMINISM: Same snapshot → identical output every replay.
     * Tests that entire chain is deterministic for evidence replay/audit.
     */
    public function test_chain_output_is_deterministic_for_identical_snapshots(): void
    {
        $snapshot = $this->makeSnapshot(attested: true, votesFromIp: 3);

        // Evaluate chain multiple times
        $results = [];
        for ($i = 0; $i < 3; $i++) {
            $evaluationResult = $this->sequence->evaluate($snapshot);

            $observationContext = new ConstitutionalObservationContext([]);
            $capabilityDecision = $this->policy->evaluateSimplifiedOverlay(
                $evaluationResult->evaluationState,
                $observationContext,
            );

            $results[] = [
                'state' => $evaluationResult->evaluationState,
                'decision' => $capabilityDecision,
            ];
        }

        // All three runs must be identical
        $this->assertEquals($results[0]['state'], $results[1]['state']);
        $this->assertEquals($results[1]['state'], $results[2]['state']);

        // Decisions must be identical (all null or all non-null with same reason)
        if ($results[0]['decision'] === null) {
            $this->assertNull($results[1]['decision']);
            $this->assertNull($results[2]['decision']);
        } else {
            $this->assertNotNull($results[1]['decision']);
            $this->assertNotNull($results[2]['decision']);
            $this->assertEquals($results[0]['decision']->reason, $results[1]['decision']->reason);
            $this->assertEquals($results[1]['decision']->reason, $results[2]['decision']->reason);
        }
    }

    /**
     * CHAIN WITH OVERLAY SIGNAL: Attestation signal present → resolver prohibits.
     * Tests that observation signals flow correctly through the chain.
     */
    public function test_chain_with_attestation_signal_prohibits(): void
    {
        // Step 1: Evidence evaluation passes
        $snapshot = $this->makeSnapshot(attested: true, votesFromIp: 2);
        $evaluationResult = $this->sequence->evaluate($snapshot);

        $this->assertEquals(EvidenceEvaluationState::SUFFICIENT_EVIDENCE, $evaluationResult->evaluationState);

        // Step 2: Create observation context WITH attestation signal
        $observationContext = new ConstitutionalObservationContext([
            OverlaySignal::attestationPresent('device_anomaly', 'Device fingerprint changed', []),
        ]);

        // Step 3: Resolver interprets result with attestation signal
        $capabilityDecision = $this->policy->evaluateSimplifiedOverlay(
            $evaluationResult->evaluationState,
            $observationContext,
        );

        // Step 4: Resolver prohibits (attestation signal requires reverification)
        $this->assertNotNull($capabilityDecision);
        $this->assertTrue($capabilityDecision->denies());
    }
}
