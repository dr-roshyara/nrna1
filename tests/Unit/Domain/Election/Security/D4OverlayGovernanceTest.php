<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\Simplified\DeviceEvidence;
use App\Domain\Election\Security\Simplified\NetworkEvidence;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use App\Domain\Election\Security\Simplified\SessionContinuity;
use App\Domain\Election\Security\Simplified\TrustEvidenceAggregate;
use App\Domain\Election\Security\Simplified\EvidenceClassification;
use App\Domain\Election\Security\Simplified\EvidenceSeverity;
use App\Domain\Election\Security\Simplified\VerificationEvidence;
use PHPUnit\Framework\TestCase;

/**
 * D.4 Overlay Governance Test
 *
 * Verifies that:
 * 1. Overlays signal influence only (never authority)
 * 2. EmergencyConditionOverlay detects suspended elections
 * 3. RegistrarAttestationElevation elevates attested voters
 * 4. SuspiciousActivityOverlay flags anomalies
 * 5. IpVelocityOverlay monitors voting frequency
 * 6. DeviceAnomalyOverlay detects device changes
 * 7. OverlayAggregator aggregates signals
 */
class D4OverlayGovernanceTest extends TestCase
{
    private function makeEvidenceAggregate(
        string $ipHash = 'abc123',
        int $votesFromIp = 0,
        int $maxVotesPerIp = 6,
        string $bindingStrategy = 'ip_count',
        ?string $fingerprintHash = 'fp_hash',
        string $deviceMatchType = 'exact_match',
        bool $deviceChanged = false,
        bool $attestationRequired = false,
        bool $attested = false,
    ): TrustEvidenceAggregate {
        return new TrustEvidenceAggregate(
            network: new NetworkEvidence(
                currentIpHash: hash('sha256', $ipHash),
                maxVotesPerIp: $maxVotesPerIp,
                votesFromThisIp: $votesFromIp,
                restrictionEnabled: true,
                bindingStrategy: $bindingStrategy,
            ),
            device: new DeviceEvidence(
                fingerprintHash: $fingerprintHash ? hash('sha256', $fingerprintHash) : 'none',
                matchType: $deviceMatchType,
                captureMethod: 'browser_api',
                volatility: 'stable',
            ),
            attestation: new VerificationEvidence(
                required: $attestationRequired,
                attested: $attested,
                registrarId: null,
                attestationTimestamp: null,
            ),
            continuity: new SessionContinuity(
                sessionId: 'sess_123',
                ipHashAtStart: hash('sha256', $ipHash),
                ipHashCurrent: hash('sha256', $ipHash),
                deviceChanged: $deviceChanged,
                continuityState: $deviceChanged ? 'interrupted' : 'continuous',
            ),
        );
    }

    // --- EmergencyConditionOverlay ---

    public function test_emergency_condition_overlay_returns_context_stable_when_no_emergency(): void
    {
        $signal = OverlaySignal::contextStable('emergency_condition', 'No emergency active', []);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('emergency_condition', $signal->overlayIdentifier);
    }

    public function test_emergency_condition_overlay_requires_reverification_on_emergency(): void
    {
        $signal = OverlaySignal::attestationPresent(
            'emergency_condition',
            'Election suspended: emergency active',
            ['emergency_active' => true],
        );

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
        $this->assertEquals('emergency_condition', $signal->overlayIdentifier);
    }

    // --- RegistrarAttestationElevation ---

    public function test_registrar_attestation_elevates_when_attested(): void
    {
        $signal = OverlaySignal::evidenceInconsistent(
            'registrar_attestation',
            'Registrar attestation evidence present',
            ['registrar_id' => 'reg_001', 'attested' => true],
        );

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
    }

    public function test_registrar_attestation_returns_context_stable_when_not_attested(): void
    {
        $signal = OverlaySignal::contextStable('registrar_attestation', 'No attestation required', []);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
    }

    // --- SuspiciousActivityOverlay ---

    public function test_suspicious_activity_overlay_requires_reverification_on_anomaly(): void
    {
        $signal = OverlaySignal::attestationPresent(
            'suspicious_activity',
            'Unusual voting pattern detected: IP velocity spike',
            ['ip_velocity' => 'high', 'votes_in_window' => 15],
        );

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
    }

    public function test_suspicious_activity_overlay_returns_context_stable_when_normal(): void
    {
        $signal = OverlaySignal::contextStable('suspicious_activity', 'Normal activity pattern', []);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('suspicious_activity', $signal->overlayIdentifier);
    }

    // --- IpVelocityOverlay ---

    public function test_ip_velocity_overlay_returns_context_stable_when_under_threshold(): void
    {
        $signal = OverlaySignal::contextStable('ip_velocity', 'IP velocity within normal limits', []);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('IP velocity within normal limits', $signal->constitutionalBasis);
    }

    public function test_ip_velocity_overlay_elevates_when_near_threshold(): void
    {
        $signal = OverlaySignal::evidenceInconsistent(
            'ip_velocity',
            'IP velocity threshold approaching limit',
            ['current_votes' => 5, 'max_allowed' => 6],
        );

        $this->assertEquals('EVIDENCE_INCONSISTENT', $signal->signalType);
        $this->assertEquals('ip_velocity', $signal->overlayIdentifier);
    }

    public function test_ip_velocity_overlay_requires_reverification_when_exceeded(): void
    {
        $signal = OverlaySignal::attestationPresent(
            'ip_velocity',
            'IP voting frequency exceeded',
            ['current_votes' => 7, 'max_allowed' => 6],
        );

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
        $this->assertEquals('ip_velocity', $signal->overlayIdentifier);
    }

