<?php

namespace Tests\Unit\Application\Election\Security\Overlays;

use App\Application\Election\Security\Overlays\IpVelocityOverlay;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use App\Models\ElectionSecurityEvent;
use Tests\TestCase;

class IpVelocityOverlayTest extends TestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    private function makeClock(): \App\Domain\Shared\Clock\ClockInterface
    {
        return new class implements \App\Domain\Shared\Clock\ClockInterface {
            public function now(): \DateTimeImmutable
            {
                return new \DateTimeImmutable();
            }
        };
    }

    private function makeOverlay(): IpVelocityOverlay
    {
        return new IpVelocityOverlay($this->makeClock());
    }

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

    public function test_reports_stable_when_velocity_normal(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext($election, 'hash_abc');

        // No recent events, velocity is normal
        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('ip_velocity', $signal->overlayIdentifier);
    }

    public function test_reports_attestation_when_velocity_threshold_exceeded(): void
    {
        $overlay = $this->makeOverlay();
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

        // STEP A.3 FIX F3: Velocity breach emits EVIDENCE_INCONSISTENT (network anomaly), not attestation
        // This test was testing the OLD buggy behavior — now corrected
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
        $this->assertEquals('ip_velocity', $signal->overlayIdentifier);
    }

    public function test_reports_stable_when_events_outside_aggregation_window(): void
    {
        $overlay = $this->makeOverlay();
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

        // Events outside aggregation window should not be counted
        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    public function test_identifier_matches_registry(): void
    {
        $overlay = $this->makeOverlay();

        $this->assertEquals('ip_velocity', $overlay->identifier());
    }

    // ============================================================================
    // GROUP 4 — SIMPLIFIED ARCHITECTURE TESTS (D.R.3 Phase A)
    // Tests using new Simplified OverlaySignal types
    // ============================================================================

    /**
     * CRITICAL FIX F3: Velocity breach emits EVIDENCE_INCONSISTENT, not attestation.
     *
     * IP velocity breach indicates network anomaly/continuity uncertainty, NOT attestation.
     * FIXED in STEP A.3: Changed line 44 to `evidenceInconsistent()` instead of `attestationPresent()`.
     *
     * Semantic distinction:
     * - Attestation = artifact available for reverification (device fingerprint, registrar record)
     * - Velocity = network anomaly, not attestation artifact
     */
    public function test_velocity_breach_simplified_emits_evidence_inconsistent_not_attestation(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ipHash = 'hash_velocity_high';
        $ctx = $this->makeContext($election, $ipHash);

        // Create events that exceed velocity threshold
        for ($i = 0; $i < 11; $i++) {
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
                'recorded_at' => now()->subSeconds(50 + $i),
            ]);
        }

        $signal = $overlay->evaluate($ctx);

        // FIXED: Now correctly returns EVIDENCE_INCONSISTENT
        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType,
            'Velocity breach should emit EVIDENCE_INCONSISTENT (network anomaly), not ADDITIONAL_ATTESTATION_PRESENT'
        );
    }

    /**
     * Velocity within limits emits CONTEXT_STABLE (Simplified).
     */
    public function test_velocity_within_limit_simplified_emits_context_stable(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext($election, 'hash_normal');

        // No events, or very few — within normal limits
        $signal = $overlay->evaluate($ctx);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('ip_velocity', $signal->overlayIdentifier);
    }

    /**
     * Signal semantics are appropriate for network context.
     * No procedural signals (deny, elevate) — only observational (stable, inconsistent).
     */
    public function test_velocity_signal_is_observational_not_procedural(): void
    {
        $overlay = $this->makeOverlay();
        $election = Election::factory()->create();
        $ctx = $this->makeContext($election, 'hash_test');

        $signal = $overlay->evaluate($ctx);

        // Must be observational, never procedural
        $this->assertTrue(
            in_array($signal->signalType, ['CONTEXT_STABLE', 'EVIDENCE_INCONSISTENT']),
            'Network velocity signal must be observational (stable or inconsistent), never procedural'
        );

        // Must NOT contain procedural keywords
        $this->assertStringNotContainsStringIgnoringCase('deny', $signal->signalType);
        $this->assertStringNotContainsStringIgnoringCase('elevate', $signal->signalType);
        $this->assertStringNotContainsStringIgnoringCase('recommend', $signal->signalType);
        $this->assertStringNotContainsStringIgnoringCase('escalate', $signal->signalType);
    }
}
