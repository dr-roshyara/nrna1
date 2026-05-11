<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee\Geo\Graph;

use App\Contexts\Membership\Domain\Committee\Context\GeographicScope;

final class GeoAuthorityGraph
{
    /**
     * @param JurisdictionNode[] $nodes
     * @param DelegationEdge[] $edges
     */
    public function __construct(
        private readonly array $nodes,
        private readonly array $edges,
    ) {}

    public function findNodeByScope(GeographicScope $scope): ?JurisdictionNode
    {
        foreach ($this->nodes as $node) {
            if ($node->matchesScope($scope)) {
                return $node;
            }
        }
        return null;
    }

    /**
     * Find all jurisdictions reachable from the given node via delegation edges.
     *
     * @return array<array{node: JurisdictionNode, edge: DelegationEdge}>
     */
    public function reachableJurisdictions(string $nodeId, \DateTimeImmutable $at): array
    {
        $reachable = [];
        $visited = [];
        $queue = [$nodeId];

        while (!empty($queue)) {
            $currentId = array_shift($queue);
            if (isset($visited[$currentId])) {
                continue;
            }
            $visited[$currentId] = true;

            foreach ($this->edges as $edge) {
                if ($edge->fromJurisdictionId === $currentId && $edge->isValidAt($at)) {
                    $targetNode = $this->findNodeById($edge->toJurisdictionId);
                    if ($targetNode !== null && $targetNode->active && !isset($visited[$edge->toJurisdictionId])) {
                        $reachable[] = ['node' => $targetNode, 'edge' => $edge];
                        $queue[] = $edge->toJurisdictionId;
                    }
                }
            }
        }

        return $reachable;
    }

    public function hasAuthorityPath(string $fromNodeId, string $toNodeId, \DateTimeImmutable $at): bool
    {
        $visited = [];
        $queue = [$fromNodeId];

        while (!empty($queue)) {
            $currentId = array_shift($queue);
            if (isset($visited[$currentId])) {
                continue;
            }
            $visited[$currentId] = true;

            if ($currentId === $toNodeId) {
                return true;
            }

            foreach ($this->edges as $edge) {
                if ($edge->fromJurisdictionId === $currentId && $edge->isValidAt($at)) {
                    $targetNode = $this->findNodeById($edge->toJurisdictionId);
                    if ($targetNode !== null && $targetNode->active) {
                        $queue[] = $edge->toJurisdictionId;
                    }
                }
            }
        }

        return false;
    }

    private function findNodeById(string $id): ?JurisdictionNode
    {
        foreach ($this->nodes as $node) {
            if ($node->id === $id) {
                return $node;
            }
        }
        return null;
    }
}
