<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlays\IpVelocityOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use App\Models\ElectionSecurityEvent;
use Tests\TestCase;

class IpVelocityOverlayTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeContext(?Election $election = null, string $ipHash = 'test_hash'): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence($ipHash, null, null, 6, 1, true, 'ip_count'),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity('s1', $ipHash, $ipHash, false, 'continuous'),
        );
    }

    public function test_evaluate_returns_continue_when_velocity_below_threshold(): void
    {
        $overlay = new IpVelocityOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext($election, 'hash_abc');

        // No recent events, velocity is 0
        $signal = $overlay->evaluate($ctx);

        $this->assertEquals(OverlayInfluence::CONTINUE_UNCHANGED, $signal->influence);
    }

    public function test_evaluate_returns_inconclusive_when_velocity_exceeds_threshold(): void
    {
        $overlay = new IpVelocityOverlay();
        $election = Election::factory()->create();
        $ipHash = 'hash_velocity_test';
        $ctx = $this->makeContext($election, $ipHash);

        // Create 10 security events with matching IP hash
        for ($i = 0; $i < 10; $i++) {
            ElectionSecurityEvent::create([
                'event_type' => 'trust_allowed',
                'election_id' => $election->id,
                'voter_slug_id' => null,
                'network_evidence' => json_encode(['currentIpHash' => $ipHash]),
                'device_evidence' => json_encode([]),
                'trust_level_before' => 'unverified',
                'trust_level_after' => 'attested',
                'policy_evaluated' => 'verification_attestation_policy',
                'overlay_applied' => null,
                'policy_evaluation_sequence' => json_encode([]),
                'overlay_influence_chain' => json_encode([]),
                'trust_state_transition' => 'unverified → attested',
                'final_constitutional_outcome' => 'allow',
                'recorded_at' => now()->subSeconds(50 + $i), // Within 5-minute window
            ]);
        }

        $signal = $overlay->evaluate($ctx);

        // With 10 events and threshold of 10, should signal inconclusive
        $this->assertEquals(OverlayInfluence::TRUST_EVALUATION_INCONCLUSIVE, $signal->influence);
    }

    public function test_evaluate_returns_continue_when_events_outside_window(): void
    {
        $overlay = new IpVelocityOverlay();
        $election = Election::factory()->create();
        $ipHash = 'hash_outside_window';
        $ctx = $this->makeContext($election, $ipHash);

        // Create events outside 5-minute window
        for ($i = 0; $i < 10; $i++) {
            ElectionSecurityEvent::create([
                'event_type' => 'trust_allowed',
                'election_id' => $election->id,
                'voter_slug_id' => null,
                'network_evidence' => json_encode(['currentIpHash' => $ipHash]),
                'device_evidence' => json_encode([]),
                'trust_level_before' => 'unverified',
                'trust_level_after' => 'attested',
                'policy_evaluated' => 'verification_attestation_policy',
                'overlay_applied' => null,
                'policy_evaluation_sequence' => json_encode([]),
                'overlay_influence_chain' => json_encode([]),
                'trust_state_transition' => 'unverified → attested',
                'final_constitutional_outcome' => 'allow',
                'recorded_at' => now()->subMinutes(10), // Outside 5-minute window
            ]);
        }

        $signal = $overlay->evaluate($ctx);

        // Events outside window should not count
        $this->assertEquals(OverlayInfluence::CONTINUE_UNCHANGED, $signal->influence);
    }

    public function test_identifier_matches_registry(): void
    {
        $overlay = new IpVelocityOverlay();

        $this->assertEquals('ip_velocity', $overlay->identifier());
    }
}
