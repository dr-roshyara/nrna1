<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Geography\Application\DTOs;

use App\Contexts\Geography\Application\DTOs\GeoHierarchyResponse;
use PHPUnit\Framework\TestCase;

final class GeoHierarchyResponseTest extends TestCase
{
    private function makeNode(array $overrides = []): GeoHierarchyResponse
    {
        return new GeoHierarchyResponse(
            id: $overrides['id'] ?? 100,
            countryCode: $overrides['countryCode'] ?? 'NP',
            adminLevel: $overrides['adminLevel'] ?? 1,
            adminType: $overrides['adminType'] ?? 'continent',
            parentId: $overrides['parentId'] ?? null,
            code: $overrides['code'] ?? null,
            name: $overrides['name'] ?? ['en' => 'Asia'],
            isActive: $overrides['isActive'] ?? true,
            validFrom: $overrides['validFrom'] ?? null,
            validTo: $overrides['validTo'] ?? null,
            children: $overrides['children'] ?? [],
        );
    }

    public function test_it_is_leaf_node_by_default(): void
    {
        $node = $this->makeNode();

        $this->assertSame(100, $node->id);
        $this->assertFalse($node->hasChildren());
        $this->assertSame([], $node->children);
    }

    public function test_it_supports_direct_child_relationship(): void
    {
        $child = $this->makeNode([
            'id' => 200,
            'adminLevel' => 2,
            'adminType' => 'country',
            'parentId' => 100,
            'code' => 'NP',
            'name' => ['en' => 'Nepal'],
        ]);

        $parent = $this->makeNode([
            'children' => [$child],
        ]);

        $this->assertTrue($parent->hasChildren());
        $this->assertCount(1, $parent->children);

        $this->assertSame(200, $parent->children[0]->id);
        $this->assertSame('country', $parent->children[0]->adminType);
    }

    public function test_it_supports_recursive_tree_structure(): void
    {
        $leaf = $this->makeNode([
            'id' => 300,
            'adminLevel' => 4,
            'adminType' => 'district',
            'parentId' => 200,
            'code' => 'NP-D1',
            'name' => ['en' => 'District 1'],
        ]);

        $child = $this->makeNode([
            'id' => 200,
            'adminLevel' => 2,
            'adminType' => 'country',
            'parentId' => 100,
            'code' => 'NP',
            'name' => ['en' => 'Nepal'],
            'children' => [$leaf],
        ]);

        $root = $this->makeNode([
            'children' => [$child],
        ]);

        // --- structural assertions (not JSON-dependent)
        $this->assertCount(1, $root->children);
        $this->assertSame(200, $root->children[0]->id);

        $this->assertCount(1, $root->children[0]->children);
        $this->assertSame(300, $root->children[0]->children[0]->id);

        $this->assertSame('district', $root->children[0]->children[0]->adminType);
    }

    public function test_it_serializes_consistently_for_projection_layer(): void
    {
        $leaf = $this->makeNode([
            'id' => 2,
            'adminType' => 'district',
        ]);

        $child = $this->makeNode([
            'id' => 1,
            'adminType' => 'country',
            'children' => [$leaf],
        ]);

        $root = $this->makeNode([
            'children' => [$child],
        ]);

        $data = $root->jsonSerialize();

        $this->assertSame(100, $data['id']);
        $this->assertIsArray($data['children']);

        $this->assertSame(1, $data['children'][0]['id']);
        $this->assertSame(2, $data['children'][0]['children'][0]['id']);
    }

    public function test_it_is_immutable_read_model(): void
    {
        $ref = new \ReflectionClass(GeoHierarchyResponse::class);

        $this->assertTrue($ref->isFinal());
        $this->assertTrue($ref->isReadOnly());
    }

    public function test_it_is_json_serializable(): void
    {
        $node = $this->makeNode();

        $this->assertInstanceOf(\JsonSerializable::class, $node);
        $this->assertNotFalse(json_encode($node));
    }
}
