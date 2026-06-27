<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application;

use App\Contexts\Governance\Application\DTOs\GovernanceGeoUnitResponse;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

final class GovernanceGeoUnitResponseTest extends TestCase
{
    #[Test]
    public function from_eloquent_row_sets_all_properties(): void
    {
        $row = (object) [
            'id'                        => 42,
            'code'                      => 'CONT-ASIA',
            'name'                      => 'Asia',
            'name_local'                => '{"en":"Asia","np":"एसिया"}',
            'admin_level'               => 0,
            'admin_type'                => 'continent',
            'parent_id'                 => null,
            'path'                      => '/1/',
            'children_count'            => 5,
            'is_active'                 => 1,
            'country_code'              => 'XX',
            'governance_level'          => 0,
            'governance_committee_name' => 'Global Committee',
            'governance_committee_code' => 'GC',
        ];

        $response = GovernanceGeoUnitResponse::fromEloquentRow($row);

        $this->assertEquals(42, $response->id);
        $this->assertEquals('CONT-ASIA', $response->code);
        $this->assertEquals('Asia', $response->name);
        $this->assertEquals(['en' => 'Asia', 'np' => 'एसिया'], $response->nameLocal);
        $this->assertEquals(0, $response->adminLevel);
        $this->assertEquals('continent', $response->adminType);
        $this->assertNull($response->parentId);
        $this->assertEquals('/1/', $response->path);
        $this->assertEquals(5, $response->childrenCount);
        $this->assertTrue($response->isActive);
        $this->assertEquals('XX', $response->countryCode);
        $this->assertEquals(0, $response->governanceLevel);
        $this->assertEquals('Global Committee', $response->governanceCommitteeName);
        $this->assertEquals('GC', $response->governanceCommitteeCode);
    }

    #[Test]
    public function depth_calculated_from_path_segments(): void
    {
        // Root level: path is '/' → 0 segments → depth 0
        $root = (object) [
            'id' => 1, 'code' => 'WORLD', 'name' => 'World', 'name_local' => '{"en":"World"}',
            'admin_level' => 0, 'admin_type' => 'world', 'parent_id' => null,
            'path' => '/', 'children_count' => 2, 'is_active' => 1,
            'country_code' => 'XX',
        ];
        $this->assertEquals(0, GovernanceGeoUnitResponse::fromEloquentRow($root)->depth);

        // Level 1: path '/1/' → 1 segment → depth 1
        $l1 = (object) [
            'id' => 2, 'code' => 'CONT', 'name' => 'Continent', 'name_local' => '{"en":"Continent"}',
            'admin_level' => 0, 'admin_type' => 'continent', 'parent_id' => 1,
            'path' => '/1/', 'children_count' => 0, 'is_active' => 1,
            'country_code' => 'XX',
        ];
        $this->assertEquals(1, GovernanceGeoUnitResponse::fromEloquentRow($l1)->depth);

        // Level 2: path '/1/2/' → 2 segments → depth 2
        $l2 = (object) [
            'id' => 3, 'code' => 'COUNTRY', 'name' => 'Country', 'name_local' => '{"en":"Country"}',
            'admin_level' => 1, 'admin_type' => 'country', 'parent_id' => 2,
            'path' => '/1/2/', 'children_count' => 0, 'is_active' => 1,
            'country_code' => 'NP',
        ];
        $this->assertEquals(2, GovernanceGeoUnitResponse::fromEloquentRow($l2)->depth);
    }

    #[Test]
    public function depth_is_zero_when_path_is_null(): void
    {
        $row = (object) [
            'id' => 1, 'code' => 'ROOT', 'name' => 'Root', 'name_local' => '{"en":"Root"}',
            'admin_level' => 0, 'admin_type' => 'root', 'parent_id' => null,
            'path' => null, 'children_count' => 0, 'is_active' => 1,
            'country_code' => 'XX',
        ];
        $this->assertEquals(0, GovernanceGeoUnitResponse::fromEloquentRow($row)->depth);
    }

    #[Test]
    public function json_serialize_uses_snake_case_keys(): void
    {
        $row = (object) [
            'id' => 1, 'code' => 'TEST', 'name' => 'Test', 'name_local' => '{"en":"Test"}',
            'admin_level' => 0, 'admin_type' => 'test', 'parent_id' => null,
            'path' => '/', 'children_count' => 0, 'is_active' => 1,
            'country_code' => 'XX', 'governance_level' => null,
            'governance_committee_name' => null, 'governance_committee_code' => null,
        ];

        $serialized = GovernanceGeoUnitResponse::fromEloquentRow($row)->jsonSerialize();

        $this->assertArrayHasKey('id', $serialized);
        $this->assertArrayHasKey('admin_level', $serialized);
        $this->assertArrayHasKey('admin_type', $serialized);
        $this->assertArrayHasKey('parent_id', $serialized);
        $this->assertArrayHasKey('children_count', $serialized);
        $this->assertArrayHasKey('is_active', $serialized);
        $this->assertArrayHasKey('country_code', $serialized);
        $this->assertArrayHasKey('governance_level', $serialized);
        $this->assertArrayHasKey('governance_committee_name', $serialized);
        $this->assertArrayHasKey('governance_committee_code', $serialized);
        $this->assertArrayHasKey('depth', $serialized);
        $this->assertArrayHasKey('name_local', $serialized);
    }

    #[Test]
    public function has_children_returns_false_when_children_null(): void
    {
        $row = $this->makeRow();
        $response = GovernanceGeoUnitResponse::fromEloquentRow($row);
        $this->assertFalse($response->hasChildren());
    }

    #[Test]
    public function has_children_returns_true_when_children_not_empty(): void
    {
        $row = $this->makeRow();
        $response = GovernanceGeoUnitResponse::fromEloquentRow($row);
        $withChildren = $response->withChildren([
            GovernanceGeoUnitResponse::fromEloquentRow($row),
        ]);
        $this->assertTrue($withChildren->hasChildren());
    }

    #[Test]
    public function with_children_creates_new_instance_without_mutating_original(): void
    {
        $row = $this->makeRow();
        $original = GovernanceGeoUnitResponse::fromEloquentRow($row);
        $child = GovernanceGeoUnitResponse::fromEloquentRow($row);

        $modified = $original->withChildren([$child]);

        $this->assertNull($original->children);
        $this->assertNotNull($modified->children);
        $this->assertCount(1, $modified->children);
        // Verify other properties are preserved
        $this->assertEquals($original->id, $modified->id);
        $this->assertEquals($original->code, $modified->code);
    }

    private function makeRow(): \stdClass
    {
        return (object) [
            'id' => 1, 'code' => 'TEST', 'name' => 'Test', 'name_local' => '{"en":"Test"}',
            'admin_level' => 0, 'admin_type' => 'test', 'parent_id' => null,
            'path' => '/', 'children_count' => 0, 'is_active' => 1,
            'country_code' => 'XX', 'governance_level' => null,
            'governance_committee_name' => null, 'governance_committee_code' => null,
        ];
    }
}
