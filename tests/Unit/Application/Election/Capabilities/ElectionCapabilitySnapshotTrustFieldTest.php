<?php

namespace Tests\Unit\Application\Election\Capabilities;

use App\Application\Election\Capabilities\CapabilityTrace;
use App\Application\Election\Capabilities\ElectionCapabilitySnapshot;
use App\Application\Election\Security\ConstitutionalTrustSnapshot;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Security\TrustLevel;
use PHPUnit\Framework\TestCase;

class ElectionCapabilitySnapshotTrustFieldTest extends TestCase
{
    public function test_trust_field_is_nullable(): void
    {
        $snapshot = new ElectionCapabilitySnapshot(
            lifecycleState: ElectionLifecycleState::VotingActive,
            capabilities: [],
            isSuspended: false,
            trace: new CapabilityTrace(),
        );

        $this->assertNull($snapshot->trust);
    }

    public function test_trust_snapshot_is_projection_only(): void
    {
        $trustSnapshot = new ConstitutionalTrustSnapshot(
            true,
            TrustLevel::Attested,
            'single_code',
            false,
            false,
            true,
            'none',
            true,
            null,
            null,
            '',
            [],
        );

        $snapshot = new ElectionCapabilitySnapshot(
            lifecycleState: ElectionLifecycleState::VotingActive,
            capabilities: [],
            isSuspended: false,
            trace: new CapabilityTrace(),
            trust: $trustSnapshot,
        );

        // Verify projection has no authority methods
        $this->assertFalse(method_exists($snapshot->trust, 'authorize'));
        $this->assertFalse(method_exists($snapshot->trust, 'grant'));
        $this->assertFalse(method_exists($snapshot->trust, 'deny'));
        $this->assertFalse(method_exists($snapshot->trust, 'recalculate'));
    }

    public function test_snapshot_trust_populated_after_resolver_evaluates_voting_action(): void
    {
        $trustSnapshot = new ConstitutionalTrustSnapshot(
            true,
            TrustLevel::Attested,
            'single_code',
            false,
            false,
            true,
            'none',
            true,
            null,
            null,
            '',
            [],
        );

        $snapshot = new ElectionCapabilitySnapshot(
            lifecycleState: ElectionLifecycleState::VotingActive,
            capabilities: ['vote' => new \App\Application\Election\Capabilities\ElectionCapabilityEntry(true)],
            isSuspended: false,
            trace: new CapabilityTrace(),
            trust: $trustSnapshot,
        );

        $this->assertNotNull($snapshot->trust);
        $this->assertTrue($snapshot->trust->trusted);
        $this->assertEquals(TrustLevel::Attested, $snapshot->trust->trustLevel);
    }
}
