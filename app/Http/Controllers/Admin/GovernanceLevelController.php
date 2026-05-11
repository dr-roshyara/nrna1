<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GovernanceLevelDefinition;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GovernanceLevelController extends Controller
{
    private function getTenantId(Request $request): string
    {
        $organisation = $request->attributes->get('organisation');
        return $organisation ? $organisation->id : $request->user()->current_organisation_id;
    }

    public function index(Request $request): JsonResponse
    {
        $tenantId = $this->getTenantId($request);

        $levels = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->orderBy('level')
            ->orderBy('sort_order')
            ->get();

        return response()->json($levels);
    }

    public function store(Request $request): JsonResponse
    {
        $tenantId = $this->getTenantId($request);

        $validated = $request->validate([
            'level' => [
                'required',
                'integer',
                'min:0',
                'max:10',
                Rule::unique('governance_level_definitions')
                    ->where(fn($q) => $q->where('tenant_id', $tenantId)),
            ],
            'committee_name' => 'required|string|max:100',
            'committee_code' => 'required|string|max:20',
            'geo_name' => 'required|string|max:100',
            'geo_code' => 'required|string|max:20',
            'geo_parent_code' => [
                'nullable',
                'string',
                'max:20',
                Rule::exists('governance_level_definitions', 'geo_code')
                    ->where(fn($q) => $q->where('tenant_id', $tenantId)),
            ],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['tenant_id'] = $tenantId;
        $validated['created_by'] = (string) $request->user()->id;

        $level = GovernanceLevelDefinition::create($validated);

        return response()->json($level, 201);
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $tenantId = $this->getTenantId($request);

        $level = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        return response()->json($level);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $tenantId = $this->getTenantId($request);

        $level = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $validated = $request->validate([
            'committee_name' => 'required|string|max:100',
            'committee_code' => 'required|string|max:20',
            'geo_name' => 'required|string|max:100',
            'geo_code' => 'required|string|max:20',
            'geo_parent_code' => [
                'nullable',
                'string',
                'max:20',
                Rule::exists('governance_level_definitions', 'geo_code')
                    ->where(fn($q) => $q->where('tenant_id', $tenantId))
                    ->whereNot('id', $id),
            ],
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
        ]);

        $validated['updated_by'] = (string) $request->user()->id;

        $level->update($validated);

        return response()->json($level->fresh());
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        $tenantId = $this->getTenantId($request);

        $level = GovernanceLevelDefinition::where('tenant_id', $tenantId)
            ->where('id', $id)
            ->firstOrFail();

        $level->delete();

        return response()->json(null, 204);
    }
}
