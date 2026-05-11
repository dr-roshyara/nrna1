<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Controllers;

use App\Contexts\Governance\API\V1\Requests\HierarchyQueryRequest;
use App\Contexts\Governance\API\V1\Responses\CommitteeGovernanceResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeHierarchyResponse;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\JsonResponse;

final class GovernanceHierarchyController
{
    public function __construct(
        private readonly CommitteeHierarchyQueryInterface $hierarchyQuery,
    ) {}

    public function hierarchy(HierarchyQueryRequest $request, TenantId $tenantId): JsonResponse
    {
        $records = $this->hierarchyQuery->execute($tenantId);

        // Build tree from flat records
        $tree = $this->buildTree($records, $request);

        $response = [
            'data' => array_map(fn (CommitteeHierarchyResponse $n) => $n->jsonSerialize(), $tree),
            'meta' => [
                'projectionGeneration' => $this->hierarchyQuery->getProjectionGeneration($tenantId),
                'projectionAgeMs' => 0,
                'projectionEvaluatedAt' => null,
                'stale' => false,
                'totalRoots' => count($tree),
            ],
        ];

        return new JsonResponse($response, 200, [
            'Cache-Control' => 'max-age=60, stale-while-revalidate=300',
        ]);
    }

    /** @return CommitteeHierarchyResponse[] */
    private function buildTree(array $records, HierarchyQueryRequest $request): array
    {
        // Group records by parentId
        $children = [];
        $nodeMap = [];
        $depth = $request->getDepth();
        $search = $request->getSearch();
        $state = $request->getState();

        foreach ($records as $record) {
            // Apply search filter
            if ($search !== null && !str_contains(strtolower($record->name), strtolower($search))) {
                continue;
            }
            // Apply state filter
            if ($state !== null && $record->operationalState !== $state) {
                continue;
            }
            $parentId = $record->parentId !== null ? $record->parentId->value() : null;
            $children[$parentId][] = $record;
            $nodeMap[$record->id->value()] = $record;
        }

        // Determine roots
        $root = $request->getRoot();
        $rootIds = $root !== null
            ? (isset($nodeMap[$root]) ? [$root] : [])
            : array_map(fn($r) => $r->id->value(), $children[null] ?? []);

        // Build tree with depth limit
        $maxDepth = $depth ?? PHP_INT_MAX;

        return $this->buildNodes($rootIds, $children, $nodeMap, 0, $maxDepth, $search, $state);
    }

    /** @return CommitteeHierarchyResponse[] */
    private function buildNodes(
        array $ids,
        array &$children,
        array &$nodeMap,
        int $currentDepth,
        int $maxDepth,
        ?string $search,
        ?string $state,
    ): array {
        $result = [];

        foreach ($ids as $id) {
            if (!isset($nodeMap[$id])) {
                continue;
            }

            $record = $nodeMap[$id];
            $childNodes = [];

            if ($currentDepth < $maxDepth && isset($children[$id])) {
                $childNodes = $this->buildNodes(
                    array_map(fn ($r) => $r->id->value(), $children[$id]),
                    $children,
                    $nodeMap,
                    $currentDepth + 1,
                    $maxDepth,
                    $search,
                    $state,
                );
            }

            $result[] = new CommitteeHierarchyResponse(
                id: $record->id->value(),
                name: $record->name,
                level: $record->level,
                parentId: $record->parentId?->value(),
                childrenCount: count($children[$id] ?? []),
                children: $childNodes,
                governance: CommitteeGovernanceResponse::fromArray([
                    'operationalState' => $record->operationalState,
                    'temporalState' => $record->temporalState,
                    'legitimacy' => $record->legitimacy,
                    'canAct' => $record->canAct,
                    'isFullyOperational' => $record->isFullyOperational,
                    'evaluatedAt' => null,
                    'projectionGeneration' => '',
                ]),
            );
        }

        return $result;
    }
}
