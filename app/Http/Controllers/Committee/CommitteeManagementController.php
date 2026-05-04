<?php

declare(strict_types=1);

namespace App\Http\Controllers\Committee;

use App\Contexts\Membership\Application\Committee\CreateCommittee;
use App\Contexts\Membership\Application\Committee\DTOs\CreateCommitteeCommand;
use App\Contexts\Membership\Application\Committee\DTOs\UpdateCommitteeDetailsCommand;
use App\Contexts\Membership\Application\Committee\GetCommitteeDashboard;
use App\Contexts\Membership\Application\Committee\UpdateCommitteeDetails;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\GeoReference;
use App\Models\Organisation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
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

        // TODO: Fetch committees from repository grouped by level
        $committees = [
            'central' => [],
            'province' => [],
            'district' => [],
            'ward' => [],
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
            ]);

            $command = new CreateCommitteeCommand(
                name: $validated['name'],
                code: $validated['code'],
                type: CommitteeType::fromString($validated['type']),
                geoReference: $validated['geo_reference'] ? GeoReference::fromString($validated['geo_reference']) : null,
                tenantId: TenantId::fromString($organisation->id),
            );

            $committeeId = app(CreateCommittee::class)->execute($command);

            return redirect()->route('committee.dashboard', [
                'organisation' => $organisation,
                'committeeId' => $committeeId->value()
            ])->with('success', 'Committee created successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function edit(Organisation $organisation, string $committeeId): Response
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $dashboard = app(GetCommitteeDashboard::class)->execute(
                CommitteeId::fromString($committeeId),
                TenantId::fromString($organisation->id)
            );

            return Inertia::render('Committee/Edit', [
                'committee' => $dashboard->toArray()['committee'],
            ]);
        } catch (\Throwable $e) {
            abort(404);
        }
    }

    public function update(Organisation $organisation, string $committeeId): RedirectResponse
    {
        $this->authorize('manageCommittee', $organisation);

        try {
            $validated = request()->validate([
                'name' => 'nullable|string|max:255',
                'status' => 'nullable|string|in:active,inactive',
            ]);

            $command = new UpdateCommitteeDetailsCommand(
                committeeId: CommitteeId::fromString($committeeId),
                tenantId: TenantId::fromString($organisation->id),
                name: $validated['name'] ?? null,
                status: $validated['status'] ?? null,
            );

            app(UpdateCommitteeDetails::class)->execute($command);

            return redirect()->back()->with('success', 'Committee updated successfully.');
        } catch (\Throwable $e) {
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
