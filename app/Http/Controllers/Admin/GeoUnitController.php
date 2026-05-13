<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contexts\Governance\Application\DTOs\GeoUnitQueryFilter;
use App\Contexts\Governance\Application\Services\GovernanceGeoUnitQueryService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoUnitController extends Controller
{
    public function __construct(
        private readonly GovernanceGeoUnitQueryService $queryService,
    ) {}

    private function getTenantId(Request $request): string
    {
        $organisation = $request->attributes->get('organisation');
        return $organisation ? $organisation->id : $request->user()->current_organisation_id;
    }

    /**
     * Get flat list of governance-projected geo units with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        $filter = GeoUnitQueryFilter::fromRequest($request->all());
        $units = $this->queryService->fetchFlat($this->getTenantId($request), $filter);

        return response()->json(['data' => $units]);
    }

    /**
     * Get tree-ready flat list with depth pre-calculated.
     */
    public function tree(Request $request): JsonResponse
    {
        $filter = GeoUnitQueryFilter::fromRequest($request->all());
        $units = $this->queryService->fetchTree($this->getTenantId($request), $filter);

        return response()->json(['data' => $units]);
    }

    /**
     * Get a single geo unit with breadcrumb trail.
     */
    public function show(Request $request): JsonResponse
    {
        $id = (int) $request->route('id');
        $tenantId = $this->getTenantId($request);

        $unit = $this->queryService->fetchById($tenantId, $id);

        if (!$unit) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $breadcrumb = $this->queryService->breadcrumb($tenantId, $id);

        return response()->json([
            'data' => $unit->jsonSerialize() + ['breadcrumb' => $breadcrumb],
        ]);
    }

    /**
     * Search/lookup endpoint for cascader/autocomplete.
     */
    public function lookup(Request $request): JsonResponse
    {
        $q = $request->get('q', '');
        $results = $this->queryService->lookup($this->getTenantId($request), $q);

        return response()->json(['data' => $results]);
    }
}
