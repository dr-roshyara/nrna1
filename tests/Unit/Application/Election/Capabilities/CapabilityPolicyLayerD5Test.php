<?php

namespace Tests\Unit\Application\Election\Capabilities;

use App\Application\Election\Capabilities\CapabilityPolicyLayer;
use PHPUnit\Framework\TestCase;

class CapabilityPolicyLayerD5Test extends TestCase
{
    public function test_trust_layer_has_priority_2(): void
    {
        $this->assertEquals(2, CapabilityPolicyLayer::Trust->value);
    }

    public function test_overlay_layer_remains_priority_1(): void
    {
        $this->assertEquals(1, CapabilityPolicyLayer::Overlay->value);
    }

    public function test_lifecycle_layer_updated_to_priority_3(): void
    {
        $this->assertEquals(3, CapabilityPolicyLayer::Lifecycle->value);
    }

    public function test_layer_priority_ordering_is_sequential(): void
    {
        $this->assertEquals(1, CapabilityPolicyLayer::Overlay->value);
        $this->assertEquals(2, CapabilityPolicyLayer::Trust->value);
        $this->assertEquals(3, CapabilityPolicyLayer::Lifecycle->value);
        $this->assertEquals(4, CapabilityPolicyLayer::Preconditions->value);
        $this->assertEquals(5, CapabilityPolicyLayer::Authorization->value);
    }
}
