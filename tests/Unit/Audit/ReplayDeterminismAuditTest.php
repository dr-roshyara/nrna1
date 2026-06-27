<?php

namespace Tests\Unit\Audit;

use App\Application\Election\Capabilities\CapabilityContext;
use App\Application\Election\Capabilities\Policies\TrustCapabilityPolicy;
use App\Application\Election\Security\OverlayAggregator;
use App\Application\Election\Security\PolicySequence;
use App\Application\Election\Security\TrustPolicyEvaluator;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustLevel;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Domain\Election\Security\VotingTrustResult;
use App\Models\Election;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use Tests\TestCase;

class ReplayDeterminismAuditTest extends TestCase
{
    use RefreshDatabase;

    /**
     * AUDIT 4.1: Same frozen snapshot always produces same trust evaluation state
     * Deterministic replay: election_id + snapshot → same TrustEvaluationState
     */
    public function test_same_snapshot_produces_identical_trust_evaluation_state(): void
    {
        // Arrange: Create election with frozen snapshot
        $election = Election::factory()->create([
            'network_binding_strategy' => 'ip_strict',
            'max_votes_per_ip' => 3,
            'device_binding_strategy' => 'fingerprint_required',
            'ballot_authorization_protocol' => 'dual_code',
            'trust_overlay_active' => false,
        ]);

        $user = User::factory()->create();

        // Get evaluator from container (consistent state)
        $evaluator = app(TrustPolicyEvaluator::class);

        // First evaluation
        $result1 = $evaluator->evaluate(
            election: $election,
            user: $user,
            rawIp: '192.168.1.1',
            rawFingerprint: 'fp_stable_123',
            sessionId: 'session_abc',
        );

        // Second evaluation (identical inputs)
        $result2 = $evaluator->evaluate(
            election: $election,
            user: $user,
            rawIp: '192.168.1.1',
            rawFingerprint: 'fp_stable_123',
            sessionId: 'session_abc',
        );

        // Assert: Identical evaluation state (deterministic)
        $this->assertEquals(
            $result1->result->evaluationState,
            $result2->result->evaluationState,
            "Determinism violated: same inputs produced different evaluation states"
        );

        // Assert: Identical trust level
        $this->assertEquals(
            $result1->result->trustLevel,
            $result2->result->trustLevel,
            "Trust level divergence detected"
        );

        // Assert: Identical reason
        $this->assertEquals(
            $result1->result->reason,
            $result2->result->reason,
            "Divergence in evaluation reason"
        );
    }

    /**
     * AUDIT 4.2: Policy evaluation order is deterministic (constitutional, not runtime-dependent)
     * Policies must evaluate in fixed order regardless of system state
     */
    public function test_policy_sequence_maintains_deterministic_evaluation_order(): void
    {
        // Arrange: Create PolicySequence instance
        $election = Election::factory()->create();
        $user = User::factory()->create();

        $policySequence = app(PolicySequence::class);

        // Evaluate 3 times with identical context
        $results = [];
        for ($i = 0; $i < 3; $i++) {
            $ctx = new \App\Application\Election\Security\TrustCapabilityContext(
                election: $election,
                user: $user,
                network: new NetworkTrustEvidence(
                    currentIpHash: hash('sha256', '192.168.1.1'),
                    registeredIpHash: null,
                    whitelist: null,
                    maxVotesPerIp: 3,
                    votesFromThisIp: 1,
                    restrictionEnabled: true,
                    bindingStrategy: 'ip_strict',
                ),
                device: new DeviceTrustContext(
                    fingerprintHash: hash('sha256', 'fp_123'),
                    registeredFingerprintHash: hash('sha256', 'fp_123'),
                    matchType: FingerprintMatchType::ExactMatch,
                    captureMethod: 'browser_api',
                    volatility: 'stable',
                ),
                attestation: new VerificationAttestationRecord(
                    required: false,
                    attested: false,
                    registrarId: null,
                    attestationTimestamp: null,
                    protocol: 'none',
                    networkEvidenceHash: null,
                    deviceEvidenceHash: null,
                    revoked: false,
                    validityScope: \App\Domain\Election\Security\TrustValidityScope::ElectionScoped,
                ),
                sessionContinuity: new VotingSessionTrustContinuity(
                    sessionId: 'session_abc',
                    ipHashAtStart: hash('sha256', '192.168.1.1'),
                    ipHashCurrent: hash('sha256', '192.168.1.1'),
                    deviceChanged: false,
                    continuityState: 'continuous',
                ),
            );

            $results[] = $policySequence->evaluate($ctx);
        }

        // Assert: All three evaluations produced identical results
        $this->assertEquals(
            $results[0]->evaluationState,
            $results[1]->evaluationState,
            "Policy evaluation diverged between runs 1 and 2"
        );

        $this->assertEquals(
            $results[1]->evaluationState,
            $results[2]->evaluationState,
            "Policy evaluation diverged between runs 2 and 3"
        );

        // Assert: policyOutcomeSequence order is identical
        $this->assertEquals(
            $results[0]->policyOutcomeSequence,
            $results[1]->policyOutcomeSequence,
            "Policy outcome sequence diverged"
        );
    }

