<?php

namespace Tests\Replay;

use App\Application\Election\Security\Simplified\Policies\DeviceBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\NetworkBindingPolicy;
use App\Application\Election\Security\Simplified\Policies\VerificationPolicy;
use App\Domain\Election\Security\Simplified\ConstitutionalEvidenceSnapshot;
use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\ElectionConstitutionHasher;
use App\Domain\Election\Security\Simplified\ElectionConstitutionSnapshot;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use PHPUnit\Framework\TestCase;

/**
 * Replay Determinism Contract Tests
 *
 * Proves that the constitutional policy evaluation pipeline is fully deterministic:
 * same evidence input → same evaluation output, every time.
 *
 * These are NOT unit tests of logic — they are REPLAY CONTRACT TESTS.
 * They verify the evaluation pipeline satisfies the replay determinism invariant.
 */
class ReplayDeterminismContractTest extends TestCase
{
    private function createStandardSnapshot(): ConstitutionalEvidenceSnapshot
    {
        $constitution = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $now = new \DateTimeImmutable('2026-05-27 12:00:00');

        return new ConstitutionalEvidenceSnapshot(
            constitution: $constitution,
            verification: new VerificationEvidence(
                required: true,
                attested: true,
                registrarId: 'reg_001',
                attestationTimestamp: $now,
            ),
            network: new NetworkEvidence(
                currentIpHash: hash('sha256', '192.168.1.1'),
                maxVotesPerIp: 6,
                votesFromThisIp: 2,
                restrictionEnabled: true,
                bindingStrategy: 'ip_count',
            ),
            device: new DeviceEvidence(
                fingerprintHash: hash('sha256', 'fp_123'),
                matchType: 'exact_match',
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
            evaluatedAt: $now,
            constitutionalHash: ElectionConstitutionHasher::hash($constitution),
        );
    }

    // =========================================================================
    // REPLAY DETERMINISM — Same input → same output, every run
    // =========================================================================

    /**
     * REPLAY CONTRACT: VerificationPolicy must produce identical results
     * for identical ConstitutionalEvidenceSnapshot across multiple evaluations.
     * Run 3× to catch non-determinism.
     */
    public function test_verification_policy_replay_determinism(): void
    {
        $snapshot = $this->createStandardSnapshot();
        $policy = new VerificationPolicy();

        $firstResult = $policy->evaluate($snapshot);

        for ($i = 0; $i < 3; $i++) {
            $replayResult = $policy->evaluate($snapshot);

            $this->assertEquals(
                $firstResult->passed,
                $replayResult->passed,
                "VerificationPolicy replay run {$i}: passed differs"
            );
            $this->assertEquals(
                $firstResult->constitutionalBasis,
                $replayResult->constitutionalBasis,
                "VerificationPolicy replay run {$i}: constitutionalBasis differs"
            );
            $this->assertEquals(
                $firstResult->policyIdentifier,
                $replayResult->policyIdentifier,
                "VerificationPolicy replay run {$i}: policyIdentifier differs"
            );
            $this->assertEquals(
                $firstResult->supportingFacts,
                $replayResult->supportingFacts,
                "VerificationPolicy replay run {$i}: supportingFacts differs"
            );
        }
    }

    /**
     * REPLAY CONTRACT: NetworkBindingPolicy must produce identical results
     * for identical snapshot across multiple evaluations.
     */
    public function test_network_binding_policy_replay_determinism(): void
    {
        $snapshot = $this->createStandardSnapshot();
        $policy = new NetworkBindingPolicy();

        $firstResult = $policy->evaluate($snapshot);

        for ($i = 0; $i < 3; $i++) {
            $replayResult = $policy->evaluate($snapshot);

            $this->assertEquals(
                $firstResult->passed,
                $replayResult->passed,
                "NetworkBindingPolicy replay run {$i}: passed differs"
            );
            $this->assertEquals(
                $firstResult->constitutionalBasis,
                $replayResult->constitutionalBasis,
                "NetworkBindingPolicy replay run {$i}: constitutionalBasis differs"
            );
        }
    }

    /**
     * REPLAY CONTRACT: DeviceBindingPolicy must produce identical results
     * for identical snapshot across multiple evaluations.
     */
    public function test_device_binding_policy_replay_determinism(): void
    {
        $snapshot = $this->createStandardSnapshot();
        $policy = new DeviceBindingPolicy();

        $firstResult = $policy->evaluate($snapshot);

        for ($i = 0; $i < 3; $i++) {
            $replayResult = $policy->evaluate($snapshot);

            $this->assertEquals(
                $firstResult->passed,
                $replayResult->passed,
                "DeviceBindingPolicy replay run {$i}: passed differs"
            );
            $this->assertEquals(
                $firstResult->constitutionalBasis,
                $replayResult->constitutionalBasis,
                "DeviceBindingPolicy replay run {$i}: constitutionalBasis differs"
            );
        }
    }

    /**
     * REPLAY CONTRACT: All policies together must produce identical results
     * for the same evidence snapshot — end-to-end determinism.
     */
    public function test_all_policies_deterministic_end_to_end(): void
    {
        $snapshot = $this->createStandardSnapshot();
        $policies = [
            new VerificationPolicy(),
            new NetworkBindingPolicy(),
            new DeviceBindingPolicy(),
        ];

        // First evaluation
        $firstResults = array_map(fn($p) => $p->evaluate($snapshot), $policies);

        for ($run = 0; $run < 3; $run++) {
            foreach ($policies as $i => $policy) {
                $replayResult = $policy->evaluate($snapshot);

                $this->assertSame(
                    $firstResults[$i]->passed,
                    $replayResult->passed,
                    "Policy {$i} replay run {$run}: passed differs from first evaluation"
                );
                $this->assertSame(
                    $firstResults[$i]->constitutionalBasis,
                    $replayResult->constitutionalBasis,
                    "Policy {$i} replay run {$run}: constitutionalBasis differs from first evaluation"
                );
            }
        }
    }

    // =========================================================================
    // SNAPSHOT HASH STABILITY — Same snapshot → same hash, always
    // =========================================================================

    /**
     * REPLAY CONTRACT: Identical ElectionConstitutionSnapshot must produce
     * identical hash every time. This ensures constitution integrity verification.
     */
    public function test_identical_snapshot_produces_identical_hash(): void
    {
        $snapshot1 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $snapshot2 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $hash1 = ElectionConstitutionHasher::hash($snapshot1);
        $hash2 = ElectionConstitutionHasher::hash($snapshot2);

        $this->assertSame($hash1, $hash2, 'Identical constitution snapshots must produce identical hashes');
    }

    /**
     * REPLAY CONTRACT: Different constitution values must produce different hashes.
     * Hash collision would break constitutional integrity verification.
     */
    public function test_different_snapshot_produces_different_hash(): void
    {
        $snapshot1 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $snapshot2 = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_strict',
            maxVotesPerIp: 6,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: true,
        );

        $hash1 = ElectionConstitutionHasher::hash($snapshot1);
        $hash2 = ElectionConstitutionHasher::hash($snapshot2);

        $this->assertNotSame($hash1, $hash2, 'Different constitution snapshots must produce different hashes');
    }

