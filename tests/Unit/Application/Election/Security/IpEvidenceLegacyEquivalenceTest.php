<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Application\Election\Security\Overlays\NetworkContinuityObservation;
use App\Application\Election\Security\Overlays\ParticipationDensityObservation;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use Tests\TestCase;

/**
 * GROUP 5 — Constitutional Equivalence: Legacy → Evidence Migration (REDESIGNED)
 *
 * Focus: Proving participation law is preserved during semantic rewiring
 * NOT: Performance or backward compatibility
 *
 * CRITICAL: This group tests at Layer 1 (observational signals), NOT Layer 2 (resolver).
 * Overlay signals demonstrate observational equivalence to legacy behavior.
 * Layer 2 sovereign equivalence is tested separately in Group 8.
 *
 * Constitutional doctrine: Legacy procedural sovereignty = Constitutional sovereign legitimacy
 *
 * These tests prove that overlay observation signals match the legacy decision outcomes:
 * - IP mismatch (legacy blocks) = NetworkContinuity EVIDENCE_INCONSISTENT (evidence says "mismatch")
 * - IP match (legacy allows) = NetworkContinuity CONTEXT_STABLE (evidence says "match")
 * - Frequency >= limit (legacy blocks) = ParticipationDensity EVIDENCE_INCONSISTENT (evidence says "exceeded")
 * - Frequency < limit (legacy allows) = ParticipationDensity CONTEXT_STABLE (evidence says "within")
 */
class IpEvidenceLegacyEquivalenceTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private NetworkContinuityObservation $continuityOverlay;
    private ParticipationDensityObservation $densityOverlay;

    protected function setUp(): void
    {
        parent::setUp();

        $this->continuityOverlay = new NetworkContinuityObservation();
        $this->densityOverlay = new ParticipationDensityObservation();
    }

    private function makeContinuityContext(
        ?string $registeredIpHash = null,
        ?string $currentIpHash = null,
        string $bindingStrategy = 'ip_strict',
        ?Election $election = null
    ): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: $currentIpHash ?? 'net_current_123',
                registeredIpHash: $registeredIpHash,
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 0,
                restrictionEnabled: $bindingStrategy !== 'none',
                bindingStrategy: $bindingStrategy,
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                's1',
                $registeredIpHash ?? 'net_current_123',
                $currentIpHash ?? 'net_current_123',
                false,
                'continuous'
            ),
        );
    }

    private function makeDensityContext(
        int $votesFromThisIp = 2,
        int $maxVotesPerIp = 6,
        bool $restrictionEnabled = true,
        ?Election $election = null
    ): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'net_test_123',
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: $maxVotesPerIp,
                votesFromThisIp: $votesFromThisIp,
                restrictionEnabled: $restrictionEnabled,
                bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                's1', 'net_test_123', 'net_test_123', false, 'continuous'
            ),
        );
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 5.1-5.2: IP Continuity Equivalence
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_legacy_ip_mismatch_equals_evidence_continuity_inconsistent(): void
    {
        // Legacy: validateVotingIpWithResponse() renders Inertia block when User.voting_ip !== request.ip()
        // Evidence: NetworkContinuity observation emits EVIDENCE_INCONSISTENT when hashes differ

        $election = Election::factory()->create();

        $ctx = $this->makeContinuityContext(
            registeredIpHash: 'net_legacy_abc123',
            currentIpHash: 'net_different_def456',
            election: $election
        );

        $signal = $this->continuityOverlay->evaluate($ctx);

        // Both paths should observe inconsistency
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    public function test_legacy_ip_match_equals_evidence_continuity_stable(): void
    {
        // Legacy: validateVotingIpWithResponse() allows when User.voting_ip === request.ip()
        // Evidence: NetworkContinuity observation emits CONTEXT_STABLE when hashes match

        $election = Election::factory()->create();

        $ctx = $this->makeContinuityContext(
            registeredIpHash: 'net_legacy_abc123',
            currentIpHash: 'net_legacy_abc123',
            election: $election
        );

        $signal = $this->continuityOverlay->evaluate($ctx);

        // Both paths should observe stability
        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 5.3-5.4: IP Frequency Limit Equivalence
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_legacy_ip_frequency_limit_equals_evidence_density_threshold(): void
    {
        // Legacy: check_ip_address() blocks when count >= max_use_clientIP
        // Evidence: ParticipationDensity observation emits EVIDENCE_INCONSISTENT when votesFromThisIp >= maxVotesPerIp
        // CONSTITUTIONAL IMPROVEMENT: check_ip_address() was global (all elections); constitutional path is election-scoped.
        // This is a deliberate improvement, not a mismatch.

        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        // At threshold (legacy would block, evidence should too)
        $ctx = $this->makeDensityContext(
            votesFromThisIp: 6,
            maxVotesPerIp: 6,
            election: $election
        );

        $signal = $this->densityOverlay->evaluate($ctx);

        // Both paths should observe density exceedance
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    public function test_legacy_ip_within_limit_equals_evidence_density_allowed(): void
    {
        // Legacy: check_ip_address() allows when count < max_use_clientIP
        // Evidence: ParticipationDensity observation emits CONTEXT_STABLE when votesFromThisIp < maxVotesPerIp

        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        $ctx = $this->makeDensityContext(
            votesFromThisIp: 3,
            maxVotesPerIp: 6,
            election: $election
        );

        $signal = $this->densityOverlay->evaluate($ctx);

        // Both paths should observe stability
        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 5.5-5.6: Boundary Condition Equivalence
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_legacy_boundary_one_below_threshold_equals_evidence(): void
    {
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        $ctx = $this->makeDensityContext(
            votesFromThisIp: 5,
            maxVotesPerIp: 6,
            election: $election
        );

        $signal = $this->densityOverlay->evaluate($ctx);

        // Evidence path should also emit stability (one below threshold)
        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    public function test_legacy_boundary_at_exact_threshold_equals_evidence(): void
    {
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        $ctx = $this->makeDensityContext(
            votesFromThisIp: 6,
            maxVotesPerIp: 6,
            election: $election
        );

        $signal = $this->densityOverlay->evaluate($ctx);

        // Evidence path should also emit inconsistency (at exact threshold)
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 5.7-5.9: Combined Constraint Equivalence
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_legacy_combined_ip_mismatch_and_frequency_exceeds(): void
    {
        // Multiple observational insufficiencies do NOT imply ranked authority escalation.
        // Two insufficiencies = plural evidence; NOT 'critical risk' escalation

        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        // Build context with both constraints violated
        $ctx = new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'net_different_456',
                registeredIpHash: 'net_abc123',
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 10,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                's1', 'net_abc123', 'net_different_456', false, 'continuous'
            ),
        );

        // Both overlays should emit EVIDENCE_INCONSISTENT (unordered set)
        $continuitySignal = $this->continuityOverlay->evaluate($ctx);
        $densitySignal = $this->densityOverlay->evaluate($ctx);

        $signals = [$continuitySignal->signalType, $densitySignal->signalType];
        sort($signals);

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signals[0]);
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signals[1]);
    }

    public function test_legacy_ip_match_but_frequency_exceeds(): void
    {
        // IP continuity satisfied; density constraint independently violated

        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        $ctx = new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'net_abc123',
                registeredIpHash: 'net_abc123',
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 7,
                restrictionEnabled: true,
                bindingStrategy: 'ip_strict',
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                's1', 'net_abc123', 'net_abc123', false, 'continuous'
            ),
        );

        // Continuity should emit CONTEXT_STABLE (hashes match)
        $continuitySignal = $this->continuityOverlay->evaluate($ctx);
        $this->assertEquals('CONTEXT_STABLE', $continuitySignal->signalType);

        // Density should emit EVIDENCE_INCONSISTENT (count > max)
        $densitySignal = $this->densityOverlay->evaluate($ctx);
        $this->assertEquals('EVIDENCE_INCONSISTENT', $densitySignal->signalType);
    }

    public function test_legacy_no_constraints_active(): void
    {
        $election = Election::factory()->create(['max_votes_per_ip' => 6, 'network_binding_strategy' => 'none']);

        // No IP constraints
        $ctx = new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'net_current',
                registeredIpHash: null, // No constraint
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 100, // Way over, but constraint disabled
                restrictionEnabled: false,
                bindingStrategy: 'none',
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity('s1', 'net_current', 'net_current', false, 'continuous'),
        );

        // Both overlays should emit CONTEXT_STABLE (no constraints active)
        $continuitySignal = $this->continuityOverlay->evaluate($ctx);
        $densitySignal = $this->densityOverlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $continuitySignal->signalType);
        $this->assertEquals('CONTEXT_STABLE', $densitySignal->signalType);
    }
}
