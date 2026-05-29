<?php

namespace Tests\Unit\Application\Election\Security;

use App\Application\Election\Security\ConstitutionalOverlay;
use App\Application\Election\Security\OverlayCoordinator;
use App\Application\Election\Security\TrustCapabilityContext;
use App\Domain\Election\Security\DeviceTrustContext;
use App\Domain\Election\Security\FingerprintMatchType;
use App\Domain\Election\Security\NetworkTrustEvidence;
use App\Domain\Election\Security\OverlayInfluence;
use App\Domain\Election\Security\OverlaySignal;
use App\Domain\Election\Security\TrustValidityScope;
use App\Domain\Election\Security\VerificationAttestationRecord;
use App\Domain\Election\Security\VotingSessionTrustContinuity;
use App\Models\Election;
use PHPUnit\Framework\TestCase;

class OverlayCoordinatorD4Test extends TestCase
{
    private function makeContext(?Election $election = null): TrustCapabilityContext
    {
        return new TrustCapabilityContext(
            election: $election,
            user: null,
            network: new NetworkTrustEvidence('hash', null, null, 6, 1, true, 'ip_count'),
            device: new DeviceTrustContext(null, null, FingerprintMatchType::NotRequired, 'none', 'stable'),
            attestation: new VerificationAttestationRecord(
                false, false, null, null, 'none', null, null, false, TrustValidityScope::ElectionScoped
            ),
            sessionContinuity: new VotingSessionTrustContinuity('s1', 'h', 'h', false, 'continuous'),
        );
    }

    public function test_aggregate_returns_overlay_influence_context_not_trust_result(): void
    {
        // Verify return type is OverlayInfluenceContext, not VotingTrustResult
        $overlay1 = $this->createMock(ConstitutionalOverlay::class);
        $overlay1->method('evaluate')->willReturn(OverlaySignal::continue('test_overlay'));

        $coordinator = new OverlayCoordinator([$overlay1]);
        $ctx = $this->makeContext();

        $result = $coordinator->aggregate($ctx);

        $this->assertIsObject($result);
        $this->assertEquals(\App\Domain\Election\Security\OverlayInfluenceContext::class, get_class($result));
    }

    public function test_aggregate_never_mutates_context_facts(): void
    {
        $overlay1 = $this->createMock(ConstitutionalOverlay::class);
        $overlay1->method('evaluate')->willReturn(OverlaySignal::continue('test_overlay'));

        $coordinator = new OverlayCoordinator([$overlay1]);
        $ctx = $this->makeContext();

        $originalIpHash = $ctx->network->currentIpHash;

        $coordinator->aggregate($ctx);

        // Verify context facts unchanged
        $this->assertEquals($originalIpHash, $ctx->network->currentIpHash);
    }

    public function test_aggregate_with_no_overlays_returns_no_influence(): void
    {
        $coordinator = new OverlayCoordinator([]);
        $ctx = $this->makeContext();

        $result = $coordinator->aggregate($ctx);

        $this->assertFalse($result->hasInfluence);
        $this->assertEmpty($result->signals);
    }

    public function test_aggregate_stops_after_constitutional_review_required(): void
    {
        // When an overlay requires review, collection stops
        $overlay1 = $this->createMock(ConstitutionalOverlay::class);
        $overlay1->method('evaluate')->willReturn(
            new OverlaySignal(
                OverlayInfluence::REQUIRE_CONSTITUTIONAL_REVIEW,
                'overlay_1',
                'test_condition',
                [],
                null,
                null,
            )
        );

        $overlay2 = $this->createMock(ConstitutionalOverlay::class);
        $overlay2->expects($this->never())->method('evaluate'); // Should not be called

        $coordinator = new OverlayCoordinator([$overlay1, $overlay2]);
        $ctx = $this->makeContext();

        $result = $coordinator->aggregate($ctx);

        // Only first overlay's signal should be present
        $this->assertCount(1, $result->signals);
        $this->assertTrue($result->requiresReview);
    }

    public function test_aggregate_accumulates_all_signals_when_no_review_required(): void
    {
        // When no overlay requires review, all signals are collected
        $overlay1 = $this->createMock(ConstitutionalOverlay::class);
        $overlay1->method('evaluate')->willReturn(OverlaySignal::continue('overlay_1'));

        $overlay2 = $this->createMock(ConstitutionalOverlay::class);
        $overlay2->method('evaluate')->willReturn(OverlaySignal::continue('overlay_2'));

        $overlay3 = $this->createMock(ConstitutionalOverlay::class);
        $overlay3->method('evaluate')->willReturn(OverlaySignal::continue('overlay_3'));

        $coordinator = new OverlayCoordinator([$overlay1, $overlay2, $overlay3]);
        $ctx = $this->makeContext();

        $result = $coordinator->aggregate($ctx);

        // All signals should be present
        $this->assertCount(3, $result->signals);
        $this->assertFalse($result->requiresReview);
        $this->assertFalse($result->hasInfluence);
    }

    public function test_aggregate_sets_requires_reverification_flag(): void
    {
        $overlay1 = $this->createMock(ConstitutionalOverlay::class);
        $overlay1->method('evaluate')->willReturn(
            new OverlaySignal(
                OverlayInfluence::REQUIRE_RE_VERIFICATION,
                'device_anomaly',
                'device_changed',
                [],
                null,
                null,
            )
        );

        $coordinator = new OverlayCoordinator([$overlay1]);
        $ctx = $this->makeContext();

        $result = $coordinator->aggregate($ctx);

        $this->assertTrue($result->requiresReverification);
        $this->assertTrue($result->hasInfluence);
    }

    public function test_aggregate_sets_inconclusive_flag(): void
    {
        $overlay1 = $this->createMock(ConstitutionalOverlay::class);
        $overlay1->method('evaluate')->willReturn(
            new OverlaySignal(
                OverlayInfluence::TRUST_EVALUATION_INCONCLUSIVE,
                'ip_velocity',
                'velocity_exceeded',
                [],
                null,
                null,
            )
        );

        $coordinator = new OverlayCoordinator([$overlay1]);
        $ctx = $this->makeContext();

        $result = $coordinator->aggregate($ctx);

        $this->assertTrue($result->isInconclusive);
        $this->assertTrue($result->hasInfluence);
    }
}
