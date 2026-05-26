<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\OverlayEffect;
use PHPUnit\Framework\TestCase;

class OverlayEffectTest extends TestCase
{
    public function test_deny_has_correct_value(): void
    {
        $this->assertEquals('deny', OverlayEffect::Deny->value);
    }

    public function test_elevate_trust_has_correct_value(): void
    {
        $this->assertEquals('elevate_trust', OverlayEffect::ElevateTrust->value);
    }

    public function test_require_review_has_correct_value(): void
    {
        $this->assertEquals('require_review', OverlayEffect::RequireReview->value);
    }

    public function test_restrict_continuity_has_correct_value(): void
    {
        $this->assertEquals('restrict_continuity', OverlayEffect::RestrictContinuity->value);
    }

    public function test_force_reattestion_has_correct_value(): void
    {
        $this->assertEquals('force_reattestion', OverlayEffect::ForceReAttestation->value);
    }
}
