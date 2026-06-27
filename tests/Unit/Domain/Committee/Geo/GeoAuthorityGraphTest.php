<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Geo;

use PHPUnit\Framework\TestCase;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\GeoAuthorityGraph;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\JurisdictionNode;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationEdge;
use App\Contexts\Membership\Domain\Committee\Geo\Graph\DelegationType;

final class GeoAuthorityGraphTest extends TestCase
{
    public function test_reachable_from_isolated_node_returns_empty(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA],
            edges: []
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(0, $reachable);
    }

    public function test_reachable_through_single_active_edge(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);

        $edge = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB],
            edges: [$edge]
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(1, $reachable);
        $this->assertEquals('node-b', $reachable[0]['node']->id);
        $this->assertSame($edge, $reachable[0]['edge']);
    }

    public function test_reachable_through_chain_A_to_B_to_C(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);
        $nodeC = new JurisdictionNode('node-c', 'district', 'BY-01', true);

        $edgeAB = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $edgeBC = new DelegationEdge(
            fromJurisdictionId: 'node-b',
            toJurisdictionId: 'node-c',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB, $nodeC],
            edges: [$edgeAB, $edgeBC]
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(2, $reachable);
        $this->assertEquals('node-b', $reachable[0]['node']->id);
        $this->assertEquals('node-c', $reachable[1]['node']->id);
    }

    public function test_expired_edge_not_traversed(): void
    {
        $now = new \DateTimeImmutable();
        $yesterday = $now->modify('-1 day');
        $dayBeforeYesterday = $now->modify('-2 days');

        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);

        $edge = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $dayBeforeYesterday,
            validTo: $yesterday
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB],
            edges: [$edge]
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(0, $reachable);
    }

    public function test_future_edge_not_traversed(): void
    {
        $now = new \DateTimeImmutable();
        $tomorrow = $now->modify('+1 day');
        $dayAfterTomorrow = $now->modify('+2 days');

        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);

        $edge = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $tomorrow,
            validTo: $dayAfterTomorrow
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB],
            edges: [$edge]
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(0, $reachable);
    }

    public function test_inactive_target_node_excluded(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', false);

        $edge = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB],
            edges: [$edge]
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(0, $reachable);
    }

    public function test_cycle_detection_terminates_gracefully(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);

        $edgeAB = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $edgeBA = new DelegationEdge(
            fromJurisdictionId: 'node-b',
            toJurisdictionId: 'node-a',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB],
            edges: [$edgeAB, $edgeBA]
        );

        $reachable = $graph->reachableJurisdictions('node-a', $now);

        $this->assertCount(1, $reachable);
        $this->assertEquals('node-b', $reachable[0]['node']->id);
    }

    public function test_hasAuthorityPath_true_via_valid_delegation(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);
        $nodeC = new JurisdictionNode('node-c', 'district', 'BY-01', true);

        $edgeAB = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $edgeBC = new DelegationEdge(
            fromJurisdictionId: 'node-b',
            toJurisdictionId: 'node-c',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB, $nodeC],
            edges: [$edgeAB, $edgeBC]
        );

        $hasPath = $graph->hasAuthorityPath('node-a', 'node-c', $now);

        $this->assertTrue($hasPath);
    }

    public function test_hasAuthorityPath_false_for_disconnected_nodes(): void
    {
        $now = new \DateTimeImmutable();
        $nodeA = new JurisdictionNode('node-a', 'national', null, true);
        $nodeB = new JurisdictionNode('node-b', 'state', 'BY', true);
        $nodeC = new JurisdictionNode('node-c', 'district', 'BA-01', true);

        $edgeAB = new DelegationEdge(
            fromJurisdictionId: 'node-a',
            toJurisdictionId: 'node-b',
            type: DelegationType::AUTHORITY,
            validFrom: $now,
            validTo: null
        );

        $graph = new GeoAuthorityGraph(
            nodes: [$nodeA, $nodeB, $nodeC],
            edges: [$edgeAB]
        );

        $hasPath = $graph->hasAuthorityPath('node-a', 'node-c', $now);

        $this->assertFalse($hasPath);
    }
}
