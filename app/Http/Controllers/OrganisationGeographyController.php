<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Contexts\Geography\Application\DTOs\CascaderConfigDTO;
use App\Models\Organisation;
use Illuminate\Http\JsonResponse;

final class OrganisationGeographyController extends Controller
{
    public function cascaderConfig(Organisation $organisation): JsonResponse
    {
        $this->authorize('view', $organisation);

        $config = CascaderConfigDTO::fromOrganisation($organisation);

        $data = $config->toArray();
        \Log::debug('CascaderConfig response', [
            'scope' => $data['scope'],
            'allowedCountriesCount' => count($data['allowedCountryCodes']),
            'allowedCountries' => $data['allowedCountryCodes'],
            'initialCountry' => $data['initialCountryCode'],
        ]);

        return response()->json([
            'data' => $data,
        ])->header('Cache-Control', 'public, max-age=3600');
    }
}
