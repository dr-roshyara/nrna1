<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Controllers;

use App\Contexts\Governance\API\V1\Responses\GovernanceHealthResponse;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\JsonResponse;

final class GovernanceHealthController
{
    public function __construct(
        private readonly CommitteeHierarchyQueryInterface $hierarchyQuery,
    ) {}

    public function projections(TenantId $tenantId): JsonResponse
    {
        $records = $this->hierarchyQuery->execute($tenantId);
        $generation = $this->hierarchyQuery->getProjectionGeneration($tenantId);

        $response = new GovernanceHealthResponse(
            status: $generation !== 'none' ? 'healthy' : 'stale',
            projectionGeneration: $generation,
            rebuiltAt: null,
            staleCommitteeCount: 0,
            totalCommittees: count($records),
            projectionAgeMs: 0,
        );

        return new JsonResponse($response->jsonSerialize());
    }
}
