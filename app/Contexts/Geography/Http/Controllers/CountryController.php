<?php

namespace App\Contexts\Geography\Http\Controllers;

use App\Contexts\Geography\Application\Services\GeographyService;
use App\Contexts\Geography\Domain\Models\Country;
use App\Contexts\Geography\Http\Resources\CountryResource;
use App\Contexts\Geography\Http\Resources\GeographyHierarchyResource;
use App\Contexts\Geography\Http\Resources\AdministrativeUnitResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cache;

/**
 * Country API Controller
 *
 * Handles country-related API endpoints for Geography Context.
 * Provides country information, hierarchies, and administrative units.
 */
class CountryController extends Controller
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
     * List all supported countries.
     *
     * GET /api/geography/countries
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $showAll = $request->boolean('show_all', false);

        $query = Country::query()->active();

        if (!$showAll) {
            $query->where('is_supported', true);
        }

        $countries = $query->orderBy('name_en')->get();

        return CountryResource::collection($countries)
            ->additional([
                'meta' => [
                    'total' => $countries->count(),
                    'showing' => $showAll ? 'all' : 'supported_only',
                ],
            ])
            ->withHeaders($this->getCachingHeaders('countries_index', 3600));
    }

    /**
     * Get specific country with basic info.
     *
     * GET /api/geography/countries/{code}
     *
     * @param string $code
     * @return CountryResource|JsonResponse
     */
    public function show(string $code): CountryResource|JsonResponse
    {
        $country = Country::where('code', strtoupper($code))
            ->where('is_active', true)
            ->first();

        if (!$country) {
            return response()->json([
                'error' => 'Country not found',
                'message' => "Country with code '{$code}' does not exist or is inactive",
            ], 404);
        }

        return (new CountryResource($country))
            ->additional([
                'meta' => [
                    'administrative_levels' => count($country->admin_levels ?? []),
                ],
            ])
            ->withHeaders($this->getCachingHeaders("country_{$code}", 3600));
    }

    /**
     * Get full administrative hierarchy for a country.
     *
     * GET /api/geography/countries/{code}/hierarchy
     *
     * @param string $code
     * @return GeographyHierarchyResource|JsonResponse
     */
    public function hierarchy(string $code): GeographyHierarchyResource|JsonResponse
    {
        $code = strtoupper($code);

        // Verify country exists
        $country = Country::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$country) {
            return response()->json([
                'error' => 'Country not found',
                'message' => "Country with code '{$code}' does not exist or is inactive",
            ], 404);
        }

        try {
            $hierarchy = $this->geographyService->getCountryHierarchy($code);

            return (new GeographyHierarchyResource($hierarchy))
                ->withHeaders($this->getCachingHeaders("country_hierarchy_{$code}", 3600));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch hierarchy',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get units at a specific administrative level for a country.
     *
     * GET /api/geography/countries/{code}/level/{level}
     *
     * @param Request $request
     * @param string $code
     * @param int $level
     * @return AnonymousResourceCollection|JsonResponse
     */
    public function level(Request $request, string $code, int $level): AnonymousResourceCollection|JsonResponse
    {
        $code = strtoupper($code);

        // Verify country exists
        $country = Country::where('code', $code)
            ->where('is_active', true)
            ->first();

        if (!$country) {
            return response()->json([
                'error' => 'Country not found',
                'message' => "Country with code '{$code}' does not exist or is inactive",
            ], 404);
        }

        // Validate level
        if ($level < 1 || $level > 10) {
            return response()->json([
                'error' => 'Invalid level',
                'message' => 'Administrative level must be between 1 and 10',
            ], 400);
        }

        // Check if country has this level
        $levelConfig = $country->getAdminLevelConfig($level);
        if (!$levelConfig) {
            return response()->json([
                'error' => 'Level not found',
                'message' => "Country '{$code}' does not have administrative level {$level}",
            ], 404);
        }

        $parentId = $request->query('parent_id');

        try {
            $units = $this->geographyService->getUnitsAtLevel($code, $level, $parentId);

            return AdministrativeUnitResource::collection($units)
                ->additional([
                    'meta' => [
                        'country_code' => $code,
                        'level' => $level,
                        'level_name' => $levelConfig['name'] ?? null,
                        'level_local_name' => $levelConfig['local_name'] ?? null,
                        'total' => $units->count(),
                        'expected_count' => $levelConfig['count'] ?? null,
                        'parent_id' => $parentId,
                    ],
                ])
                ->withHeaders($this->getCachingHeaders("country_{$code}_level_{$level}_parent_{$parentId}", 3600));
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Failed to fetch units',
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