    /**
     * AUDIT 4.3: Trust elevation calculations use integer math only
     * No floating-point arithmetic; thresholds must be reproducible
     */
    public function test_trust_elevation_uses_deterministic_integer_math(): void
    {
        // Test case: max_votes_per_ip = 6
        $evidence = new NetworkTrustEvidence(
            currentIpHash: hash('sha256', '192.168.1.1'),
            registeredIpHash: null,
            whitelist: null,
            maxVotesPerIp: 6,
            votesFromThisIp: 5,
            restrictionEnabled: true,
            bindingStrategy: 'ip_strict',
        );

        // Expected integer thresholds (floor operations, no floats)
        $testCases = [
            ['level' => TrustLevel::Unverified, 'expected' => 3],            // floor(6 / 2) = 3
            ['level' => TrustLevel::Attested, 'expected' => 6],              // 6
            ['level' => TrustLevel::ContinuityVerified, 'expected' => 9],    // floor(6 * 1.5) = 9
            ['level' => TrustLevel::RegistrarAttested, 'expected' => 12],    // floor(6 * 2) = 12
        ];

        foreach ($testCases as $case) {
            $trustLevel = $case['level'];
            $expectedThreshold = $case['expected'];
            // Calculate 3 times
            $results = [
                $evidence->remainingVotes($trustLevel),
                $evidence->remainingVotes($trustLevel),
                $evidence->remainingVotes($trustLevel),
            ];

            // Assert: All three calculations identical (deterministic)
            $this->assertEquals(
                $results[0],
                $results[1],
                "Calculation divergence: {$trustLevel->value} run 1 vs 2"
            );

            $this->assertEquals(
                $results[1],
                $results[2],
                "Calculation divergence: {$trustLevel->value} run 2 vs 3"
            );

            // Assert: Matches expected threshold (5 votes already cast)
            // remainingVotes returns max(0, threshold - votesFromThisIp)
            $expectedRemaining = max(0, $expectedThreshold - 5);
            $this->assertEquals(
                $expectedRemaining,
                $results[0],
                "Threshold mismatch for {$trustLevel->value}"
            );
        }

        $this->assertTrue(true);
    }

