<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use PHPUnit\Framework\TestCase;

class CapabilityPolicyLayerTest extends TestCase
{
    public function test_enum_has_all_required_cases(): void
    {
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityPolicyLayer::Overlay'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityPolicyLayer::Lifecycle'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityPolicyLayer::Preconditions'));
        $this->assertTrue(defined('App\Application\Election\Capabilities\CapabilityPolicyLayer::Authorization'));
    }

    public function test_overlay_priority(): void
    {
        $this->assertEquals(1, CapabilityPolicyLayer::Overlay->priority());
    }

    public function test_lifecycle_priority(): void
    {
        $this->assertEquals(2, CapabilityPolicyLayer::Lifecycle->priority());
    }

    public function test_preconditions_priority(): void
    {
        $this->assertEquals(3, CapabilityPolicyLayer::Preconditions->priority());
    }

    public function test_authorization_priority(): void
    {
        $this->assertEquals(4, CapabilityPolicyLayer::Authorization->priority());
    }

    public function test_overlay_dominates_other_layers(): void
    {
        $this->assertLessThan(CapabilityPolicyLayer::Lifecycle->priority(), CapabilityPolicyLayer::Overlay->priority());
        $this->assertLessThan(CapabilityPolicyLayer::Preconditions->priority(), CapabilityPolicyLayer::Overlay->priority());
        $this->assertLessThan(CapabilityPolicyLayer::Authorization->priority(), CapabilityPolicyLayer::Overlay->priority());
    }

    public function test_overlay_label(): void
    {
        $this->assertEquals('Operational Overlay', CapabilityPolicyLayer::Overlay->label());
    }

    public function test_lifecycle_label(): void
    {
        $this->assertEquals('Lifecycle State', CapabilityPolicyLayer::Lifecycle->label());
    }

    public function test_preconditions_label(): void
    {
        $this->assertEquals('Constitutional Requirements', CapabilityPolicyLayer::Preconditions->label());
    }

    public function test_authorization_label(): void
    {
        $this->assertEquals('Role Authorization', CapabilityPolicyLayer::Authorization->label());
    }
}
