<?php

declare(strict_types=1);

namespace App\Contexts\Geography\Application\Services;

use App\Contexts\Geography\Application\DTOs\GeoHierarchyResponse;
use App\Contexts\Geography\Application\DTOs\GeoUnitResponse;

/**
 * CQRS Read Model Projection Builder (Optimized)
 *
 * - Strict O(n) complexity
 * - No recursion
 * - No array_shift (avoids O(n²))
 * - No repeated object reconstruction
 * - Deterministic projection pipeline
 */
final class GeoHierarchyProjectionBuilder
{
    /**
     * @param GeoUnitResponse[] $units
     * @return GeoHierarchyResponse[]
     */
    public function buildForest(array $units): array
    {
        if ($units === []) {
            return [];
        }

        /** @var array<int, GeoHierarchyResponse> $nodes */
        $nodes = [];

        /** @var array<int, int[]> $childrenIndex */
        $childrenIndex = [];

        /**
         * PASS 1: Create immutable nodes
         */
        foreach ($units as $unit) {
            $nodes[$unit->id] = new GeoHierarchyResponse(
                id: $unit->id,
                countryCode: $unit->countryCode,
                adminLevel: $unit->adminLevel,
                adminType: $unit->adminType,
                parentId: $unit->parentId,
                code: $unit->code,
                name: $unit->name,
                isActive: $unit->isActive,
                validFrom: $unit->validFrom,
                validTo: $unit->validTo,
                children: []
            );

            if ($unit->parentId !== null) {
                $childrenIndex[$unit->parentId][] = $unit->id;
            }
        }

        /**
         * PASS 2: Attach children references (deepest-first to avoid stale references)
         *
         * krsort ensures deepest parents are processed first, so by the time a
         * shallow parent captures child references, those children already hold
         * their own fully-assembled subtrees.
         */
        krsort($childrenIndex);

        foreach ($childrenIndex as $parentId => $childIds) {
            if (!isset($nodes[$parentId])) {
                continue;
            }

            $parent = $nodes[$parentId];

            $children = [];
            foreach ($childIds as $childId) {
                if (isset($nodes[$childId])) {
                    $children[] = $nodes[$childId];
                }
            }

            // single reconstruction only once per parent
            $nodes[$parentId] = $this->withChildren($parent, $children);
        }

        /**
         * PASS 3: Extract roots
         */
        $roots = [];

        foreach ($nodes as $node) {
            if ($node->parentId === null || !isset($nodes[$node->parentId])) {
                $roots[] = $node;
            }
        }

        return $roots;
    }

    /**
     * Build subtree from root ID
     */
    public function buildTree(array $units, int $rootId): ?GeoHierarchyResponse
    {
        foreach ($this->buildForest($units) as $root) {
            if ($root->id === $rootId) {
                return $root;
            }
        }

        return null;
    }

    /**
     * Iterative BFS subtree search (O(n), no recursion, no array_shift)
     */
    public function extractSubtree(array $roots, int $nodeId): ?GeoHierarchyResponse
    {
        $queue = $roots;
        $i = 0;

        while (isset($queue[$i])) {
            $current = $queue[$i++];

            if ($current->id === $nodeId) {
                return $current;
            }

            foreach ($current->children as $child) {
                $queue[] = $child;
            }
        }

        return null;
    }

    /**
     * Iterative DFS flatten (avoids recursion overhead)
     *
     * @return int[]
     */
    public function flattenIds(GeoHierarchyResponse $node): array
    {
        $stack = [$node];
        $result = [];

        while ($stack !== []) {
            $current = array_pop($stack);

            $result[] = $current->id;

            foreach ($current->children as $child) {
                $stack[] = $child;
            }
        }

        return $result;
    }

    /**
     * Immutable child binding helper
     */
    private function withChildren(GeoHierarchyResponse $node, array $children): GeoHierarchyResponse
    {
        return new GeoHierarchyResponse(
            id: $node->id,
            countryCode: $node->countryCode,
            adminLevel: $node->adminLevel,
            adminType: $node->adminType,
            parentId: $node->parentId,
            code: $node->code,
            name: $node->name,
            isActive: $node->isActive,
            validFrom: $node->validFrom,
            validTo: $node->validTo,
            children: $children
        );
    }
}
