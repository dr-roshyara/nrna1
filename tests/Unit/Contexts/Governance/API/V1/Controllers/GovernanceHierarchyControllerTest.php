<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Controllers;

use App\Contexts\Governance\API\V1\Controllers\GovernanceHierarchyController;
use App\Contexts\Governance\API\V1\Requests\HierarchyQueryRequest;
use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId as RecordCommitteeId;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GovernanceHierarchyControllerTest extends TestCase
{
    private CommitteeHierarchyQueryInterface&MockObject $hierarchyQuery;
    private GovernanceHierarchyController $controller;
    private TenantId $tenantId;

    private CommitteeHierarchyRecord $rootRecord;
    private CommitteeHierarchyRecord $childRecord;

    protected function setUp(): void
    {
        $this->hierarchyQuery = $this->createMock(CommitteeHierarchyQueryInterface::class);
        $this->controller = new GovernanceHierarchyController($this->hierarchyQuery);
        $this->tenantId = TenantId::fromString('tenant-1');

        $rootId = RecordCommitteeId::generate()->value();
        $childId = RecordCommitteeId::generate()->value();

        $this->rootRecord = new CommitteeHierarchyRecord(
            id: new RecordCommitteeId($rootId),
            name: 'National Committee',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'CURRENT',
            legitimacy: 'CONSTITUTIONAL',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: null,
            termEnd: null,
        );

        $this->childRecord = new CommitteeHierarchyRecord(
            id: new RecordCommitteeId($childId),
            name: 'Province Committee',
            level: 1,
            parentId: new RecordCommitteeId($rootId),
            operationalState: 'ACTIVE',
            temporalState: 'CURRENT',
            legitimacy: 'CONSTITUTIONAL',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: null,
            termEnd: null,
        );
    }

    public function test_hierarchy_returns_200_with_data(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET');

        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->with($this->tenantId)
            ->willReturn([$this->rootRecord, $this->childRecord]);

        $this->hierarchyQuery->expects($this->once())
            ->method('getProjectionGeneration')
            ->willReturn('gen-001');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertArrayHasKey('data', $data);
        $this->assertArrayHasKey('meta', $data);
        $this->assertCount(1, $data['data']); // one root
        $this->assertSame('National Committee', $data['data'][0]['name']);
        $this->assertCount(1, $data['data'][0]['children']);
    }

    public function test_hierarchy_returns_cache_control_header(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET');

        $this->hierarchyQuery->method('execute')->willReturn([]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-001');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $this->assertSame(200, $response->status());
        $this->assertStringContainsString('max-age=60', $response->headers->get('Cache-Control'));
        $this->assertStringContainsString('stale-while-revalidate=300', $response->headers->get('Cache-Control'));
    }

    public function test_hierarchy_returns_empty_array_when_no_committees(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET');

        $this->hierarchyQuery->method('execute')->willReturn([]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-001');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $data = $response->getData(true);
        $this->assertEmpty($data['data']);
        $this->assertSame(0, $data['meta']['totalRoots']);
    }

    public function test_hierarchy_depth_limits_tree(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET', ['depth' => 0]);

        $this->hierarchyQuery->method('execute')->willReturn([$this->rootRecord, $this->childRecord]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-001');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $data = $response->getData(true);
        $this->assertCount(1, $data['data']);
        $this->assertEmpty($data['data'][0]['children']); // depth 0 = no children expanded
    }

    public function test_hierarchy_search_filters_by_name(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET', ['search' => 'Province']);

        $this->hierarchyQuery->method('execute')->willReturn([$this->rootRecord, $this->childRecord]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-001');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $data = $response->getData(true);
        // "Province Committee" is a child; root doesn't match search so only child appears under root
        // Since no root matches, the tree has no roots
        $this->assertEmpty($data['data']);
    }

    public function test_hierarchy_state_filter(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET', ['state' => 'INACTIVE']);

        $this->hierarchyQuery->method('execute')->willReturn([$this->rootRecord, $this->childRecord]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-001');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $data = $response->getData(true);
        // Both are ACTIVE, not INACTIVE
        $this->assertEmpty($data['data']);
    }

    public function test_hierarchy_meta_includes_projection_generation(): void
    {
        $request = HierarchyQueryRequest::create('/api/v1/governance/hierarchy', 'GET');

        $this->hierarchyQuery->method('execute')->willReturn([]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-abc');

        $response = $this->controller->hierarchy($request, $this->tenantId);

        $data = $response->getData(true);
        $this->assertSame('gen-abc', $data['meta']['projectionGeneration']);
        $this->assertFalse($data['meta']['stale']);
        $this->assertArrayHasKey('projectionAgeMs', $data['meta']);
        $this->assertArrayHasKey('projectionEvaluatedAt', $data['meta']);
    }
}
