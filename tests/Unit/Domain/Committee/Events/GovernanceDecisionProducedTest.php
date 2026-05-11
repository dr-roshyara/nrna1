<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Events;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Events\GovernanceDecisionProduced;

final class GovernanceDecisionProducedTest extends TestCase
{
    public function test_event_name_is_stable(): void
    {
        $event = new GovernanceDecisionProduced(
            decisionId: 'dec-1',
            tenantId: 'tenant-1',
            capabilityType: 'create_committee',
            resolutionType: 'direct',
            winningAuthorityId: 'auth-1'
        );

        $this->assertEquals('governance.decision.produced', $event->eventName());
    }

    public function test_metadata_is_structured_and_complete(): void
    {
        $event = new GovernanceDecisionProduced(
            decisionId: 'dec-1',
            tenantId: 'tenant-1',
            capabilityType: 'create_committee',
            resolutionType: 'override',
            winningAuthorityId: 'auth-1'
        );

        $metadata = $event->metadata();

        $this->assertIsArray($metadata);
        $this->assertArrayHasKey('decision', $metadata);
        $this->assertArrayHasKey('capability', $metadata);
        $this->assertArrayHasKey('resolution', $metadata);
        $this->assertArrayHasKey('tenant', $metadata);

        $this->assertEquals('dec-1', $metadata['decision']['id']);
        $this->assertEquals(1, $metadata['decision']['version']);
        $this->assertEquals('create_committee', $metadata['capability']['type']);
        $this->assertEquals('override', $metadata['resolution']['type']);
        $this->assertEquals('auth-1', $metadata['resolution']['winning_authority_id']);
        $this->assertEquals('tenant-1', $metadata['tenant']['id']);
    }

    public function test_version_is_set_to_one_by_default(): void
    {
        $event = new GovernanceDecisionProduced(
            decisionId: 'dec-1',
            tenantId: 'tenant-1',
            capabilityType: 'create_committee',
            resolutionType: 'none',
            winningAuthorityId: null
        );

        $this->assertEquals(1, $event->decisionVersion);
    }
}
