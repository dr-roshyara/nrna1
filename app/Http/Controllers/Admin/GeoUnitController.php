<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Contexts\Governance\Application\DTOs\GeoUnitQueryFilter;
use App\Contexts\Governance\Application\Services\GovernanceGeoUnitQueryService;
use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GeoUnitController extends Controller
{
    public function __construct(
        private readonly GovernanceGeoUnitQueryService $queryService,
    ) {}

    private function getTenantIdForGovernance(Request $request): string
    {
        // Geo units are SHARED reference data
        // but governance levels ARE tenant-specific
        $organisation = $request->route('organisation');
        if ($organisation) {
            return $organisation->id;
        }

        return '';
    }

    /**
     * Get flat list of governance-projected geo units with optional filters.
     */
    public function index(Request $request): JsonResponse
    {
        $filter = GeoUnitQueryFilter::fromRequest($request->all());
        $units = $this->queryService->fetchFlat($this->getTenantIdForGovernance($request), $filter);

        return response()->json(['data' => $units]);
    }

    /**
     * Get tree-ready flat list with depth pre-calculated.
     */
    public function tree(Request $request): JsonResponse
    {
        $filter = GeoUnitQueryFilter::fromRequest($request->all());
        $units = $this->queryService->fetchTree($this->getTenantIdForGovernance($request), $filter);

        return response()->json(['data' => $units]);
    }

    /**
     * Get a single geo unit with breadcrumb trail.
     */
    public function show(Request $request): JsonResponse
    {
        $id = (int) $request->route('id');
        $tenantId = $this->getTenantIdForGovernance($request);

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
        $results = $this->queryService->lookup($this->getTenantIdForGovernance($request), $q);

        return response()->json(['data' => $results]);
    }

    /**
     * Create a new geographic unit.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code'        => 'required|string|max:50',
            'name'        => 'required|string|max:255',
            'admin_level' => 'required|integer|min:0|max:10',
            'type'        => 'required|string|max:50',
            'parent_id'   => 'nullable|integer|exists:geo_administrative_units,id',
            'is_active'   => 'nullable|boolean',
        ]);

        $organisation = $request->attributes->get('organisation');
        $countryCode = $organisation?->base_country_code;

        try {
            $parentPath = '';
            if ($validated['parent_id']) {
                $parent = GeoAdministrativeUnit::findOrFail($validated['parent_id']);
                $parentPath = $parent->path . $parent->id . '/';
            }

            $unit = GeoAdministrativeUnit::create([
                'organisation_id' => $organisation->id,
                'country_code' => $countryCode,
                'admin_level'  => $validated['admin_level'],
                'admin_type'   => $validated['type'],
                'parent_id'    => $validated['parent_id'] ?? null,
                'code'         => $validated['code'],
                'path'         => $parentPath,
                'name_local'   => ['en' => $validated['name']],
                'is_active'    => $validated['is_active'] ?? true,
            ]);

            return response()->json([
                'data' => [
                    'id'            => $unit->id,
                    'code'          => $unit->code,
                    'name'          => $unit->name_local['en'] ?? $validated['name'],
                    'admin_level'   => $unit->admin_level,
                    'admin_type'    => $unit->admin_type,
                    'parent_id'     => $unit->parent_id,
                    'path'          => $unit->path,
                    'is_active'     => $unit->is_active,
                    'children_count'=> 0,
                ],
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to create geographic unit: ' . $e->getMessage(),
            ], 422);
        }
    }
}
