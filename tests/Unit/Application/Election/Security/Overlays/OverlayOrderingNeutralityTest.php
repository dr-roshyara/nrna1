<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\TrustCapabilityContext;
use App\Application\Election\Security\OverlayAggregator;
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
 * GROUP 7a — Overlay Ordering Neutrality Tests
 *
 * Focus: Proving that overlay evaluation order does not affect sovereign outcome
 *
 * Constitutional principle:
 * Overlay registration order MUST remain sovereignty-neutral.
 * The SET of observational signals is what matters, not their evaluation sequence.
 *
 * Determinism guarantee:
 * Evaluating overlays in any order produces SET-equivalent observations.
 * Outcome is determined by signal SET (unordered), not signal sequence.
 *
 * This prevents hidden procedural precedence from creeping into constitutional layer.
 */
class OverlayOrderingNeutralityTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    public function test_overlay_order_network_first_density_second_identical_to_reverse(): void
    {
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        // Build context where both overlays emit EVIDENCE_INCONSISTENT
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

        // Evaluate overlays in order 1: Network, then Density
        $continuityOverlay = new NetworkContinuityObservation();
        $densityOverlay = new ParticipationDensityObservation();

        $signal1 = $continuityOverlay->evaluate($ctx);
        $signal2 = $densityOverlay->evaluate($ctx);

        $set1 = [$signal1->signalType, $signal2->signalType];
        sort($set1);

        // Evaluate overlays in reverse order: Density, then Network
        $signal3 = $densityOverlay->evaluate($ctx);
        $signal4 = $continuityOverlay->evaluate($ctx);

        $set2 = [$signal3->signalType, $signal4->signalType];
        sort($set2);

        // SET equality (unordered) must hold
        $this->assertEquals($set1, $set2, 'Signal SET must be identical regardless of evaluation order');
        $this->assertContains('EVIDENCE_INCONSISTENT', $set1);
    }

    public function test_overlay_stable_ordering_is_order_independent(): void
    {
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

        // Build context: one stable (matched hashes), one inconsistent (exceeded density)
        $ctx = new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence(
                currentIpHash: 'net_abc123',
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
                's1', 'net_abc123', 'net_abc123', false, 'continuous'
            ),
        );

        $continuityOverlay = new NetworkContinuityObservation();
        $densityOverlay = new ParticipationDensityObservation();

        // Order 1: Network, then Density
        $signal1 = $continuityOverlay->evaluate($ctx);
        $signal2 = $densityOverlay->evaluate($ctx);
        $set1 = [$signal1->signalType, $signal2->signalType];
        sort($set1);

        // Order 2: Density, then Network
        $signal3 = $densityOverlay->evaluate($ctx);
        $signal4 = $continuityOverlay->evaluate($ctx);
        $set2 = [$signal3->signalType, $signal4->signalType];
        sort($set2);

        // Signal TYPE SET is identical regardless of order
        $this->assertEquals($set1, $set2);
    }

    public function test_overlay_coordinator_aggregates_order_neutral(): void
    {
        $election = Election::factory()->create(['max_votes_per_ip' => 6]);

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

        // Create two overlays, then build coordinators in different orders
        $continuity = new NetworkContinuityObservation();
        $density = new ParticipationDensityObservation();

        // Coordinator 1: [Network, Density]
        $coordinator1 = new OverlayAggregator([$continuity, $density]);
        $observations1 = $coordinator1->aggregate($ctx);

        // Coordinator 2: [Density, Network] (reversed)
        $coordinator2 = new OverlayAggregator([$density, $continuity]);
        $observations2 = $coordinator2->aggregate($ctx);

        // Extract signal types from both aggregations
        $signals1 = array_map(fn($sig) => $sig->signalType, $observations1->all());
        $signals2 = array_map(fn($sig) => $sig->signalType, $observations2->all());

        sort($signals1);
        sort($signals2);

        // Signal type SET must be identical
        $this->assertEquals($signals1, $signals2);
    }
}
