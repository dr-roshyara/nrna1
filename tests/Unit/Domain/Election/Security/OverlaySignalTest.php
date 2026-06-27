<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\EvidenceWeightCategory;
use App\Domain\Election\Security\OverlaySignal;
use App\Domain\Election\Security\RecommendedProceduralPath;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class OverlaySignalTest extends TestCase
{
    public function test_no_influence_signal_returns_all_neutral_values(): void
    {
        $signal = OverlaySignal::noInfluence('test_overlay');

        $this->assertEquals(ConstitutionalConcernLevel::NONE, $signal->concernLevel);
        $this->assertEquals(EvidenceWeightCategory::STRONG, $signal->evidenceWeight);
        $this->assertEquals(RecommendedProceduralPath::CONTINUE, $signal->proceduralPath);
        $this->assertEquals('test_overlay', $signal->overlayIdentifier);
        $this->assertNull($signal->suggestedElevatedLevel);
    }

    public function test_defer_to_governance_signal_indicates_review_required(): void
    {
        $signal = new OverlaySignal(
            ConstitutionalConcernLevel::CRITICAL,
            EvidenceWeightCategory::STRONG,
            RecommendedProceduralPath::DEFER_TO_GOVERNANCE,
            'overlay_id',
            'suspicious_pattern',
            [],
            null,
        );

        $this->assertEquals(RecommendedProceduralPath::DEFER_TO_GOVERNANCE, $signal->proceduralPath);
        $this->assertEquals(ConstitutionalConcernLevel::CRITICAL, $signal->concernLevel);
    }

    public function test_continue_signal_has_no_concern(): void
    {
        $signal = OverlaySignal::noInfluence('test');

        $this->assertEquals(RecommendedProceduralPath::CONTINUE, $signal->proceduralPath);
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $signal->concernLevel);
    }

    public function test_trust_elevation_request_carries_suggested_level(): void
    {
        $signal = new OverlaySignal(
            ConstitutionalConcernLevel::NONE,
            EvidenceWeightCategory::DEFINITIVE,
            RecommendedProceduralPath::ELEVATED_SCRUTINY,
            'registrar_elevation',
            'registrar_attestation_present',
            ['registrar_id' => 'reg_123'],
            TrustLevel::RegistrarAttested,
        );

        $this->assertEquals(TrustLevel::RegistrarAttested, $signal->suggestedElevatedLevel);
        $this->assertEquals(RecommendedProceduralPath::ELEVATED_SCRUTINY, $signal->proceduralPath);
    }

    public function test_signal_evidence_context_contains_no_raw_pii(): void
    {
        // Evidence context should contain hashed/minimized data
        $signal = new OverlaySignal(
            ConstitutionalConcernLevel::LOW,
            EvidenceWeightCategory::WEAK,
            RecommendedProceduralPath::REQUEST_REVERIFICATION,
            'ip_velocity',
            'velocity_threshold_exceeded',
            ['velocity_count' => 15, 'threshold' => 10, 'ip_hash' => 'abc123hash'],
            null,
        );

        // Verify structure is correct, no raw IP strings
        $this->assertIsArray($signal->evidenceContext);
        $this->assertNotEmpty($signal->evidenceContext);
        // Verify no raw IP addresses (e.g. 192.168.1.1)
        $context_str = json_encode($signal->evidenceContext);
        $this->assertStringNotContainsString('.', $context_str); // No dotted IPs
    }
}