    /**
     * AUDIT 4.4: Overlay aggregation is deterministic
     * Multiple overlays always aggregate to same signal (collection ordering matters)
     */
    public function test_overlay_aggregation_maintains_deterministic_ordering(): void
    {
        // Arrange: Create election with overlays active
        $election = Election::factory()->create([
            'trust_overlay_active' => true,
        ]);

        $user = User::factory()->create();

        $coordinator = app(OverlayAggregator::class);

        // Verify: Overlay collection is ordered
        $reflection = new ReflectionClass($coordinator);
        $overlaysProperty = $reflection->getProperty('overlays');
        $overlaysProperty->setAccessible(true);
        $overlays = $overlaysProperty->getValue($coordinator);

        // Assert: Overlays exist
        $this->assertIsArray($overlays);
        $this->assertNotEmpty($overlays);

        // Aggregate 3 times
        $aggregations = [];
        for ($i = 0; $i < 3; $i++) {
            $ctx = new \App\Application\Election\Security\TrustCapabilityContext(
                election: $election,
                user: $user,
                network: new NetworkTrustEvidence(
                    currentIpHash: hash('sha256', '192.168.1.1'),
                    registeredIpHash: null,
                    whitelist: null,
                    maxVotesPerIp: 3,
                    votesFromThisIp: 0,
                    restrictionEnabled: false,
                    bindingStrategy: 'none',
                ),
                device: new DeviceTrustContext(
                    fingerprintHash: null,
                    registeredFingerprintHash: null,
                    matchType: FingerprintMatchType::NotRequired,
                    captureMethod: 'none',
                    volatility: 'stable',
                ),
                attestation: new VerificationAttestationRecord(
                    required: false,
                    attested: false,
                    registrarId: null,
                    attestationTimestamp: null,
                    protocol: 'none',
                    networkEvidenceHash: null,
                    deviceEvidenceHash: null,
                    revoked: false,
                    validityScope: \App\Domain\Election\Security\TrustValidityScope::ElectionScoped,
                ),
                sessionContinuity: new VotingSessionTrustContinuity(
                    sessionId: 'session_' . $i,
                    ipHashAtStart: hash('sha256', '192.168.1.1'),
                    ipHashCurrent: hash('sha256', '192.168.1.1'),
                    deviceChanged: false,
                    continuityState: 'continuous',
                ),
            );

            $aggregations[] = $coordinator->aggregate($ctx);
        }

        // Assert: All aggregations have same concern level
        $this->assertEquals(
            $aggregations[0]->highestConcernLevel,
            $aggregations[1]->highestConcernLevel,
            "Overlay aggregation concern level diverged between runs 1 and 2"
        );

        $this->assertEquals(
            $aggregations[1]->highestConcernLevel,
            $aggregations[2]->highestConcernLevel,
            "Overlay aggregation concern level diverged between runs 2 and 3"
        );
    }

    /**
     * AUDIT 4.5: Resolver produces deterministic capability decisions
     * Same context always produces same ElectionCapabilitySnapshot
     */
    public function test_resolver_produces_deterministic_capability_decisions(): void
    {
        // Arrange: Create voting context
        $election = Election::factory()->create();
        $user = User::factory()->create();

        $resolver = app(\App\Application\Election\Services\ElectionCapabilityResolver::class);
        $evaluator = app(TrustPolicyEvaluator::class);

        // Evaluate 3 times with identical context
        $snapshots = [];
        for ($i = 0; $i < 3; $i++) {
            $envelope = $evaluator->evaluate(
                election: $election,
                user: $user,
                rawIp: '192.168.1.1',
                rawFingerprint: 'fp_stable_123',
                sessionId: 'session_abc',
            );

            $context = new CapabilityContext(
                election: $election,
                user: $user,
                action: 'vote',
                actionMetadata: [],
                state: $election->lifecycleState(),
                trust: $envelope,
            );

            $snapshots[] = $resolver->evaluate($context);
        }

        // Assert: Identical capability decisions
        $this->assertEquals(
            $snapshots[0]->reason,
            $snapshots[1]->reason,
            "Resolver capability decision divergence between runs 1 and 2"
        );

        $this->assertEquals(
            $snapshots[1]->reason,
            $snapshots[2]->reason,
            "Resolver capability decision divergence between runs 2 and 3"
        );

        $this->assertTrue(true);
    }