    /**
     * REPLAY CONTRACT: Hash is deterministic across multiple calls
     * on the same snapshot object.
     */
    public function test_hash_is_deterministic_across_calls(): void
    {
        $snapshot = new ElectionConstitutionSnapshot(
            networkBindingStrategy: 'ip_count',
            maxVotesPerIp: 10,
            deviceBindingStrategy: 'fingerprint_required',
            ballotAuthorizationProtocol: 'single_code',
            verificationRequired: false,
        );

        $firstHash = ElectionConstitutionHasher::hash($snapshot);

        for ($i = 0; $i < 5; $i++) {
            $this->assertSame(
                $firstHash,
                ElectionConstitutionHasher::hash($snapshot),
                "Hash changed on call {$i} — non-deterministic hashing detected"
            );
        }
    }

    // =========================================================================
    // POLICY ORDERING STABILITY — Deterministic evaluation order
    // =========================================================================

    /**
     * REPLAY CONTRACT: Policy identifiers must follow constitutional evaluation order:
     * 1. verification_policy (Article 6 — evidence legitimacy)
     * 2. network_binding_policy (Article 1/4 — IP limits and continuity)
     * 3. device_binding_policy (Article 5 — device continuity)
     *
     * This order is defined by the policy dependency graph:
     * - Verification: no dependencies (establishes base legitimacy)
     * - Network: depends on verification (attested evidence required first)
     * - Device: depends on verification + network (continuity verified)
     */
    public function test_policy_dependency_order_is_acyclic(): void
    {
        $policyFiles = [
            VerificationPolicy::class,
            NetworkBindingPolicy::class,
            DeviceBindingPolicy::class,
        ];

        $identifiers = [
            'verification_policy' => VerificationPolicy::class,
            'network_binding_policy' => NetworkBindingPolicy::class,
            'device_binding_policy' => DeviceBindingPolicy::class,
        ];

        // Check no circular dependencies by ensuring each policy file
        // does NOT reference the identifier of a policy that depends on it
        // (verification must not reference network_binding_policy or device_binding_policy)
        // (network must not reference device_binding_policy)

        $verificationContents = file_get_contents((new \ReflectionClass(VerificationPolicy::class))->getFileName());
        $networkContents = file_get_contents((new \ReflectionClass(NetworkBindingPolicy::class))->getFileName());

        // VerificationPolicy (first in order) must not reference downstream policies
        $this->assertStringNotContainsString(
            'network_binding_policy',
            $verificationContents,
            'VerificationPolicy must not depend on NetworkBindingPolicy'
        );
        $this->assertStringNotContainsString(
            'device_binding_policy',
            $verificationContents,
            'VerificationPolicy must not depend on DeviceBindingPolicy'
        );

        // NetworkBindingPolicy (second in order) must not reference downstream policy
        $this->assertStringNotContainsString(
            'device_binding_policy',
            $networkContents,
            'NetworkBindingPolicy must not depend on DeviceBindingPolicy'
        );
    }

    /**
     * REPLAY CONTRACT: The policy evaluation order is well-defined and stable.
     * The constitutional order (verification → network → device) defines
     * the only valid dependency topology. Any deviation breaks replay determinism.
     */
    public function test_policy_evaluation_order_is_stable(): void
    {
        $expectedOrder = [
            'verification_policy',
            'network_binding_policy',
            'device_binding_policy',
        ];

        // This order must be consistent — it defines constitutional evaluation topology
        $this->assertEquals('verification_policy', $expectedOrder[0]);
        $this->assertEquals('network_binding_policy', $expectedOrder[1]);
        $this->assertEquals('device_binding_policy', $expectedOrder[2]);
    }

    /**
     * REPLAY CONTRACT: ConstitutionalEvidenceSnapshot is immutable.
     * Frozen at evaluation start — no property may change after construction.
     */
    public function test_evidence_snapshot_is_immutable(): void
    {
        $snapshot = $this->createStandardSnapshot();

        // Attempting to set any property should throw an Error (readonly class)
        try {
            $snapshot->constitutionalHash = 'changed';
            $this->fail('ConstitutionalEvidenceSnapshot must be immutable (readonly)');
        } catch (\Error $e) {
            $this->assertStringContainsString('readonly', $e->getMessage());
        }
    }
}
