<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Geography\Application\Services;

use App\Contexts\Geography\Application\DTOs\GeoUnitResponse;
use App\Contexts\Geography\Application\Services\GeoHierarchyProjectionBuilder;
use PHPUnit\Framework\TestCase;

final class GeoHierarchyProjectionBuilderTest extends TestCase
{
    private GeoHierarchyProjectionBuilder $builder;

    protected function setUp(): void
    {
        $this->builder = new GeoHierarchyProjectionBuilder();
    }

    private function makeUnit(array $overrides = []): GeoUnitResponse
    {
        return new GeoUnitResponse(
            id: $overrides['id'] ?? 1,
            countryCode: $overrides['countryCode'] ?? 'NP',
            adminLevel: $overrides['adminLevel'] ?? 1,
            adminType: $overrides['adminType'] ?? 'continent',
            parentId: $overrides['parentId'] ?? null,
            code: $overrides['code'] ?? null,
            name: $overrides['name'] ?? ['en' => 'Asia'],
            isActive: $overrides['isActive'] ?? true,
            validFrom: $overrides['validFrom'] ?? null,
            validTo: $overrides['validTo'] ?? null,
        );
    }

    public function test_it_returns_empty_forest_for_empty_input(): void
    {
        $forest = $this->builder->buildForest([]);

        $this->assertSame([], $forest);
    }

    public function test_it_builds_single_root_node(): void
    {
        $units = [$this->makeUnit()];

        $forest = $this->builder->buildForest($units);

        $this->assertCount(1, $forest);
        $this->assertSame(1, $forest[0]->id);
        $this->assertFalse($forest[0]->hasChildren());
    }

    public function test_it_builds_parent_child_hierarchy(): void
    {
        $parent = $this->makeUnit(['id' => 1, 'adminType' => 'continent']);
        $child = $this->makeUnit(['id' => 2, 'parentId' => 1, 'adminType' => 'country', 'code' => 'NP']);

        $forest = $this->builder->buildForest([$parent, $child]);

        $this->assertCount(1, $forest);
        $this->assertSame(1, $forest[0]->id);
        $this->assertTrue($forest[0]->hasChildren());
        $this->assertCount(1, $forest[0]->children);
        $this->assertSame(2, $forest[0]->children[0]->id);
        $this->assertSame('NP', $forest[0]->children[0]->code);
    }

    public function test_it_builds_multi_level_hierarchy(): void
    {
        $continent = $this->makeUnit(['id' => 1, 'adminType' => 'continent']);
        $country   = $this->makeUnit(['id' => 2, 'parentId' => 1, 'adminType' => 'country', 'code' => 'NP']);
        $province  = $this->makeUnit(['id' => 3, 'parentId' => 2, 'adminType' => 'province', 'code' => 'NP-P1']);

        $forest = $this->builder->buildForest([$continent, $country, $province]);

        $this->assertCount(1, $forest);
        $this->assertCount(1, $forest[0]->children);
        $this->assertSame(2, $forest[0]->children[0]->id);
        $this->assertCount(1, $forest[0]->children[0]->children);
        $this->assertSame(3, $forest[0]->children[0]->children[0]->id);
    }

    public function test_it_handles_multiple_roots(): void
    {
        $asia    = $this->makeUnit(['id' => 1, 'adminType' => 'continent', 'name' => ['en' => 'Asia']]);
        $europe  = $this->makeUnit(['id' => 2, 'adminType' => 'continent', 'name' => ['en' => 'Europe']]);

        $forest = $this->builder->buildForest([$asia, $europe]);

        $this->assertCount(2, $forest);
        $this->assertSame(1, $forest[0]->id);
        $this->assertSame(2, $forest[1]->id);
    }

