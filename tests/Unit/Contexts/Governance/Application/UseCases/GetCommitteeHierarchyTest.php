<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Governance\Application\UseCases;

use App\Contexts\Governance\Application\DTOs\CommitteeHierarchyRecord;
use App\Contexts\Governance\Application\DTOs\CommitteeTreeNode;
use App\Contexts\Governance\Application\Ports\CacheInterface;
use App\Contexts\Governance\Application\Services\CommitteeHierarchyBuilder;
use App\Contexts\Governance\Application\UseCases\GetCommitteeHierarchy;
use App\Contexts\Governance\Infrastructure\Repositories\CommitteeHierarchyRepository;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId as UlidCommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use PHPUnit\Framework\TestCase;

final class GetCommitteeHierarchyTest extends TestCase
{
    private CommitteeHierarchyRepository $repository;
    private CommitteeHierarchyBuilder $builder;
    private CacheInterface $cache;
    private GetCommitteeHierarchy $useCase;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(CommitteeHierarchyRepository::class);
        $this->builder = new CommitteeHierarchyBuilder();
        $this->cache = $this->createMock(CacheInterface::class);

        $this->useCase = new GetCommitteeHierarchy(
            $this->repository,
            $this->builder,
            $this->cache,
        );
    }

    public function test_returns_hierarchy_tree(): void
    {
        $tenantId = TenantId::fromString('tenant-a');
        $cid = '01ARZ3NDEKTSV4RRFFQ69G5FAV';

        $record = new CommitteeHierarchyRecord(
            id: UlidCommitteeId::fromString($cid),
            name: 'ICC Global',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: null,
            termEnd: null,
        );

        $this->cache
            ->expects($this->once())
            ->method('get')
            ->willReturn(null);

        $this->repository
            ->expects($this->once())
            ->method('getAll')
            ->with($tenantId)
            ->willReturn([$record]);

        $this->cache
            ->expects($this->once())
            ->method('set')
            ->with($this->stringContains('hierarchy_records_tenant-a'), [$record], 300);

        $this->repository
            ->method('getProjectionGeneration')
            ->willReturn('abc123');

        $result = $this->useCase->execute($tenantId);

        $this->assertCount(1, $result);
        $this->assertInstanceOf(CommitteeTreeNode::class, $result[0]);
        $this->assertSame('ICC Global', $result[0]->name);
        $this->assertNull($result[0]->parentId);
    }

    public function test_caches_records_not_trees(): void
    {
        $tenantId = TenantId::fromString('tenant-b');

        $record = new CommitteeHierarchyRecord(
            id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            name: 'ICC Global',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: null,
            termEnd: null,
        );

        $callCount = 0;
        $this->cache
            ->expects($this->exactly(2))
            ->method('get')
            ->willReturnCallback(function () use ($record, &$callCount) {
                $callCount++;
                return $callCount === 1 ? null : [$record];
            });

        $this->repository
            ->expects($this->once())
            ->method('getAll')
            ->willReturn([$record]);

        $this->repository
            ->method('getProjectionGeneration')
            ->willReturn('abc123');

        $result1 = $this->useCase->execute($tenantId);
        $this->assertCount(1, $result1);

        $result2 = $this->useCase->execute($tenantId);
        $this->assertCount(1, $result2);
    }

    public function test_handles_empty_tenant(): void
    {
        $tenantId = TenantId::fromString('empty-tenant');

        $this->cache
            ->method('get')
            ->willReturn(null);

        $this->repository
            ->method('getAll')
            ->willReturn([]);

        $this->repository
            ->method('getProjectionGeneration')
            ->willReturn('none');

        $result = $this->useCase->execute($tenantId);

        $this->assertIsArray($result);
        $this->assertEmpty($result);
    }

    public function test_cache_invalidation(): void
    {
        $tenantId = TenantId::fromString('tenant-c');

        $record = new CommitteeHierarchyRecord(
            id: UlidCommitteeId::fromString('01ARZ3NDEKTSV4RRFFQ69G5FAV'),
            name: 'ICC Global',
            level: 0,
            parentId: null,
            operationalState: 'ACTIVE',
            temporalState: 'VALID',
            legitimacy: 'LEGITIMATE',
            canAct: true,
            isFullyOperational: true,
            projectionVersion: 1,
            termStart: null,
            termEnd: null,
        );

        $cacheState = [];
        $this->cache
            ->method('get')
            ->willReturnCallback(function (string $key) use (&$cacheState) {
                return $cacheState[$key] ?? null;
            });
        $this->cache
            ->method('set')
            ->willReturnCallback(function (string $key, mixed $value, int $ttl) use (&$cacheState) {
                $cacheState[$key] = $value;
            });
        $this->cache
            ->method('delete')
            ->willReturnCallback(function (string $key) use (&$cacheState) {
                unset($cacheState[$key]);
            });

        $this->repository
            ->expects($this->exactly(2))
            ->method('getAll')
            ->willReturn([$record]);

        $this->repository
            ->method('getProjectionGeneration')
            ->willReturn('abc123');

        $this->useCase->execute($tenantId);

        $this->useCase->invalidateCache($tenantId);

        $result = $this->useCase->execute($tenantId);

        $this->assertIsArray($result);
    }
}
