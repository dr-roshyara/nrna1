<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Responses;

use App\Contexts\Governance\API\V1\Responses\CommitteeGovernanceResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeHierarchyResponse;
use PHPUnit\Framework\TestCase;

final class CommitteeHierarchyResponseTest extends TestCase
{
    private CommitteeGovernanceResponse $governance;

    protected function setUp(): void
    {
        $this->governance = new CommitteeGovernanceResponse(
            operationalState: 'ACTIVE',
            temporalState: 'CURRENT',
            legitimacy: 'CONSTITUTIONAL',
            canAct: true,
            isFullyOperational: true,
            termStart: null,
            termEnd: null,
            evaluatedAt: null,
            projectionGeneration: 'gen-001',
        );
    }

    public function test_constructs_with_required_fields(): void
    {
        $response = new CommitteeHierarchyResponse(
            id: 'cmt-001',
            name: 'Root Committee',
            level: 0,
            parentId: null,
            childrenCount: 0,
            children: [],
            governance: $this->governance,
        );

        $this->assertSame('cmt-001', $response->id);
        $this->assertNull($response->parentId);
        $this->assertEmpty($response->children);
    }

    public function test_constructs_with_children(): void
    {
        $child = new CommitteeHierarchyResponse(
            id: 'cmt-002',
            name: 'Child Committee',
            level: 1,
            parentId: 'cmt-001',
            childrenCount: 0,
            children: [],
            governance: $this->governance,
        );

        $parent = new CommitteeHierarchyResponse(
            id: 'cmt-001',
            name: 'Root Committee',
            level: 0,
            parentId: null,
            childrenCount: 1,
            children: [$child],
            governance: $this->governance,
        );

        $this->assertCount(1, $parent->children);
        $this->assertSame('cmt-002', $parent->children[0]->id);
    }

    public function test_json_serialize_includes_nested_children(): void
    {
        $child = new CommitteeHierarchyResponse(
            id: 'cmt-002',
            name: 'Child',
            level: 1,
            parentId: 'cmt-001',
            childrenCount: 0,
            children: [],
            governance: $this->governance,
        );

        $parent = new CommitteeHierarchyResponse(
            id: 'cmt-001',
            name: 'Root',
            level: 0,
            parentId: null,
            childrenCount: 1,
            children: [$child],
            governance: $this->governance,
        );

        $serialized = $parent->jsonSerialize();

        $this->assertSame('Root', $serialized['name']);
        $this->assertCount(1, $serialized['children']);
        $this->assertSame('Child', $serialized['children'][0]['name']);
        $this->assertArrayHasKey('governance', $serialized);
        $this->assertSame('ACTIVE', $serialized['governance']['operationalState']);
    }
}
