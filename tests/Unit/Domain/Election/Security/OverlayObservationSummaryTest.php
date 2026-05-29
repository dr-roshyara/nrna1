<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\ConstitutionalConcernLevel;
use App\Domain\Election\Security\EvidenceWeightCategory;
use App\Domain\Election\Security\OverlayObservationSummary;
use App\Domain\Election\Security\Simplified\OverlaySignal;
use PHPUnit\Framework\TestCase;

class OverlayObservationSummaryTest extends TestCase
{
    public function test_no_observations_returns_minimal_context(): void
    {
        $context = OverlayObservationSummary::noObservations();

        $this->assertEmpty($context->signals);
        $this->assertFalse($context->hasObservations);
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $context->highestConcern);
        $this->assertEquals(EvidenceWeightCategory::STRONG, $context->lowestWeight);
    }

    public function test_context_with_context_stable_signals_has_no_observations(): void
    {
        $signals = [
            OverlaySignal::contextStable('overlay_1', 'Stable context', []),
            OverlaySignal::contextStable('overlay_2', 'Stable context', []),
        ];

        $context = new OverlayObservationSummary(
            signals: $signals,
            highestConcern: ConstitutionalConcernLevel::NONE,
            lowestWeight: EvidenceWeightCategory::STRONG,
            hasObservations: true,
        );

        $this->assertTrue($context->hasObservations);
        $this->assertEquals(ConstitutionalConcernLevel::NONE, $context->highestConcern);
    }

    public function test_context_with_evidence_inconsistent_signal_indicates_concern(): void
    {
        $signal = OverlaySignal::evidenceInconsistent(
            'emergency_overlay',
            'Emergency condition triggered',
            [],
        );

        $context = new OverlayObservationSummary(
            signals: [$signal],
            highestConcern: ConstitutionalConcernLevel::MEDIUM,
            lowestWeight: EvidenceWeightCategory::WEAK,
            hasObservations: true,
        );

        $this->assertTrue($context->hasObservations);
        $this->assertEquals(ConstitutionalConcernLevel::MEDIUM, $context->highestConcern);
    }

    public function test_context_with_attestation_present_signal(): void
    {
        $signal = OverlaySignal::attestationPresent(
            'registrar_elevation',
            'Registrar attestation present',
            [],
        );

        $context = new OverlayObservationSummary(
            signals: [$signal],
            highestConcern: ConstitutionalConcernLevel::LOW,
            lowestWeight: EvidenceWeightCategory::STRONG,
            hasObservations: true,
        );

        $this->assertTrue($context->hasObservations);
    }

    public function test_context_readonly_immutability(): void
    {
        $context = OverlayObservationSummary::noObservations();

        $this->expectException(\Error::class);
        $context->hasObservations = true;
    }
}
