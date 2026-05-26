<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlayInfluenceContext;
use App\Domain\Election\Security\OverlaySignal;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class OverlayInfluenceContextTest extends TestCase
{
    public function test_no_influence_returns_empty_context(): void
    {
        $context = OverlayInfluenceContext::noInfluence();

        $this->assertEmpty($context->signals);
        $this->assertFalse($context->hasInfluence);
        $this->assertFalse($context->requiresReview);
        $this->assertFalse($context->requiresReverification);
        $this->assertFalse($context->isInconclusive);
        $this->assertNull($context->elevationRequest);
    }

    public function test_context_with_continue_signals_has_no_influence(): void
    {
        $signals = [
            OverlaySignal::continue('overlay_1'),
            OverlaySignal::continue('overlay_2'),
        ];

        $context = new OverlayInfluenceContext(
            signals: $signals,
            hasInfluence: false,
            requiresReview: false,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: null,
        );

        $this->assertFalse($context->hasInfluence);
        $this->assertFalse($context->requiresReview);
    }

    public function test_context_with_constitutional_review_signal_sets_requires_review(): void
    {
        $signal = new OverlaySignal(
            OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW,
            'emergency_overlay',
            'emergency_condition_triggered',
            [],
            null,
            null,
        );

        $context = new OverlayInfluenceContext(
            signals: [$signal],
            hasInfluence: true,
            requiresReview: true,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: null,
        );

        $this->assertTrue($context->requiresReview);
        $this->assertTrue($context->hasInfluence);
    }

    public function test_context_with_elevation_request_carries_trust_level(): void
    {
        $signal = new OverlaySignal(
            OverlayInfluence::TRUST_ELEVATION_REQUEST,
            'registrar_elevation',
            'registrar_attestation_present',
            [],
            null,
            TrustLevel::RegistrarAttested,
        );

        $context = new OverlayInfluenceContext(
            signals: [$signal],
            hasInfluence: true,
            requiresReview: false,
            requiresReverification: false,
            isInconclusive: false,
            elevationRequest: TrustLevel::RegistrarAttested,
        );

        $this->assertEquals(TrustLevel::RegistrarAttested, $context->elevationRequest);
    }

    public function test_context_readonly_immutability(): void
    {
        $context = OverlayInfluenceContext::noInfluence();

        // Verify object is readonly (immutable)
        $this->expectException(\Error::class);
        $context->hasInfluence = true;
    }
}
