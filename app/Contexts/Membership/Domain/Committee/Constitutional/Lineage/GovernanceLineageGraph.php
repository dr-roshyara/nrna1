<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Constitutional\Lineage;

final class GovernanceLineageGraph
{
    /** @var GovernanceLineageNode[] */
    private array $nodes = [];

    /** @var GovernanceLineageEdge[] */
    private array $edges = [];

    public function addNode(GovernanceLineageNode $node): void
    {
        $this->nodes[$node->decisionId] = $node;
    }

    public function addEdge(GovernanceLineageEdge $edge): void
    {
        $this->edges[] = $edge;
    }

    public function findNode(string $decisionId): ?GovernanceLineageNode
    {
        return $this->nodes[$decisionId] ?? null;
    }

    public function successorsOf(string $decisionId): array
    {
        $successors = [];
        foreach ($this->edges as $edge) {
            if ($edge->fromNodeId === $decisionId) {
                $successors[] = $edge->toNodeId;
            }
        }
        return $successors;
    }

    public function branches(): array
    {
        // Count outgoing edges per node
        $outgoingCounts = [];
        foreach ($this->edges as $edge) {
            $outgoingCounts[$edge->fromNodeId] = ($outgoingCounts[$edge->fromNodeId] ?? 0) + 1;
        }

        // Nodes with more than one successor are branch points
        $branchPoints = [];
        foreach ($outgoingCounts as $nodeId => $count) {
            if ($count > 1) {
                $branchPoints[] = $nodeId;
            }
        }

        return $branchPoints;
    }

    public function nodeCount(): int
    {
        return count($this->nodes);
    }
}
