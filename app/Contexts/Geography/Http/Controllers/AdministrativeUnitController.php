<?php

namespace App\Contexts\Geography\Http\Controllers;

use App\Contexts\Geography\Application\Services\GeographyService;
use App\Contexts\Geography\Http\Resources\AdministrativeUnitResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Administrative Unit API Controller
 *
 * Handles administrative unit endpoints for Geography Context.
 * Provides unit details, children, and code-based lookups.
 */
class AdministrativeUnitController extends Controller
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
     * Get specific unit with ancestors.
     *
     * GET /api/geography/units/{id}
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function show(Request $request, int $id): JsonResponse
    {
        try {
            $language = $request->header('Accept-Language', 'en');
            $unitData = $this->geographyService->getUnitWithAncestors($id, $language);

            if (empty($unitData)) {
                return response()->json([
                    'error' => 'Unit not found',
                    'message' => "Administrative unit with ID {$id} does not exist",
                ], 404);
            }

            return response()->json([
                'data' => [
                    'type' => 'administrative_unit_with_ancestors',
                    'id' => $id,
                    'attributes' => [
                        'unit' => $unitData['unit'],
                        'ancestors' => $unitData['ancestors'],
                        'full_path' => $unitData['full_path'],
                    ],
                ],
                'meta' => [
                    'language' => $language,
                    'ancestor_count' => count($unitData['ancestors']),
                ],
                'jsonapi' => [
                    'version' => '1.0',
                ],
            ])
            ->withHeaders($this->getCachingHeaders("unit_{$id}_ancestors", 3600));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch unit',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get child units of a parent.
     *
     * GET /api/geography/units/{id}/children
     *
     * @param int $id
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function children(int $id): AnonymousResourceCollection|JsonResponse
    {
        try {
            $children = $this->geographyService->getChildUnits($id);

            if ($children->isEmpty()) {
                // Check if parent exists
                $parent = $this->geographyService->getUnitById($id);
                if (!$parent) {
                    return response()->json([
                        'error' => 'Parent unit not found',
                        'message' => "Administrative unit with ID {$id} does not exist",
                    ], 404);
                }
            }

            return AdministrativeUnitResource::collection($children)
                ->additional([
                    'meta' => [
                        'parent_id' => $id,
                        'total' => $children->count(),
                    ],
                ])
                ->withHeaders($this->getCachingHeaders("unit_{$id}_children", 3600));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch children',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get unit by code.
     *
     * GET /api/geography/units/code/{code}
     *
     * @param Request $request
     * @param string $code
     * @return AdministrativeUnitResource|JsonResponse
     */
    public function showByCode(Request $request, string $code): AdministrativeUnitResource|JsonResponse
    {
        try {
            $unit = $this->geographyService->getUnitByCode($code);

            if (!$unit) {
                return response()->json([
                    'error' => 'Unit not found',
                    'message' => "Administrative unit with code '{$code}' does not exist",
                ], 404);
            }

            return (new AdministrativeUnitResource($unit))
                ->additional([
                    'meta' => [
                        'query_type' => 'by_code',
                        'code' => $code,
                    ],
                ])
                ->withHeaders($this->getCachingHeaders("unit_code_{$code}", 3600));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch unit',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get HTTP caching headers.
     *
     * @param string $key
     * @param int $ttl
     * @return array
     */
    protected function getCachingHeaders(string $key, int $ttl = 3600): array
    {
        $etag = md5($key . Cache::get($key, ''));
        $lastModified = gmdate('D, d M Y H:i:s', time()) . ' GMT';

        return [
            'Cache-Control' => "public, max-age={$ttl}",
            'ETag' => "\"{$etag}\"",
            'Last-Modified' => $lastModified,
        ];
    }
}
