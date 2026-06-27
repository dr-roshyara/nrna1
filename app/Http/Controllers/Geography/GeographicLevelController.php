<?php

declare(strict_types=1);

namespace App\Http\Controllers\Geography;

use App\Contexts\Geography\Domain\ValueObjects\GeographicStructure;
use App\Contexts\Geography\Infrastructure\Models\GeoAdministrativeUnit;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class GeographicLevelController extends Controller
{
    public function defaults(string $countryCode): JsonResponse
    {
        $structure = match (strtoupper($countryCode)) {
            'NP' => GeographicStructure::forNepal(),
            default => null,
        };

        if ($structure === null) {
            return response()->json(['error' => "No default levels for country '{$countryCode}'"], 404);
        }

        return response()->json(['levels' => $structure->getLevelsArray()]);
    }

    public function available(string $countryCode): JsonResponse
    {
        $maxLevel = \DB::table('geo_administrative_units')
            ->where('country_code', strtoupper($countryCode))
            ->max('admin_level');

        return response()->json([
            'max_db_level' => $maxLevel,
            'available_db_levels' => $maxLevel ? range(1, (int) $maxLevel) : [],
        ]);
    }
}
