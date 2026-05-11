<?php

namespace App\Http\Controllers\Geography;

use App\Contexts\Geography\Domain\Models\Country;
use App\Contexts\Geography\Domain\Models\GeoAdministrativeUnit;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

final class OrganisationGeographyController extends Controller
{
    public function countries(): JsonResponse
    {
        $countries = Country::active()
            ->orderBy('name_en')
            ->get(['code', 'name_en']);

        return response()->json([
            'data' => $countries->map(fn($c) => [
                'id'   => $c->code,
                'code' => $c->code,
                'name' => $c->name_en,
                'flag' => $this->flagEmoji($c->code),
            ]),
        ]);
    }

    public function continents(): JsonResponse
    {
        $continents = GeoAdministrativeUnit::where('admin_level', 0)
            ->active()
            ->orderBy('code')
            ->get(['id', 'code', 'name_local']);

        return response()->json([
            'data' => $continents->map(fn($c) => [
                'id'   => $c->id,
                'code' => $c->code,
                'name' => $c->getName('en'),
            ]),
        ]);
    }

    public function regionsByCountry(string $countryCode): JsonResponse
    {
        // admin_level 1 = province/state (top administrative division)
        $regions = GeoAdministrativeUnit::byCountry(strtoupper($countryCode))
            ->byLevel(1)
            ->active()
            ->orderBy('code')
            ->get(['id', 'name_local', 'code']);

        return response()->json([
            'data' => $regions->map(fn($r) => [
                'id'   => $r->id,
                'code' => $r->code,
                'name' => $r->getName('en'),
            ]),
        ]);
    }

    private function flagEmoji(string $code): string
    {
        // Unicode regional indicator symbols — works for all ISO 3166-1 alpha-2 codes
        return implode('', array_map(
            fn($char) => mb_chr(0x1F1E6 + ord($char) - ord('A')),
            str_split(strtoupper($code))
        ));
    }
}
