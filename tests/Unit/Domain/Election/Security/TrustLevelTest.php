<?php

namespace Tests\Unit\Domain\Election\Security;

use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class TrustLevelTest extends TestCase
{
    public function test_unverified_has_correct_string_value(): void
    {
        $this->assertEquals('unverified', TrustLevel::Unverified->value);
    }

    public function test_attested_has_correct_string_value(): void
    {
        $this->assertEquals('attested', TrustLevel::Attested->value);
    }

    public function test_continuity_verified_has_correct_string_value(): void
    {
        $this->assertEquals('continuity_verified', TrustLevel::ContinuityVerified->value);
    }

    public function test_registrar_attested_has_correct_string_value(): void
    {
        $this->assertEquals('registrar_attested', TrustLevel::RegistrarAttested->value);
    }
}
