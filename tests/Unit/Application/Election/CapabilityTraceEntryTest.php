<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilityTraceEntry;
use App\Domain\Election\Enum\CapabilityOutcome;
use PHPUnit\Framework\TestCase;

class CapabilityTraceEntryTest extends TestCase
{
    public function test_granted_creates_entry_with_granted_outcome(): void
    {
        $entry = CapabilityTraceEntry::granted('TestPolicy');

        $this->assertTrue($entry->isGranted());
        $this->assertFalse($entry->isDenied());
        $this->assertEquals('TestPolicy', $entry->policyName);
        $this->assertNull($entry->reason);
        $this->assertNull($entry->detail);
    }

    public function test_abstained_creates_entry_with_abstained_outcome(): void
    {
        $entry = CapabilityTraceEntry::abstained('TestPolicy');

        $this->assertTrue($entry->isAbstained());
        $this->assertFalse($entry->isDenied());
        $this->assertFalse($entry->isGranted());
        $this->assertEquals('TestPolicy', $entry->policyName);
    }

    public function test_denied_creates_entry_with_denied_outcome(): void
    {
        $entry = CapabilityTraceEntry::denied(
            'TestPolicy',
            CapabilityDenialReason::Suspended,
            'suspended by admin'
        );

        $this->assertTrue($entry->isDenied());
        $this->assertFalse($entry->isGranted());
        $this->assertFalse($entry->isAbstained());
        $this->assertEquals('TestPolicy', $entry->policyName);
        $this->assertEquals(CapabilityDenialReason::Suspended, $entry->reason);
        $this->assertEquals('suspended by admin', $entry->detail);
    }

    public function test_denied_without_detail(): void
    {
        $entry = CapabilityTraceEntry::denied('TestPolicy', CapabilityDenialReason::MissingRole);

        $this->assertTrue($entry->isDenied());
        $this->assertEquals(CapabilityDenialReason::MissingRole, $entry->reason);
        $this->assertNull($entry->detail);
    }

    public function test_short_circuit_outcome(): void
    {
        $entry = CapabilityTraceEntry::shortCircuit(
            'SuspensionPolicy',
            CapabilityDenialReason::Suspended,
            'election suspended'
        );

        $this->assertTrue($entry->isShortCircuit());
        $this->assertFalse($entry->isDenied());
        $this->assertFalse($entry->isGranted());
        $this->assertEquals('SuspensionPolicy', $entry->policyName);
        $this->assertEquals(CapabilityDenialReason::Suspended, $entry->reason);
    }

    public function test_short_circuit_requires_denial_reason(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Short circuit requires denial reason');

        new CapabilityTraceEntry('TestPolicy', CapabilityOutcome::ShortCircuit);
    }

    public function test_valid_outcomes(): void
    {
        $denied = new CapabilityTraceEntry('TestPolicy', CapabilityOutcome::Denied, CapabilityDenialReason::Suspended);
        $abstained = new CapabilityTraceEntry('TestPolicy', CapabilityOutcome::Abstained);
        $granted = new CapabilityTraceEntry('TestPolicy', CapabilityOutcome::Granted);

        $this->assertTrue($denied->isDenied());
        $this->assertTrue($abstained->isAbstained());
        $this->assertTrue($granted->isGranted());
    }
}
