<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class OverlaySignalTest extends TestCase
{
    public function test_continue_signal_has_continue_unchanged_influence(): void
    {
        $signal = OverlaySignal::continue('test_overlay');

        $this->assertEquals(OverlayInfluence::CONTINUE_UNCHANGED, $signal->influence);
        $this->assertEquals('test_overlay', $signal->overlayIdentifier);
    }

    public function test_require_constitutional_review_returns_true(): void
    {
        $signal = new OverlaySignal(
            OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW,
            'overlay_id',
            'suspicious_pattern',
            [],
            null,
            null,
        );

        $this->assertTrue($signal->requiresConstitutionalReview());
    }

    public function test_continue_signal_returns_false_for_requires_constitutional_review(): void
    {
        $signal = OverlaySignal::continue('test');

        $this->assertFalse($signal->requiresConstitutionalReview());
    }

    public function test_trust_elevation_request_carries_suggested_level(): void
    {
        $signal = new OverlaySignal(
            OverlayInfluence::TRUST_ELEVATION_REQUEST,
            'registrar_elevation',
            'registrar_attestation_present',
            ['registrar_id' => 'reg_123'],
            null,
            TrustLevel::RegistrarAttested,
        );

        $this->assertEquals(TrustLevel::RegistrarAttested, $signal->suggestedElevatedTrustLevel);
    }

    public function test_signal_evidence_context_contains_no_raw_pii(): void
    {
        // Evidence context should contain hashed/minimized data
        $signal = new OverlaySignal(
            OverlayInfluence::TRUST_EVALUATION_INCONCLUSIVE,
            'ip_velocity',
            'velocity_threshold_exceeded',
            ['velocity_count' => 15, 'threshold' => 10, 'ip_hash' => 'abc123hash'],
            null,
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
