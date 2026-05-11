<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Geography\Application\DTOs\CascaderConfigDTO;
use App\Contexts\Geography\Application\Services\GeoReferenceBuilder;
use App\Contexts\Membership\Application\Committee\CreateCommitteeUseCase;
use App\Contexts\Membership\Application\Committee\DTOs\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\DTOs\UpdateCommitteeDetailsCommand;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Application\Committee\UpdateCommitteeDetails;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

final class CommitteeManagementController extends Controller
{
    public function tutorial(): Response
    {
        return Inertia::render('Committee/Tutorial');
    }

    public function index(Organisation $organisation): Response
    {
        $this->authorize('manageCommittee', $organisation);

        $allCommittees = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::where('organisation_id', $organisation->id)
            ->get();

        $committees = [
            'central' => $allCommittees->filter(fn($c) => $c->type === 'central')->values(),
            'province' => $allCommittees->filter(fn($c) => $c->type === 'province')->values(),
            'district' => $allCommittees->filter(fn($c) => $c->type === 'district')->values(),
            'ward' => $allCommittees->filter(fn($c) => $c->type === 'ward')->values(),
        ];

        return Inertia::render('Committee/Index', [
            'committees' => $committees,
            'organisationSlug' => $organisation->slug,
        ]);
    }

    public function create(Organisation $organisation): Response
    {
        $this->authorize('manageCommittee', $organisation);

        return Inertia::render('Committee/Create', [
            'committeeTypes' => [
                ['value' => 'central', 'label' => 'Central Committee'],
                ['value' => 'province', 'label' => 'Province Committee'],
                ['value' => 'district', 'label' => 'District Committee'],
                ['value' => 'ward', 'label' => 'Ward Committee'],
            ],
            'organisationSlug' => $organisation->slug,
        ]);
    }

    public function store(Organisation $organisation): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $validated = request()->validate([
                'name' => 'required|string|max:255',
                'code' => 'required|string|max:100',
                'type' => 'required|string|in:central,province,district,ward',
                'geo_reference' => 'nullable|string|max:255',
                'geo_selections' => 'nullable|array',
                'geo_selections.region' => 'nullable|string|max:50',
                'geo_selections.country' => 'nullable|string|max:2',
                'geo_selections.geo' => 'nullable|array',
                'geo_selections.geo.*' => 'integer|min:1',
            ]);

            $canonicalGeoRef = null;
            $membershipGeoRef = null;
            $regionCode = null;
            $countryCode = null;

            if ($validated['geo_selections'] ?? false) {
                $builder = app(GeoReferenceBuilder::class);
                $structure = $organisation->getGeographicStructure();
                $canonicalGeoRef = $builder->build($validated['geo_selections'], $structure);

                $regionCode = $canonicalGeoRef->region;
                $countryCode = $canonicalGeoRef->getCountryCode();

                if ($countryCode !== null && !$canonicalGeoRef->geoPath->isEmpty()) {
                    // Only create membershipGeoRef if there's a geo path beyond country
                    $legacyFormat = strtolower($countryCode) . '.' . $canonicalGeoRef->geoPath->toString();
                    $membershipGeoRef = GeoReference::fromString($legacyFormat);
                }
            } elseif ($validated['geo_reference'] ?? false) {
                $membershipGeoRef = GeoReference::fromString($validated['geo_reference']);
            }

            $command = new CreateCommitteeCommand(
                name: $validated['name'],
                code: $validated['code'],
                type: CommitteeType::fromString($validated['type']),
                geoReference: $membershipGeoRef,
                tenantId: TenantId::fromString($organisation->id),
                regionCode: $regionCode,
                countryCode: $countryCode,
            );

            try {
                $committeeId = app(CreateCommitteeUseCase::class)->execute($command);
            } catch (\Throwable $e) {
                \Log::error('CreateCommittee failed', ['error' => $e->getMessage()]);
                throw $e;
            }

            // Fetch committee using withoutGlobalScopes to avoid tenant filtering issues
            $committee = CommitteeModel::withoutGlobalScopes()
                ->where('id', $committeeId->value())
                ->where('organisation_id', $organisation->id)
                ->firstOrFail();

            return redirect()->route('committee.dashboard', [
                'organisation' => $organisation,
                'committee' => $committee->slug
            ])->with('success', 'Committee created successfully.');
        } catch (\Throwable $e) {
            \Log::error('Committee creation error', [
                'message' => $e->getMessage(),
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Organisation $organisation, CommitteeModel $committee): Response
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $dashboard = app(GetCommitteeDashboard::class)->execute(
                CommitteeId::fromString($committee->id),
                TenantId::fromString($organisation->id)
            );

            return Inertia::render('Committee/Edit', [
                'committee' => $dashboard->toArray()['committee'],
                'organisationSlug' => $organisation->slug,
            ]);
        } catch (\Throwable $e) {
            abort(404);
        }
    }

    public function update(Organisation $organisation, CommitteeModel $committee): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $validated = request()->validate([
                'name' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
                'geo_reference' => 'nullable|string|max:255',
                'geo_selections' => 'nullable|array',
                'geo_selections.region' => 'nullable|string|max:50',
                'geo_selections.country' => 'nullable|string|max:2',
                'geo_selections.geo' => 'nullable|array',
                'geo_selections.geo.*' => 'integer|min:1',
            ]);

            $regionCode = null;
            $countryCode = null;

            if ($validated['geo_selections'] ?? false) {
                $builder = app(GeoReferenceBuilder::class);
                $structure = $organisation->getGeographicStructure();
                $canonicalGeoRef = $builder->build($validated['geo_selections'], $structure);

                $regionCode = $canonicalGeoRef->region;
                $countryCode = $canonicalGeoRef->getCountryCode();

                if ($canonicalGeoRef->getCountryCode() !== null) {
                    $legacyFormat = strtolower($canonicalGeoRef->getCountryCode());
                    if (!$canonicalGeoRef->geoPath->isEmpty()) {
                        $legacyFormat .= '.' . $canonicalGeoRef->geoPath->toString();
                    }
                    $membershipGeoRef = GeoReference::fromString($legacyFormat);
                }
            }

            $command = new UpdateCommitteeDetailsCommand(
                committeeId: CommitteeId::fromString($committee->id),
                tenantId: TenantId::fromString($organisation->id),
                name: $validated['name'] ?? null,
                status: $validated['status'] ?? null,
                regionCode: $regionCode,
                countryCode: $countryCode,
            );

            app(UpdateCommitteeDetails::class)->execute($command);

            return redirect()->back()->with('success', 'Committee updated successfully.');
        } catch (\Throwable $e) {
            \Log::error('Committee store error: ' . $e->getMessage(), ['exception' => $e, 'trace' => $e->getTraceAsString()]);
            return redirect()->back()->withErrors(['error' => 'Error creating committee: ' . $e->getMessage()]);
        }
    }

    public function cascaderConfig(Organisation $organisation): JsonResponse
    {
        $this->authorize('manageCommittee', $organisation);

        $config = CascaderConfigDTO::fromOrganisation($organisation);
        return response()->json($config->toArray());
    }

    public function checkCodeExists(Organisation $organisation, string $code): JsonResponse
    {
        $this->authorize('manageCommittee', $organisation);

        $exists = CommitteeModel::where('organisation_id', $organisation->id)
            ->where('code', strtoupper($code))
            ->exists();

        return response()->json([
            'exists' => $exists,
            'code' => strtoupper($code),
        ]);
    }
}
