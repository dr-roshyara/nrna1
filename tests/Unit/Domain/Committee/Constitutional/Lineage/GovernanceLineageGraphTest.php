<?php

declare(strict_types=1);

namespace Tests\Unit\Domain\Committee\Constitutional\Lineage;

use App\Contexts\Membership\Domain\Committee\Constitutional\Lineage\GovernanceLineageGraph;
use App\Contexts\Membership\Domain\Committee\Constitutional\Lineage\GovernanceLineageNode;
use App\Contexts\Membership\Domain\Committee\Constitutional\Lineage\GovernanceLineageEdge;
use PHPUnit\Framework\TestCase;

class GovernanceLineageGraphTest extends TestCase
{
    /**
     * @test
     * Graph stores and retrieves node
     */
    public function test_graph_stores_and_retrieves_node(): void
    {
        $graph = new GovernanceLineageGraph();

        $node = new GovernanceLineageNode(
            decisionId: 'decision-1',
            integrityHash: 'hash-1',
            decidedAt: new \DateTimeImmutable('2026-01-01T10:00:00Z')
        );

        $graph->addNode($node);

        $retrieved = $graph->findNode('decision-1');
        $this->assertNotNull($retrieved);
        $this->assertSame('decision-1', $retrieved->decisionId);
    }

    /**
     * @test
     * Successors returns linked nodes
     */
    public function test_successors_returns_linked_nodes(): void
    {
        $graph = new GovernanceLineageGraph();

        $nodeA = new GovernanceLineageNode(
            decisionId: 'node-A',
            integrityHash: 'hash-A',
            decidedAt: new \DateTimeImmutable('2026-01-01T10:00:00Z')
        );
        $nodeB = new GovernanceLineageNode(
            decisionId: 'node-B',
            integrityHash: 'hash-B',
            decidedAt: new \DateTimeImmutable('2026-01-02T10:00:00Z')
        );

        $graph->addNode($nodeA);
        $graph->addNode($nodeB);

        $edge = new GovernanceLineageEdge(
            fromNodeId: 'node-A',
            toNodeId: 'node-B',
            edgeReason: 'successor'
        );
        $graph->addEdge($edge);

        $successors = $graph->successorsOf('node-A');
        $this->assertContains('node-B', $successors);
    }

    /**
     * @test
     * Branches detects fork points
     */
    public function test_branches_detects_fork(): void
    {
        $graph = new GovernanceLineageGraph();

        $nodeA = new GovernanceLineageNode(
            decisionId: 'node-A',
            integrityHash: 'hash-A',
            decidedAt: new \DateTimeImmutable('2026-01-01T10:00:00Z')
        );
        $nodeB = new GovernanceLineageNode(
            decisionId: 'node-B',
            integrityHash: 'hash-B',
            decidedAt: new \DateTimeImmutable('2026-01-02T10:00:00Z')
        );
        $nodeC = new GovernanceLineageNode(
            decisionId: 'node-C',
            integrityHash: 'hash-C',
            decidedAt: new \DateTimeImmutable('2026-01-03T10:00:00Z')
        );

        $graph->addNode($nodeA);
        $graph->addNode($nodeB);
        $graph->addNode($nodeC);

        // A branches to both B and C
        $graph->addEdge(new GovernanceLineageEdge('node-A', 'node-B', 'branch-1'));
        $graph->addEdge(new GovernanceLineageEdge('node-A', 'node-C', 'branch-2'));

        $branches = $graph->branches();
        $this->assertContains('node-A', $branches);
    }

    /**
     * @test
     * Linear graph has no branches
     */
    public function test_linear_graph_has_no_branches(): void
    {
        $graph = new GovernanceLineageGraph();

        $nodeA = new GovernanceLineageNode(
            decisionId: 'node-A',
            integrityHash: 'hash-A',
            decidedAt: new \DateTimeImmutable('2026-01-01T10:00:00Z')
        );
        $nodeB = new GovernanceLineageNode(
            decisionId: 'node-B',
            integrityHash: 'hash-B',
            decidedAt: new \DateTimeImmutable('2026-01-02T10:00:00Z')
        );
        $nodeC = new GovernanceLineageNode(
            decisionId: 'node-C',
            integrityHash: 'hash-C',
            decidedAt: new \DateTimeImmutable('2026-01-03T10:00:00Z')
        );

        $graph->addNode($nodeA);
        $graph->addNode($nodeB);
        $graph->addNode($nodeC);

        // Linear: A → B → C
        $graph->addEdge(new GovernanceLineageEdge('node-A', 'node-B', 'successor'));
        $graph->addEdge(new GovernanceLineageEdge('node-B', 'node-C', 'successor'));

        $branches = $graph->branches();
        $this->assertEmpty($branches);
    }
}
