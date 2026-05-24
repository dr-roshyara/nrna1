<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityDecision;
use App\Application\Election\Capabilities\CapabilitySeverity;
use PHPUnit\Framework\TestCase;

class CapabilityDecisionTest extends TestCase
{
    public function test_grant_creates_allowed_decision(): void
    {
        $decision = CapabilityDecision::grant();

        $this->assertTrue($decision->allows());
        $this->assertFalse($decision->denies());
        $this->assertNull($decision->reason);
        $this->assertNull($decision->detail);
    }

    public function test_abstain_creates_allowed_decision(): void
    {
        $decision = CapabilityDecision::abstain();

        $this->assertTrue($decision->allows());
        $this->assertFalse($decision->denies());
        $this->assertNull($decision->reason);
        $this->assertNull($decision->detail);
    }

    public function test_deny_with_reason(): void
    {
        $decision = CapabilityDecision::deny(CapabilityDenialReason::Suspended);

        $this->assertFalse($decision->allows());
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
        $this->assertNull($decision->detail);
    }

    public function test_deny_with_reason_and_detail(): void
    {
        $decision = CapabilityDecision::deny(
            CapabilityDenialReason::MissingRole,
            'chief'
        );

        $this->assertFalse($decision->allows());
        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilityDenialReason::MissingRole, $decision->reason);
        $this->assertEquals('chief', $decision->detail);
    }

    public function test_deny_with_custom_severity(): void
    {
        $decision = CapabilityDecision::deny(
            CapabilityDenialReason::Suspended,
            'suspended by admin',
            CapabilitySeverity::GovernanceHold
        );

        $this->assertTrue($decision->denies());
        $this->assertEquals(CapabilitySeverity::GovernanceHold, $decision->severity);
    }

    public function test_deny_defaults_to_hard_block_severity(): void
    {
        $decision = CapabilityDecision::deny(CapabilityDenialReason::InvalidLifecycle);

        $this->assertEquals(CapabilitySeverity::HardBlock, $decision->severity);
    }

    public function test_grant_severity(): void
    {
        $decision = CapabilityDecision::grant();

        $this->assertEquals(CapabilitySeverity::HardBlock, $decision->severity);
    }

    public function test_all_denial_reasons_can_be_denied(): void
    {
        foreach (CapabilityDenialReason::cases() as $reason) {
            $decision = CapabilityDecision::deny($reason);
            $this->assertTrue($decision->denies());
            $this->assertEquals($reason, $decision->reason);
        }
    }

    public function test_decision_is_immutable(): void
    {
        $decision = CapabilityDecision::deny(CapabilityDenialReason::Suspended);

        $this->assertEquals(CapabilityDenialReason::Suspended, $decision->reason);
        // Cannot reassign due to readonly
        $this->assertIsObject($decision);
    }
}
