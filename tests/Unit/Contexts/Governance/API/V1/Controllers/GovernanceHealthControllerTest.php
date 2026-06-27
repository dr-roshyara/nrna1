<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Controllers;

use App\Contexts\Governance\API\V1\Controllers\GovernanceHealthController;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GovernanceHealthControllerTest extends TestCase
{
    private CommitteeHierarchyQueryInterface&MockObject $hierarchyQuery;
    private GovernanceHealthController $controller;
    private TenantId $tenantId;

    protected function setUp(): void
    {
        $this->hierarchyQuery = $this->createMock(CommitteeHierarchyQueryInterface::class);
        $this->controller = new GovernanceHealthController($this->hierarchyQuery);
        $this->tenantId = TenantId::fromString('tenant-1');
    }

    public function test_projections_returns_healthy_when_generation_exists(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->with($this->tenantId)
            ->willReturn([]);

        $this->hierarchyQuery->expects($this->once())
            ->method('getProjectionGeneration')
            ->with($this->tenantId)
            ->willReturn('gen-001');

        $response = $this->controller->projections($this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertSame('healthy', $data['status']);
        $this->assertSame('gen-001', $data['projectionGeneration']);
        $this->assertSame(0, $data['totalCommittees']);
    }

    public function test_projections_returns_stale_when_no_generation(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $this->hierarchyQuery->expects($this->once())
            ->method('getProjectionGeneration')
            ->willReturn('none');

        $response = $this->controller->projections($this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertSame('stale', $data['status']);
        $this->assertSame('none', $data['projectionGeneration']);
    }

    public function test_projections_includes_freshness_metadata(): void
    {
        $this->hierarchyQuery->method('execute')->willReturn([]);
        $this->hierarchyQuery->method('getProjectionGeneration')->willReturn('gen-001');

        $response = $this->controller->projections($this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertArrayHasKey('projectionGeneration', $data);
        $this->assertArrayHasKey('staleCommitteeCount', $data);
        $this->assertArrayHasKey('totalCommittees', $data);
        $this->assertArrayHasKey('projectionAgeMs', $data);
        $this->assertArrayHasKey('rebuiltAt', $data);
    }
}
