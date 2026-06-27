<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Application\UseCases;

use App\Contexts\Governance\Application\DTOs\CommitteeTreeNode;
use App\Contexts\Governance\Application\Ports\CacheInterface;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Governance\Application\Services\CommitteeHierarchyBuilder;
use App\Contexts\Governance\Infrastructure\Repositories\CommitteeHierarchyRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class GetCommitteeHierarchy implements CommitteeHierarchyQueryInterface
{
    private const CACHE_TTL = 300;

    public function __construct(
        private CommitteeHierarchyRepository $repository,
        private CommitteeHierarchyBuilder $builder,
        private CacheInterface $cache,
    ) {}

    // Projection fields are eventually consistent — NOT authoritative domain truth. See docs/ARCHITECTURE.md
    /**
     * @return CommitteeTreeNode[]
     */
    public function execute(TenantId $tenantId): array
    {
        $cacheKey = $this->cacheKey($tenantId);

        $records = $this->cache->get($cacheKey);

        if ($records === null) {
            $records = $this->repository->getAll($tenantId);
            $this->cache->set($cacheKey, $records, self::CACHE_TTL);
        }

        return $this->builder->buildTree($records);
    }

    public function invalidateCache(TenantId $tenantId): void
    {
        $this->cache->delete($this->cacheKey($tenantId));
    }

    public function getProjectionGeneration(TenantId $tenantId): string
    {
        return $this->repository->getProjectionGeneration($tenantId);
    }

    // Cache key includes generation to invalidate after rebuilds — still eventually consistent
    private function cacheKey(TenantId $tenantId): string
    {
        $generation = $this->repository->getProjectionGeneration($tenantId);

        return 'hierarchy_records_' . $tenantId->value() . '_' . $generation;
    }
}
