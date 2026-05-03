<?php

namespace App\Contexts\Geography\Http\Controllers;

use App\Contexts\Geography\Application\Services\GeographyService;
use App\Contexts\Geography\Http\Requests\GeographyHierarchyRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;

/**
 * Geography API Controller
 *
 * Handles general geography operations including hierarchy validation.
 */
class GeographyController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param GeographyService $geographyService
     */
    public function __construct(
        protected GeographyService $geographyService
    ) {
    }

    /**
     * Validate geography hierarchy.
     *
     * POST /api/geography/validate/hierarchy
     *
     * Request body:
     * {
     *   "country_code": "NP",
     *   "unit_ids": [1, 23, 456, 7890]
     * }
     *
     * @param GeographyHierarchyRequest $request
     * @return JsonResponse
     */
    public function validateHierarchy(GeographyHierarchyRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $countryCode = $validated['country_code'];
        $unitIds = $validated['unit_ids'];

        try {
            // Simple validation
            $isValid = $this->geographyService->validateGeographyHierarchy($countryCode, $unitIds);

            // Detailed validation
            $detailedValidation = $this->geographyService->validateHierarchyDetailed(
                $countryCode,
                $unitIds[0] ?? null,
                $unitIds[1] ?? null,
                $unitIds[2] ?? null,
                $unitIds[3] ?? null
            );

            return response()->json([
                'data' => [
                    'type' => 'hierarchy_validation',
                    'attributes' => [
                        'is_valid' => $isValid,
                        'country_code' => $countryCode,
                        'unit_ids' => $unitIds,
                        'detailed_validation' => $detailedValidation,
                    ],
                ],
                'meta' => [
                    'validation_timestamp' => now()->toIso8601String(),
                    'units_validated' => count($unitIds),
                ],
                'jsonapi' => [
                    'version' => '1.0',
                ],
            ], $isValid ? 200 : 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Validation failed',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
