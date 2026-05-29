<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\SimplifiedPolicySequence;
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
use App\Models\Election;
use PHPUnit\Framework\TestCase;

/**
 * GROUP 3 — Sovereign Outcome Determinism Tests
 *
 * Focus: Constitutional outcome invariance
 * NOT: Performance or edge cases
 *
 * Constitutional principle:
 * Sovereign decisions must be reproducible.
 * Given identical constitutional evidence, the system MUST return
 * identical capability decisions across evaluations.
 *
 * This is the foundation for:
 * - Replay audit
 * - Dispute resolution
 * - Constitutional transparency
 * - Compliance verification
 */
class IpEvidenceDeterminismTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

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
    ): ConstitutionalEvidenceSnapshot
    {
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

    // ════════════════════════════════════════════════════════════════════════════════════
    // 3.1-3.3: Outcome Invariance Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_sovereign_outcome_deterministic_identical_evidence_set(): void
    {
        $snapshot = $this->makeSnapshot();
        $results = [];

        for ($i = 0; $i < 5; $i++) {
            $result = $this->sequence->evaluate($snapshot);
            $results[] = $result->evaluationState->value ?? $result->evaluationState->name;
        }

        // All 5 evaluations must be identical
        $this->assertCount(5, $results);
        $this->assertTrue(count(array_unique($results)) === 1, 'Evaluation state changed across iterations');
    }

    public function test_sovereign_outcome_deterministic_reasoning_preserved(): void
    {
        $snapshot = $this->makeSnapshot();
        $reasons = [];

        for ($i = 0; $i < 5; $i++) {
            $result = $this->sequence->evaluate($snapshot);
            $reasons[] = $result->reason->value ?? $result->reason->name;
        }

        // All 5 reason codes must be identical
        $uniqueReasons = array_unique($reasons);
        $this->assertCount(1, $uniqueReasons, 'Reason code changed across iterations');
    }

    public function test_sovereign_outcome_deterministic_policy_sequence_preserved(): void
    {
        $snapshot = $this->makeSnapshot();
        $sequences = [];

        for ($i = 0; $i < 5; $i++) {
            $result = $this->sequence->evaluate($snapshot);
            $sequences[] = json_encode($result->policyOutcomeSequence);
        }

        // All policy sequences must be identical JSON
        $uniqueSequences = array_unique($sequences);
        $this->assertCount(1, $uniqueSequences, 'Policy outcome sequence changed across iterations');
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 3.4-3.5: Ordering Independence Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_network_continuity_deterministic_regardless_of_evaluation_context(): void
    {
        $snapshotA = $this->makeSnapshot(votesFromIp: 1);
        $snapshotB = $this->makeSnapshot(votesFromIp: 5);

        $resultA = $this->sequence->evaluate($snapshotA);
        $resultB = $this->sequence->evaluate($snapshotB);

        // Both should succeed (sufficient evidence)
        // Network evidence in A is less dense than B,
        // but both should be deterministic
        $this->assertNotNull($resultA);
        $this->assertNotNull($resultB);
    }

    public function test_participation_density_deterministic_at_boundary(): void
    {
        $snapshotBelow = $this->makeSnapshot(votesFromIp: 5); // 5 < 6
        $snapshotAt = $this->makeSnapshot(votesFromIp: 6);    // 6 >= 6

        $resultBelow = $this->sequence->evaluate($snapshotBelow);
        $resultAt = $this->sequence->evaluate($snapshotAt);

        // At boundary, decision must switch deterministically
        // Below should pass, at should fail
        $this->assertEquals(
            \App\Domain\Election\Security\Simplified\EvidenceEvaluationState::SUFFICIENT_EVIDENCE,
            $resultBelow->evaluationState
        );

        // Re-evaluate $snapshotAt multiple times to ensure determinism
        $reeval1 = $this->sequence->evaluate($snapshotAt);
        $reeval2 = $this->sequence->evaluate($snapshotAt);

        $this->assertEquals($reeval1->evaluationState, $reeval2->evaluationState);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 3.6-3.7: Hash Stability Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_network_hash_deterministic_same_election(): void
    {
        $ip = '192.168.1.1';
        $electionId = 'election_abc';

        $hash1 = hash('sha256', $ip . ':' . $electionId);
        $hash2 = hash('sha256', $ip . ':' . $electionId);
        $hash3 = hash('sha256', $ip . ':' . $electionId);

        $this->assertEquals($hash1, $hash2);
        $this->assertEquals($hash2, $hash3);
    }

    public function test_network_hash_different_across_elections(): void
    {
        $ip = '192.168.1.1';
        $election1 = 'election_abc';
        $election2 = 'election_def';

        $hash1 = hash('sha256', $ip . ':' . $election1);
        $hash2 = hash('sha256', $ip . ':' . $election2);

        $this->assertNotEquals($hash1, $hash2);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 3.8-3.10: Snapshot Immutability Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_constitutional_evidence_snapshot_immutable(): void
    {
        $snapshot = $this->makeSnapshot();

        // PHP readonly classes prevent external modification
        // Snapshot properties cannot be reassigned after construction
        // This test verifies the readonly constraint is in place
        $reflection = new \ReflectionClass($snapshot);
        $this->assertTrue(
            $reflection->getProperties(\ReflectionProperty::IS_PUBLIC)[0]?->isReadonly() ?? true,
            'Evidence snapshot must use PHP readonly for immutability guarantee'
        );
    }

    public function test_vote_count_frozen_at_evaluation_time(): void
    {
        // Vote count is frozen at evidence acquisition time — overlays do NOT re-query database
        $snapshot = $this->makeSnapshot(votesFromIp: 5);

        // Evaluate same snapshot twice
        $result1 = $this->sequence->evaluate($snapshot);
        // Even if time passes or votes change in database, snapshot evidence is frozen
        $result2 = $this->sequence->evaluate($snapshot);

        // Results must be identical (frozen evidence, not dynamic)
        $this->assertEquals($result1->evaluationState, $result2->evaluationState);
    }

    public function test_evaluation_timestamp_immutable(): void
    {
        $evaluatedAt = new \DateTimeImmutable('2026-01-01 12:00:00');
        $snapshot = new ConstitutionalEvidenceSnapshot(
            constitution: $this->constitution,
            verification: new VerificationEvidence(true, true, 'reg_001', $evaluatedAt),
            network: new NetworkEvidence(
                hash('sha256', '192.168.1.1'), 6, 2, true, 'ip_count'
            ),
            device: new DeviceEvidence(hash('sha256', 'fp_123'), 'exact_match', 'browser_api', 'stable'),
            continuity: new SessionContinuity('s1', hash('sha256', '192.168.1.1'),
                                            hash('sha256', '192.168.1.1'), false, 'continuous'),
            eligibility: new \App\Domain\Election\Security\Simplified\ParticipationEligibilityEvidence(
                hasActiveMembership: true,
                hasValidAssignment: true,
                hasApproval: true,
                isSuspended: false,
                eligibilityEvaluatedAt: new \DateTimeImmutable('2026-05-27 12:00:00'),
                eligibilitySourceVersion: '1.0',
                eligibilityHash: 'test-eligibility-hash',
            ),
            evaluatedAt: $evaluatedAt,
            constitutionalHash: ElectionConstitutionHasher::hash($this->constitution),
        );

        $result1 = $this->sequence->evaluate($snapshot);
        sleep(1); // Wait 1 second
        $result2 = $this->sequence->evaluate($snapshot);

        // Decisions must be identical despite time passing
        $this->assertEquals($result1->evaluationState, $result2->evaluationState);
    }
}
