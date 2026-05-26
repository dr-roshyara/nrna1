<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\TrustValidityScope;
use PHPUnit\Framework\TestCase;

class TrustValidityScopeTest extends TestCase
{
    public function test_election_scoped_has_correct_value(): void
    {
        $this->assertEquals('election_scoped', TrustValidityScope::ElectionScoped->value);
    }

    public function test_session_scoped_has_correct_value(): void
    {
        $this->assertEquals('session_scoped', TrustValidityScope::SessionScoped->value);
    }

    public function test_device_scoped_has_correct_value(): void
    {
        $this->assertEquals('device_scoped', TrustValidityScope::DeviceScoped->value);
    }

    public function test_overlay_scoped_has_correct_value(): void
    {
        $this->assertEquals('overlay_scoped', TrustValidityScope::OverlayScoped->value);
    }

    public function test_manual_clear_required_has_correct_value(): void
    {
        $this->assertEquals('manual_clear', TrustValidityScope::ManualClearRequired->value);
    }
}
