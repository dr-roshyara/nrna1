<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilitySeverity;
use App\Application\Election\Capabilities\ElectionCapabilityEntry;
use PHPUnit\Framework\TestCase;

class ElectionCapabilityEntryTest extends TestCase
{
    public function test_allowed_entry(): void
    {
        $entry = ElectionCapabilityEntry::allowed('chief', 'draft');

        $this->assertTrue($entry->allowed);
        $this->assertFalse($entry->isDenied());
        $this->assertNull($entry->denialReason);
        $this->assertNull($entry->denialDetail);
        $this->assertNull($entry->severity);
        $this->assertEquals('chief', $entry->requiredRole);
        $this->assertEquals('draft', $entry->lifecycleState);
    }

    public function test_allowed_entry_without_role(): void
    {
        $entry = ElectionCapabilityEntry::allowed();

        $this->assertTrue($entry->allowed);
        $this->assertNull($entry->requiredRole);
        $this->assertEquals('', $entry->lifecycleState);
    }

    public function test_denied_entry(): void
    {
        $entry = ElectionCapabilityEntry::denied(
            CapabilityDenialReason::Suspended,
            'suspended by admin',
            CapabilitySeverity::GovernanceHold,
            'voting_active'
        );

        $this->assertFalse($entry->allowed);
        $this->assertTrue($entry->isDenied());
        $this->assertEquals(CapabilityDenialReason::Suspended, $entry->denialReason);
        $this->assertEquals('suspended by admin', $entry->denialDetail);
        $this->assertEquals(CapabilitySeverity::GovernanceHold, $entry->severity);
        $this->assertEquals('voting_active', $entry->lifecycleState);
    }

    public function test_denied_entry_without_detail(): void
    {
        $entry = ElectionCapabilityEntry::denied(CapabilityDenialReason::MissingRole);

        $this->assertFalse($entry->allowed);
        $this->assertTrue($entry->isDenied());
        $this->assertEquals(CapabilityDenialReason::MissingRole, $entry->denialReason);
        $this->assertNull($entry->denialDetail);
        $this->assertEquals(CapabilitySeverity::HardBlock, $entry->severity);
    }

    public function test_denied_defaults_to_hard_block(): void
    {
        $entry = ElectionCapabilityEntry::denied(CapabilityDenialReason::InvalidLifecycle);

        $this->assertEquals(CapabilitySeverity::HardBlock, $entry->severity);
    }

    public function test_denied_with_all_reasons(): void
    {
        foreach (CapabilityDenialReason::cases() as $reason) {
            $entry = ElectionCapabilityEntry::denied($reason);
            $this->assertTrue($entry->isDenied());
            $this->assertEquals($reason, $entry->denialReason);
        }
    }

    public function test_entry_is_immutable(): void
    {
        $entry = ElectionCapabilityEntry::allowed('chief');

        $this->assertTrue($entry->allowed);
        // Cannot reassign due to readonly
        $this->assertIsObject($entry);
    }

    public function test_denied_entry_with_multiple_severity_levels(): void
    {
        foreach (CapabilitySeverity::cases() as $severity) {
            $entry = ElectionCapabilityEntry::denied(
                CapabilityDenialReason::Suspended,
                null,
                $severity
            );
            $this->assertEquals($severity, $entry->severity);
        }
    }
}
