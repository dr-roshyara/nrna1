<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Constitutional\GovernanceLegitimacy;

final class GovernanceLegitimacyTest extends TestCase
{
    public function test_legitimate_is_valid(): void
    {
        $this->assertTrue(GovernanceLegitimacy::LEGITIMATE->isValid());
    }

    public function test_expired_is_not_valid(): void
    {
        $this->assertFalse(GovernanceLegitimacy::EXPIRED->isValid());
    }

    public function test_emergency_and_caretaker_are_temporary(): void
    {
        $this->assertTrue(GovernanceLegitimacy::EMERGENCY->isTemporary());
        $this->assertTrue(GovernanceLegitimacy::CARETAKER->isTemporary());
    }
}
