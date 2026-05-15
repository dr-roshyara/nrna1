<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Geography\Application\DTOs\CascaderConfigDTO;
use App\Contexts\Membership\Application\Committee\CommitteeSlugAvailabilityService;
use App\Contexts\Membership\Application\Committee\DTOs\UpdateCommitteeDetailsCommand;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Application\Committee\UpdateCommitteeDetails;
use App\Contexts\Membership\Domain\Committee\Exceptions\InvalidCommitteeSlugException;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeSlug;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Http\Requests\Committee\StoreCommitteeRequest;
use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use App\Http\Controllers\Controller;

final class CommitteeManagementController extends Controller
{
    public function __construct()
    {
    }

    public function tutorial(): Response
    {
        return Inertia::render('Committee/Tutorial');
    }

    public function index(Organisation $organisation): Response
    {
        $this->authorize('manageCommittee', $organisation);

        $governanceLevels = \Illuminate\Support\Facades\DB::table('governance_level_definitions')
            ->where('tenant_id', $organisation->id)
            ->where('is_active', true)
            ->orderBy('level')
            ->get();

        $allCommittees = \App\Contexts\Membership\Infrastructure\Models\CommitteeModel::where('organisation_id', $organisation->id)
            ->get();

        $committees = [];
        foreach ($governanceLevels as $level) {
            $committees[$level->level] = $allCommittees->filter(fn($c) => $c->level === $level->level)->values();
        }

        return Inertia::render('Committee/Index', [
            'committees' => $committees,
            'governanceLevels' => $governanceLevels,
            'organisationSlug' => $organisation->slug,
        ]);
    }

    public function create(Organisation $organisation): Response
    {
        $this->authorize('manageCommittee', $organisation);

        $governanceLevels = \Illuminate\Support\Facades\DB::table('governance_level_definitions')
            ->where('tenant_id', $organisation->id)
            ->where('is_active', true)
            ->orderBy('level')
            ->get();

        $geoUnits = \Illuminate\Support\Facades\DB::table('geo_administrative_units')
            ->where('organisation_id', $organisation->id)
            ->where('is_active', true)
            ->orderBy('admin_level', 'asc')
            ->orderBy('name_local->en', 'asc')
            ->get(['id', 'admin_level', 'name_local', 'code']);

        return Inertia::render('Committee/Create', [
            'organisationSlug' => $organisation->slug,
            'governanceLevels' => $governanceLevels,
            'geoUnits' => $geoUnits->map(fn($unit) => [
                'id' => $unit->id,
                'admin_level' => $unit->admin_level,
                'name' => is_array($unit->name_local) ? ($unit->name_local['en'] ?? $unit->code) : $unit->code,
                'code' => $unit->code,
            ]),
        ]);
    }

    public function store(StoreCommitteeRequest $request, Organisation $organisation): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $slug = CommitteeSlug::fromString($request->input('slug') ?: $request->input('name'));
        } catch (InvalidCommitteeSlugException $e) {
            return back()->withErrors(['slug' => $e->getMessage()]);
        }

        try {
            $committeeId = CommitteeId::generate();
            $committeeModel = CommitteeModel::create([
                'id' => $committeeId->value(),
                'organisation_id' => $organisation->id,
                'code' => $request->input('code'),
                'name' => $request->input('name'),
                'type' => $request->input('type'),
                'level' => (int) $request->input('governanceLevel'),
                'operational_geo' => (int) $request->input('geoUnitId'),
                'slug' => $slug->value(),
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return back()->withErrors(['slug' => 'committee.slug.taken']);
        }

        return redirect()->route('committee.dashboard', [
            'organisation' => $organisation,
            'committee' => $committeeModel->slug,
        ])->with('success', 'Committee created successfully.');
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

    public function checkSlugExists(
        Organisation $organisation,
        CommitteeSlugAvailabilityService $service,
        Request $request
    ): JsonResponse {
        $this->authorize('manageCommittee', $organisation);

        $rawSlug = $request->query('value', '');

        try {
            $slug = CommitteeSlug::fromString($rawSlug);
        } catch (InvalidCommitteeSlugException $e) {
            return response()->json([
                'exists' => false,
                'reserved' => false,
                'slug' => $rawSlug,
                'suggestions' => [],
                'error_key' => $e->getMessage(),
            ], 422);
        }

        $result = $service->check($organisation->id, $slug);

        return response()->json($result->toArray());
    }
}
