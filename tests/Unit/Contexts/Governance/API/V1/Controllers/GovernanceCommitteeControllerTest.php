<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\API\V1\Controllers;

use App\Contexts\Governance\API\V1\Controllers\GovernanceCommitteeController;
use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId as RecordCommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class GovernanceCommitteeControllerTest extends TestCase
{
    private CommitteeHierarchyQueryInterface&MockObject $hierarchyQuery;
    private GovernanceCommitteeController $controller;
    private TenantId $tenantId;

    private CommitteeHierarchyRecord $rootRecord;
    private CommitteeHierarchyRecord $childRecord;
    private string $rootId;
    private string $childId;

    protected function setUp(): void
    {
        $this->hierarchyQuery = $this->createMock(CommitteeHierarchyQueryInterface::class);
        $this->controller = new GovernanceCommitteeController($this->hierarchyQuery);
        $this->tenantId = TenantId::fromString('tenant-1');

        $this->rootId = CommitteeId::generate()->value();
        $this->childId = CommitteeId::generate()->value();

        $this->rootRecord = new CommitteeHierarchyRecord(
            id: new RecordCommitteeId($this->rootId),
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
            id: new RecordCommitteeId($this->childId),
            name: 'Province Committee',
            level: 1,
            parentId: new RecordCommitteeId($this->rootId),
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

    public function test_show_returns_200_for_valid_id(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->with($this->tenantId)
            ->willReturn([$this->rootRecord, $this->childRecord]);

        $response = $this->controller->show($this->rootId, $this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertSame('ACTIVE', $data['operationalState']);
        $this->assertSame('CONSTITUTIONAL', $data['legitimacy']);
    }

    public function test_show_returns_404_for_unknown_id(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([$this->rootRecord]);

        $response = $this->controller->show('unknown', $this->tenantId);

        $this->assertSame(404, $response->status());
        $data = $response->getData(true);
        $this->assertSame('COMMITTEE_NOT_FOUND', $data['error']['code']);
    }

    public function test_children_returns_200_for_valid_parent(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([$this->rootRecord, $this->childRecord]);

        $response = $this->controller->children($this->rootId, $this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertSame('National Committee', $data['committee']['name']);
        $this->assertCount(1, $data['children']);
        $this->assertSame('Province Committee', $data['children'][0]['name']);
    }

    public function test_children_returns_empty_when_no_children(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([$this->rootRecord]);

        $response = $this->controller->children($this->rootId, $this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertCount(0, $data['children']);
        $this->assertSame(0, $data['total']);
    }

    public function test_children_returns_404_for_unknown_id(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $response = $this->controller->children('unknown', $this->tenantId);

        $this->assertSame(404, $response->status());
    }

    public function test_governance_returns_200_for_valid_id(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([$this->rootRecord]);

        $response = $this->controller->governance($this->rootId, $this->tenantId);

        $this->assertSame(200, $response->status());
        $data = $response->getData(true);
        $this->assertSame('ACTIVE', $data['operationalState']);
        $this->assertSame('CURRENT', $data['temporalState']);
    }

    public function test_governance_returns_404_for_unknown_id(): void
    {
        $this->hierarchyQuery->expects($this->once())
            ->method('execute')
            ->willReturn([]);

        $response = $this->controller->governance('unknown', $this->tenantId);

        $this->assertSame(404, $response->status());
    }
}
