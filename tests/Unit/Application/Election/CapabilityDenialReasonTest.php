<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use PHPUnit\Framework\TestCase;

class CapabilityDenialReasonTest extends TestCase
{
    public function test_enum_has_all_required_cases(): void
    {
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityDenialReason::Suspended'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityDenialReason::MissingRole'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityDenialReason::InvalidLifecycle'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityDenialReason::UnmetPrecondition'));
    }

    public function test_suspended_label(): void
    {
        $this->assertEquals('Election Suspended', CapabilityDenialReason::Suspended->label());
    }

    public function test_missing_role_label(): void
    {
        $this->assertEquals('Missing Required Role', CapabilityDenialReason::MissingRole->label());
    }

    public function test_invalid_lifecycle_label(): void
    {
        $this->assertEquals('Invalid Lifecycle State', CapabilityDenialReason::InvalidLifecycle->label());
    }

    public function test_unmet_precondition_label(): void
    {
        $this->assertEquals('Unmet Requirements', CapabilityDenialReason::UnmetPrecondition->label());
    }

    public function test_enum_values_are_strings(): void
    {
        $this->assertIsString(CapabilityDenialReason::Suspended->value);
        $this->assertIsString(CapabilityDenialReason::MissingRole->value);
        $this->assertIsString(CapabilityDenialReason::InvalidLifecycle->value);
        $this->assertIsString(CapabilityDenialReason::UnmetPrecondition->value);
    }

    public function test_suspended_value(): void
    {
        $this->assertEquals('suspended', CapabilityDenialReason::Suspended->value);
    }

    public function test_missing_role_value(): void
    {
        $this->assertEquals('missing_role', CapabilityDenialReason::MissingRole->value);
    }

    public function test_invalid_lifecycle_value(): void
    {
        $this->assertEquals('invalid_lifecycle', CapabilityDenialReason::InvalidLifecycle->value);
    }

    public function test_unmet_precondition_value(): void
    {
        $this->assertEquals('unmet_precondition', CapabilityDenialReason::UnmetPrecondition->value);
    }
}