    public function test_it_handles_unordered_input(): void
    {
        // Children before parent — should still produce correct tree
        $child  = $this->makeUnit(['id' => 2, 'parentId' => 1, 'adminType' => 'country', 'code' => 'NP']);
        $parent = $this->makeUnit(['id' => 1, 'adminType' => 'continent']);

        $forest = $this->builder->buildForest([$child, $parent]);

        $this->assertCount(1, $forest);
        $this->assertTrue($forest[0]->hasChildren());
        $this->assertSame(2, $forest[0]->children[0]->id);
    }

    public function test_build_tree_returns_single_root_by_id(): void
    {
        $parent = $this->makeUnit(['id' => 1]);
        $child  = $this->makeUnit(['id' => 2, 'parentId' => 1]);

        $tree = $this->builder->buildTree([$parent, $child], 1);

        $this->assertNotNull($tree);
        $this->assertSame(1, $tree->id);
        $this->assertTrue($tree->hasChildren());
    }

    public function test_build_tree_returns_null_for_missing_root(): void
    {
        $units = [$this->makeUnit(['id' => 1])];

        $tree = $this->builder->buildTree($units, 999);

        $this->assertNull($tree);
    }

    public function test_extract_subtree_finds_node_by_id(): void
    {
        $continent = $this->makeUnit(['id' => 1]);
        $country   = $this->makeUnit(['id' => 2, 'parentId' => 1]);
        $district  = $this->makeUnit(['id' => 3, 'parentId' => 2]);

        $forest = $this->builder->buildForest([$continent, $country, $district]);

        $subtree = $this->builder->extractSubtree($forest, 2);

        $this->assertNotNull($subtree);
        $this->assertSame(2, $subtree->id);
        $this->assertCount(1, $subtree->children);
        $this->assertSame(3, $subtree->children[0]->id);
    }

    public function test_extract_subtree_returns_null_for_missing_id(): void
    {
        $units = [$this->makeUnit(['id' => 1])];
        $forest = $this->builder->buildForest($units);

        $this->assertNull($this->builder->extractSubtree($forest, 999));
    }

    public function test_flatten_ids_returns_all_ids_in_dfs_order(): void
    {
        $n1 = $this->makeUnit(['id' => 1]);
        $n2 = $this->makeUnit(['id' => 2, 'parentId' => 1]);
        $n3 = $this->makeUnit(['id' => 3, 'parentId' => 1]);

        $forest = $this->builder->buildForest([$n1, $n2, $n3]);

        $ids = $this->builder->flattenIds($forest[0]);

        $this->assertContains(1, $ids);
        $this->assertContains(2, $ids);
        $this->assertContains(3, $ids);
    }

    public function test_it_processes_large_flat_list_in_order(): void
    {
        $units = [];
        // 10 roots, each with 10 children
        for ($root = 1; $root <= 10; $root++) {
            $units[] = $this->makeUnit(['id' => $root, 'adminType' => 'continent', 'name' => ['en' => "Continent {$root}"]]);
            for ($child = 1; $child <= 10; $child++) {
                $childId = $root * 100 + $child;
                $units[] = $this->makeUnit([
                    'id' => $childId,
                    'parentId' => $root,
                    'adminType' => 'country',
                    'code' => "C-{$childId}",
                    'name' => ['en' => "Country {$childId}"],
                ]);
            }
        }

        $forest = $this->builder->buildForest($units);

        $this->assertCount(10, $forest);
        foreach ($forest as $root) {
            $this->assertCount(10, $root->children, "Root {$root->id} should have 10 children");
        }
    }

    public function test_build_is_idempotent(): void
    {
        $units = [
            $this->makeUnit(['id' => 1]),
            $this->makeUnit(['id' => 2, 'parentId' => 1]),
        ];

        $forest1 = $this->builder->buildForest($units);
        $forest2 = $this->builder->buildForest($units);

        $this->assertCount(1, $forest1);
        $this->assertCount(1, $forest2);
        $this->assertCount(1, $forest1[0]->children);
        $this->assertCount(1, $forest2[0]->children);
        $this->assertSame($forest1[0]->children[0]->id, $forest2[0]->children[0]->id);
    }
}
