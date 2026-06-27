<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Responses;

use App\Contexts\Governance\API\V1\Responses\CommitteeChildrenResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeSummaryResponse;
use PHPUnit\Framework\TestCase;

final class CommitteeChildrenResponseTest extends TestCase
{
    public function test_constructs_with_children(): void
    {
        $parent = new CommitteeSummaryResponse(
            id: 'cmt-001', name: 'Parent', level: 0,
            type: 'central', operationalState: 'ACTIVE', canAct: true,
        );

        $child = new CommitteeSummaryResponse(
            id: 'cmt-002', name: 'Child', level: 1,
            type: 'province', operationalState: 'ACTIVE', canAct: true,
        );

        $response = new CommitteeChildrenResponse(
            committee: $parent,
            children: [$child],
            total: 1,
        );

        $this->assertSame('cmt-001', $response->committee->id);
        $this->assertCount(1, $response->children);
        $this->assertSame(1, $response->total);
    }

    public function test_constructs_with_empty_children(): void
    {
        $parent = new CommitteeSummaryResponse(
            id: 'cmt-001', name: 'Leaf', level: 2,
            type: 'ward', operationalState: 'ACTIVE', canAct: true,
        );

        $response = new CommitteeChildrenResponse(
            committee: $parent,
            children: [],
            total: 0,
        );

        $this->assertEmpty($response->children);
        $this->assertSame(0, $response->total);
    }

    public function test_json_serialize_returns_expected_structure(): void
    {
        $parent = new CommitteeSummaryResponse(
            id: 'cmt-001', name: 'Parent', level: 0,
            type: 'central', operationalState: 'ACTIVE', canAct: true,
        );

        $response = new CommitteeChildrenResponse($parent, [], 0);

        $serialized = $response->jsonSerialize();

        $this->assertArrayHasKey('committee', $serialized);
        $this->assertArrayHasKey('children', $serialized);
        $this->assertArrayHasKey('total', $serialized);
        $this->assertSame('Parent', $serialized['committee']['name']);
        $this->assertEmpty($serialized['children']);
        $this->assertSame(0, $serialized['total']);
    }
}