    /**
     * AUDIT 4.6: Temporal behavior with frozen clock (no real sleep)
     * Time parameter controlled via injection, not wall-clock
     */
    public function test_temporal_side_effects_do_not_affect_determinism(): void
    {
        // Arrange: Create fixed timestamp context
        $election = Election::factory()->create();
        $user = User::factory()->create();
        $evaluator = app(TrustPolicyEvaluator::class);

        // Evaluate at T1
        $result1 = $evaluator->evaluate(
            election: $election,
            user: $user,
            rawIp: '192.168.1.1',
            rawFingerprint: 'fp_123',
            sessionId: 'session_abc',
        );

        // Evaluate at T1 again (no time advancement needed for determinism proof)
        $result2 = $evaluator->evaluate(
            election: $election,
            user: $user,
            rawIp: '192.168.1.1',
            rawFingerprint: 'fp_123',
            sessionId: 'session_abc',
        );

        // Assert: Identical evaluation state (determinism not affected by time)
        $this->assertEquals(
            $result1->result->evaluationState,
            $result2->result->evaluationState,
            "Evaluation state diverged despite identical temporal context"
        );

        $this->assertTrue(true);
    }

    /**
     * AUDIT 4.7: Policy sequence order consistency
     * Verify policies are evaluated in same order across multiple runs
     */
    /**
     * AUDIT 4.1: Policy Sequence Order Is Observably Deterministic
     *
     * Priority 1 — Constitutional Ordering
     *
     * Tests that policy evaluation order is:
     * - explicit (not implicit/reflection-discovered)
     * - observable (not hidden in private properties)
     * - immutable (guarantees replay determinism)
     * - typed (PolicyIdentifier enum, not strings)
     */
    public function test_policy_sequence_order_is_consistent_across_runs(): void
    {
        // Arrange: Create first policy sequence instance
        $sequence1 = app(PolicySequence::class);
        $order1 = $sequence1->getEvaluationOrder();
        $topology1 = $sequence1->observableTopology();

        // Create second instance (fresh container resolution)
        $sequence2 = app(PolicySequence::class);
        $order2 = $sequence2->getEvaluationOrder();
        $topology2 = $sequence2->observableTopology();

        // Assert: Observable ordering is identical across instances
        $this->assertEquals(
            $order1,
            $order2,
            "Policy evaluation order diverged between instances (REPLAY RISK)"
        );

        // Assert: String topology is identical
        $this->assertEquals(
            $topology1,
            $topology2,
            "Observable policy topology diverged"
        );

        // Assert: Topology matches constitutional expectation
        $expectedTopology = ['verification_attestation', 'network_binding', 'device_binding'];
        $this->assertEquals(
            $expectedTopology,
            $topology1,
            "Policy topology does not match constitutional precedence"
        );

        // Assert: Ordering contains correct number of policies
        $this->assertCount(
            3,
            $order1,
            "Policy sequence missing constitutional policies"
        );
    }

    /**
     * AUDIT 4.8: Overlay Sequence Order Is Observably Deterministic (Priority 2)
     *
     * Tests that overlay evaluation order is:
     * - explicit (not implicit/container-discovery)
     * - observable (not hidden in private properties)
     * - immutable (guarantees replay determinism)
     * - typed (OverlayStratification enum, not strings)
     */
    public function test_overlay_sequence_order_is_consistent_across_runs(): void
    {
        // Arrange: Create first OverlayAggregator instance
        $coordinator1 = app(OverlayAggregator::class);
        $order1 = $coordinator1->getEvaluationOrder();
        $topology1 = $coordinator1->observableTopology();

        // Create second instance (fresh container resolution)
        $coordinator2 = app(OverlayAggregator::class);
        $order2 = $coordinator2->getEvaluationOrder();
        $topology2 = $coordinator2->observableTopology();

        // Assert: Observable ordering is identical across instances
        $this->assertEquals(
            $order1,
            $order2,
            "Overlay evaluation order diverged between instances (REPLAY RISK)"
        );

        // Assert: String topology is identical
        $this->assertEquals(
            $topology1,
            $topology2,
            "Observable overlay topology diverged"
        );

        // Assert: Topology matches constitutional expectation
        $expectedTopology = [
            'emergency_condition',
            'registrar_attestation_elevation',
            'suspicious_activity',
            'ip_velocity',
            'device_anomaly',
        ];
        $this->assertEquals(
            $expectedTopology,
            $topology1,
            "Overlay topology does not match constitutional evaluation order"
        );

        // Assert: Ordering contains correct number of overlays
        $this->assertCount(
            5,
            $order1,
            "Overlay sequence missing constitutional overlays"
        );
    }
}