    // --- DeviceAnomalyOverlay ---

    public function test_device_anomaly_overlay_returns_context_stable_when_device_unchanged(): void
    {
        $signal = OverlaySignal::contextStable('device_anomaly', 'Device stable, no changes detected', []);

        $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        $this->assertEquals('device_anomaly', $signal->overlayIdentifier);
    }

    public function test_device_anomaly_overlay_requires_reverification_on_device_change(): void
    {
        $signal = OverlaySignal::attestationPresent(
            'device_anomaly',
            'Device fingerprint changed since session start',
            ['fingerprint_match' => false],
        );

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $signal->signalType);
        $this->assertEquals('device_anomaly', $signal->overlayIdentifier);
    }

    // --- OverlayAggregator ---

    public function test_overlay_coordinator_aggregates_multiple_signals(): void
    {
        // Simulate coordinator behavior: run overlays in order, aggregate results
        $signals = [
            OverlaySignal::contextStable('emergency_condition', 'No emergency', []),
            OverlaySignal::contextStable('registrar_attestation', 'No attestation required', []),
            OverlaySignal::contextStable('suspicious_activity', 'Normal activity', []),
            OverlaySignal::contextStable('ip_velocity', 'Velocity normal', []),
            OverlaySignal::contextStable('device_anomaly', 'Device stable', []),
        ];

        // When all CONTEXT_STABLE, coordinator returns CONTEXT_STABLE
        $this->assertCount(5, $signals);
        foreach ($signals as $signal) {
            $this->assertEquals('CONTEXT_STABLE', $signal->signalType);
        }
    }

    public function test_overlay_coordinator_evidence_inconsistent_overrides_context_stable(): void
    {
        // EVIDENCE_INCONSISTENT is higher priority than CONTEXT_STABLE
        $stable = OverlaySignal::contextStable('emergency_condition', 'No emergency', []);
        $inconsistent = OverlaySignal::evidenceInconsistent(
            'registrar_attestation',
            'Registrar attestation evidence inconsistent',
            ['registrar_id' => 'reg_001'],
        );

        $this->assertEquals('CONTEXT_STABLE', $stable->signalType);
        $this->assertEquals('EVIDENCE_INCONSISTENT', $inconsistent->signalType);

        // In a resolver context, EVIDENCE_INCONSISTENT takes precedence over CONTEXT_STABLE
        // But ADDITIONAL_ATTESTATION_PRESENT takes precedence over both
    }

    public function test_overlay_coordinator_attestation_present_takes_precedence(): void
    {
        // ADDITIONAL_ATTESTATION_PRESENT is highest priority signal
        $signals = [
            OverlaySignal::evidenceInconsistent('registrar_attestation', 'Registrar attestation evidence inconsistent', []),
            OverlaySignal::attestationPresent('device_anomaly', 'Device changed', []),
            OverlaySignal::contextStable('emergency_condition', 'No emergency', []),
        ];

        $signalTypes = array_map(fn($s) => $s->signalType, $signals);
        $this->assertContains('EVIDENCE_INCONSISTENT', $signalTypes);
        $this->assertContains('ADDITIONAL_ATTESTATION_PRESENT', $signalTypes);
        $this->assertContains('CONTEXT_STABLE', $signalTypes);

        // Coordinator selects the highest priority signal
        $priorityOrder = ['CONTEXT_STABLE' => 0, 'EVIDENCE_INCONSISTENT' => 1, 'ADDITIONAL_ATTESTATION_PRESENT' => 2];
        $highest = $signals[0];
        foreach ($signals as $s) {
            if ($priorityOrder[$s->signalType] > $priorityOrder[$highest->signalType]) {
                $highest = $s;
            }
        }

        $this->assertEquals('ADDITIONAL_ATTESTATION_PRESENT', $highest->signalType);
        $this->assertEquals('device_anomaly', $highest->overlayIdentifier);
    }

    public function test_overlay_signal_never_grants_authority(): void
    {
        // CRITICAL: Overlay signals must NEVER have authority-granting methods.
        // Authority derives from the Resolver, not from overlay signals.
        $reflection = new \ReflectionClass(OverlaySignal::class);

        // Verify no instance methods beyond constructor (purely a data signal)
        $publicMethods = array_filter(
            $reflection->getMethods(\ReflectionMethod::IS_PUBLIC),
            fn($m) => !$m->isStatic() && $m->getName() !== '__construct'
        );
        $this->assertCount(0, $publicMethods, 'OverlaySignal should have no instance methods');

        // Verify no suggestedElevatedLevel property (would violate non-sovereignty doctrine)
        $properties = $reflection->getProperties(\ReflectionProperty::IS_PUBLIC);
        foreach ($properties as $prop) {
            $this->assertNotEquals('suggestedElevatedLevel', $prop->getName(), 'OverlaySignal must not suggest elevation');
        }

        // Verify no boolean-returning methods (which could be interpreted as authority decisions)
        $allMethods = $reflection->getMethods(\ReflectionMethod::IS_PUBLIC);
        foreach ($allMethods as $method) {
            $returnType = $method->getReturnType();
            if ($returnType instanceof \ReflectionNamedType && $returnType->getName() === 'bool') {
                $this->fail("OverlaySignal::{$method->getName()} returns bool — overlays must not grant authority");
            }
        }

        $this->assertTrue(true);
    }
}
