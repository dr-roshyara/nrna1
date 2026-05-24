<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilitySeverity;
use PHPUnit\Framework\TestCase;

class CapabilitySeverityTest extends TestCase
{
    public function test_enum_has_all_required_cases(): void
    {
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilitySeverity::HardBlock'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilitySeverity::GovernanceHold'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilitySeverity::Warning'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilitySeverity::Advisory'));
    }

    public function test_hard_block_is_blocking(): void
    {
        $this->assertTrue(CapabilitySeverity::HardBlock->isBlocking());
    }

    public function test_governance_hold_is_blocking(): void
    {
        $this->assertTrue(CapabilitySeverity::GovernanceHold->isBlocking());
    }

    public function test_warning_is_not_blocking(): void
    {
        $this->assertFalse(CapabilitySeverity::Warning->isBlocking());
    }

    public function test_advisory_is_not_blocking(): void
    {
        $this->assertFalse(CapabilitySeverity::Advisory->isBlocking());
    }

    public function test_hard_block_label(): void
    {
        $this->assertEquals('Hard Block', CapabilitySeverity::HardBlock->label());
    }

    public function test_governance_hold_label(): void
    {
        $this->assertEquals('Governance Hold', CapabilitySeverity::GovernanceHold->label());
    }

    public function test_warning_label(): void
    {
        $this->assertEquals('Warning', CapabilitySeverity::Warning->label());
    }

    public function test_advisory_label(): void
    {
        $this->assertEquals('Advisory', CapabilitySeverity::Advisory->label());
    }

    public function test_enum_values_are_strings(): void
    {
        $this->assertIsString(CapabilitySeverity::HardBlock->value);
        $this->assertIsString(CapabilitySeverity::GovernanceHold->value);
        $this->assertIsString(CapabilitySeverity::Warning->value);
        $this->assertIsString(CapabilitySeverity::Advisory->value);
    }
}
