<?php

declare(strict_types=1);

namespace App\Http\Controllers\Geography;

use App\Http\Controllers\Controller;
use App\Models\Region;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;

final class RegionController extends Controller
{
    public function index(): JsonResponse
    {
        $regions = Cache::remember('regions:all:active', 3600, function () {
            return Region::where('is_active', true)
                ->select('id', 'code', 'name')
                ->get();
        });

        return response()->json(['data' => $regions]);
    }

    public function countries(string $code): JsonResponse
    {
        $region = Region::where('code', $code)->first();

        if (!$region) {
            return response()->json(['message' => 'Region not found'], 404);
        }

        $cacheKey = "regions:{$code}:countries";
        $countries = Cache::remember($cacheKey, 3600, function () use ($region) {
            return $region->countries()
                ->select('code', 'name_en', 'code_alpha3')
                ->get();
        });

        return response()->json(['data' => $countries]);
    }
}
