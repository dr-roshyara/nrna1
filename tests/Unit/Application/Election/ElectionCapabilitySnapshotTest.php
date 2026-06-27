<?php

namespace Tests\Unit\Application\Election;

use App\Application\Election\Capabilities\CapabilityDenialReason;
use App\Application\Election\Capabilities\CapabilitySeverity;
use App\Application\Election\Capabilities\ElectionCapabilityEntry;
use App\Application\Election\Capabilities\ElectionCapabilitySnapshot;
use App\Application\Election\Capabilities\CapabilityTrace;
use App\Domain\Election\Enum\ElectionLifecycleState;
use PHPUnit\Framework\TestCase;

class ElectionCapabilitySnapshotTest extends TestCase
{
    public function test_snapshot_with_all_properties(): void
    {
        $capabilities = [
            'open_voting' => ElectionCapabilityEntry::allowed('chief', 'draft'),
            'close_voting' => ElectionCapabilityEntry::denied(CapabilityDenialReason::Suspended),
        ];
        $trace = CapabilityTrace::empty();

        $snapshot = new ElectionCapabilitySnapshot(
            ElectionLifecycleState::Draft,
            $capabilities,
            false,
            $trace
        );

        $this->assertEquals(ElectionLifecycleState::Draft, $snapshot->lifecycleState);
        $this->assertEquals($capabilities, $snapshot->capabilities);
        $this->assertFalse($snapshot->isSuspended);
        $this->assertEquals($trace, $snapshot->trace);
    }

    public function test_can_check_capability(): void
    {
        $capabilities = [
            'open_voting' => ElectionCapabilityEntry::allowed('chief'),
            'close_voting' => ElectionCapabilityEntry::denied(CapabilityDenialReason::InvalidLifecycle),
        ];
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft, $capabilities);

        $this->assertTrue($snapshot->can('open_voting'));
        $this->assertFalse($snapshot->can('close_voting'));
        $this->assertFalse($snapshot->can('nonexistent_action'));
    }

    public function test_denial_reason_returns_null_for_allowed(): void
    {
        $capabilities = [
            'open_voting' => ElectionCapabilityEntry::allowed(),
        ];
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft, $capabilities);

        $this->assertNull($snapshot->denialReason('open_voting'));
    }

    public function test_denial_reason_returns_reason_for_denied(): void
    {
        $capabilities = [
            'close_voting' => ElectionCapabilityEntry::denied(CapabilityDenialReason::Suspended, 'admin hold'),
        ];
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft, $capabilities);

        $this->assertEquals(CapabilityDenialReason::Suspended, $snapshot->denialReason('close_voting'));
    }

    public function test_denial_reason_returns_null_for_nonexistent(): void
    {
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft, []);

        $this->assertNull($snapshot->denialReason('nonexistent'));
    }

    public function test_suspension_flag(): void
    {
        $snapshot1 = new ElectionCapabilitySnapshot(
            ElectionLifecycleState::VotingActive,
            [],
            true
        );
        $snapshot2 = new ElectionCapabilitySnapshot(
            ElectionLifecycleState::VotingActive,
            [],
            false
        );

        $this->assertTrue($snapshot1->isSuspended);
        $this->assertFalse($snapshot2->isSuspended);
    }

    public function test_empty_snapshot(): void
    {
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft);

        $this->assertEquals(ElectionLifecycleState::Draft, $snapshot->lifecycleState);
        $this->assertEmpty($snapshot->capabilities);
        $this->assertFalse($snapshot->isSuspended);
        $this->assertFalse($snapshot->can('any_action'));
    }

    public function test_snapshot_is_immutable(): void
    {
        $snapshot = new ElectionCapabilitySnapshot(
            ElectionLifecycleState::Draft,
            ['test' => ElectionCapabilityEntry::allowed()]
        );

        $this->assertIsObject($snapshot);
        // Cannot reassign due to readonly
        $this->assertEquals(ElectionLifecycleState::Draft, $snapshot->lifecycleState);
    }

    public function test_trace_methods(): void
    {
        $trace = CapabilityTrace::empty();
        $snapshot = new ElectionCapabilitySnapshot(
            ElectionLifecycleState::Draft,
            [],
            false,
            $trace
        );

        $this->assertEquals($trace, $snapshot->trace);
        $this->assertEquals(0, $snapshot->trace->count());
    }

    public function test_denial_detail_method(): void
    {
        $capabilities = [
            'open_voting' => ElectionCapabilityEntry::denied(
                CapabilityDenialReason::MissingRole,
                'chief',
            ),
        ];
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft, $capabilities);

        $this->assertEquals('chief', $snapshot->denialDetail('open_voting'));
    }

    public function test_denial_detail_returns_null_when_allowed(): void
    {
        $capabilities = [
            'open_voting' => ElectionCapabilityEntry::allowed(),
        ];
        $snapshot = new ElectionCapabilitySnapshot(ElectionLifecycleState::Draft, $capabilities);

        $this->assertNull($snapshot->denialDetail('open_voting'));
    }
}
