<?php

declare(strict_types=1);

namespace App\Contexts\Governance\API\V1\Controllers;

use App\Contexts\Governance\API\V1\Responses\CommitteeChildrenResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeGovernanceResponse;
use App\Contexts\Governance\API\V1\Responses\CommitteeSummaryResponse;
use App\Contexts\Governance\API\V1\Responses\ErrorResponse;
use App\Contexts\Governance\Application\Ports\CommitteeHierarchyQueryInterface;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\JsonResponse;

final class GovernanceCommitteeController
{
    public function __construct(
        private readonly CommitteeHierarchyQueryInterface $hierarchyQuery,
    ) {}

    public function show(string $id, TenantId $tenantId): JsonResponse
    {
        $records = $this->hierarchyQuery->execute($tenantId);
        $committeeId = CommitteeId::fromString($id);

        foreach ($records as $record) {
            if ($record->id->value() === $committeeId->value()) {
                return new JsonResponse(
                    CommitteeGovernanceResponse::fromArray([
                        'operationalState' => $record->operationalState,
                        'temporalState' => $record->temporalState,
                        'legitimacy' => $record->legitimacy,
                        'canAct' => $record->canAct,
                        'isFullyOperational' => $record->isFullyOperational,
                        'evaluatedAt' => null,
                        'projectionGeneration' => '',
                    ])->jsonSerialize(),
                );
            }
        }

        return ErrorResponse::notFound("Committee not found with ID: {$id}")->toResponse();
    }

    public function children(string $id, TenantId $tenantId): JsonResponse
    {
        $records = $this->hierarchyQuery->execute($tenantId);
        $committeeId = CommitteeId::fromString($id);

        $parent = null;
        $directChildren = [];

        foreach ($records as $record) {
            if ($record->id->value() === $committeeId->value()) {
                $parent = $record;
                continue;
            }
            if ($record->parentId !== null && $record->parentId->value() === $committeeId->value()) {
                $directChildren[] = $record;
            }
        }

        if ($parent === null) {
            return ErrorResponse::notFound("Committee not found with ID: {$id}")->toResponse();
        }

        $summaries = array_map(
            fn ($r) => new CommitteeSummaryResponse(
                id: $r->id->value(),
                name: $r->name,
                level: $r->level,
                type: '',
                operationalState: $r->operationalState,
                canAct: $r->canAct,
            ),
            $directChildren,
        );

        $response = new CommitteeChildrenResponse(
            committee: new CommitteeSummaryResponse(
                id: $parent->id->value(),
                name: $parent->name,
                level: $parent->level,
                type: '',
                operationalState: $parent->operationalState,
                canAct: $parent->canAct,
            ),
            children: $summaries,
            total: count($directChildren),
        );

        return new JsonResponse($response->jsonSerialize());
    }

    public function governance(string $id, TenantId $tenantId): JsonResponse
    {
        $records = $this->hierarchyQuery->execute($tenantId);
        $committeeId = CommitteeId::fromString($id);

        foreach ($records as $record) {
            if ($record->id->value() === $committeeId->value()) {
                return new JsonResponse(
                    CommitteeGovernanceResponse::fromArray([
                        'operationalState' => $record->operationalState,
                        'temporalState' => $record->temporalState,
                        'legitimacy' => $record->legitimacy,
                        'canAct' => $record->canAct,
                        'isFullyOperational' => $record->isFullyOperational,
                        'evaluatedAt' => null,
                        'projectionGeneration' => '',
                    ])->jsonSerialize(),
                );
            }
        }

        return ErrorResponse::notFound("Committee not found with ID: {$id}")->toResponse();
    }
}
