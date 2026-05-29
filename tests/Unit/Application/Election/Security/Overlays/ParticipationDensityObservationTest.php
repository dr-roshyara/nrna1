<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\TrustCapabilityContext;
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
 * GROUP 2 — Participation Density Evidence Tests
 *
 * Focus: Participation concentration constraints
 * NOT: Rate limiting or anti-fraud detection
 *
 * Constitutional relationship:
 * Participation from single network should not concentrate beyond
 * constitutional threshold. Density observation informs whether
 * concentration risk exists, but is purely observational.
 *
 * Constitutional semantics:
 * - density_within_constraint: participation distributed acceptably
 * - density_exceeds_constraint: participation concentrated beyond threshold
 */
class ParticipationDensityObservationTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeOverlay(): ParticipationDensityObservation
    {
        return new ParticipationDensityObservation();
    }

    private function makeContext(
        int $votesFromThisNetwork = 0,
        int $maxVotesPerNetwork = 6,
        string $networkHash = 'net_test_123',
        ?Election $election = null
    ): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: $networkHash,
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: $maxVotesPerNetwork,
                votesFromThisIp: $votesFromThisNetwork,
                restrictionEnabled: true,
                bindingStrategy: 'ip_count',
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity(
                's1', $networkHash, $networkHash, false, 'continuous'
            ),
        );
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 2.1-2.3: Density Constraint Semantic Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_participation_density_within_threshold_emits_context_stable(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);
        $ctx = $this->makeContext(votesFromThisNetwork: 3, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('participation_density', $signal->overlayIdentifier);
    }

    public function test_participation_density_equals_threshold_emits_evidence_inconsistent(): void
    {
        // Legacy `>= max_use_clientIP` semantics preserved
        // Semantic pressure: future vocab will be `PARTICIPATION_DENSITY_EXCEEDED`

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);
        $ctx = $this->makeContext(votesFromThisNetwork: 6, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
        $this->assertEquals('participation_density', $signal->overlayIdentifier);
    }

    public function test_participation_density_exceeds_threshold_emits_evidence_inconsistent(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);
        $ctx = $this->makeContext(votesFromThisNetwork: 10, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 2.4-2.5: Boundary Transition Determinism
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_participation_density_boundary_below_threshold(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);
        $ctx = $this->makeContext(votesFromThisNetwork: 5, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    public function test_participation_density_boundary_at_threshold(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);
        $ctx = $this->makeContext(votesFromThisNetwork: 6, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 2.6-2.7: Constraint Disabled Tests
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_participation_density_disabled_emits_context_stable(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['network_binding_strategy' => 'none']);

        $ctx = new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'net_test',
                registeredIpHash: null,
                whitelist: null,
                maxVotesPerIp: 6,
                votesFromThisIp: 20, // Way over, but constraint disabled
                restrictionEnabled: false,
                bindingStrategy: 'none',
            ),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity('s1', 'net_test', 'net_test', false, 'continuous'),
        );

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    public function test_participation_density_zero_votes_stable(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(votesFromThisNetwork: 0, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 2.8-2.9: Evidence Context Preservation
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_participation_density_preserves_vote_count_evidence(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);
        $ctx = $this->makeContext(votesFromThisNetwork: 4, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertArrayHasKey('votesFromThisNetwork', $signal->evidenceContext);
        $this->assertArrayHasKey('maxVotesAllowed', $signal->evidenceContext);
        $this->assertEquals(4, $signal->evidenceContext['votesFromThisNetwork']);
        $this->assertEquals(6, $signal->evidenceContext['maxVotesAllowed']);
    }

    public function test_participation_density_constitutional_basis_documents_constraint(): void
    {

        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext(votesFromThisNetwork: 7, maxVotesPerNetwork: 6, election: $election);

        $signal = $overlay->evaluate($ctx);

        $this->assertNotEmpty($signal->constitutionalBasis);
        $this->assertStringContainsString('density', strtolower($signal->constitutionalBasis));
    }

    // ════════════════════════════════════════════════════════════════════════════════════
    // 2.10: Identifier Registry
    // ════════════════════════════════════════════════════════════════════════════════════

    public function test_participation_density_identifier_registered(): void
    {

        $overlay = $this->makeOverlay();

        $this->assertEquals('participation_density', $overlay->identifier());
    }
}
