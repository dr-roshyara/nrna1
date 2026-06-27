<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Organisation;
use App\Contexts\Membership\Application\CommitteeStructure\DefineCommitteeStructure;
use App\Contexts\Membership\Application\CommitteeStructure\ActivateCommitteeStructureUseCase;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeStructureRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

final class GovernanceSetupController extends Controller
{
    public function __construct(
        private CommitteeStructureRepositoryInterface $structureRepo
    ) {}

    public function index(Organisation $organisation): Response
    {
        $this->authorize('setupGovernance', $organisation);

        if ($organisation->governance_status === 'active') {
            return Inertia::render('Governance/AlreadyConfigured', [
                'organisation' => $organisation,
            ]);
        }

        $currentStructure = $this->structureRepo->findActiveByTenant($organisation->id);

        return Inertia::render('Governance/Setup', [
            'organisation' => $organisation,
            'currentStructure' => $currentStructure?->toArray(),
            'status' => $organisation->governance_status,
        ]);
    }

    public function store(
        Organisation $organisation,
        Request $request,
        DefineCommitteeStructure $defineUseCase
    ) {
        $this->authorize('setupGovernance', $organisation);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'levels' => 'required|array|min:1|max:10',
            'levels.*.index' => 'required|integer|min:1|max:10',
            'levels.*.code' => 'nullable|string|max:50',
            'levels.*.name' => 'required|string|max:255',
            'levels.*.geoPolicy' => 'required|string|in:none,required,optional',
            'levels.*.geoScope' => 'nullable|string|max:50',
        ]);

        // Define structure as DRAFT
        $structure = $defineUseCase->execute([
            'tenantId' => $organisation->id,
            'name' => $validated['name'],
            'levels' => $validated['levels'],
        ]);

        return redirect()->route('governance.review', $organisation)
            ->with('success', 'Governance structure saved. Review and activate.');
    }

    public function review(Organisation $organisation): Response
    {
        $this->authorize('setupGovernance', $organisation);

        $currentStructure = $this->structureRepo->findActiveByTenant($organisation->id);

        return Inertia::render('Governance/Review', [
            'organisation' => $organisation,
            'currentStructure' => $currentStructure?->toArray(),
            'status' => $organisation->governance_status,
        ]);
    }

    public function activate(
        Organisation $organisation,
        ActivateCommitteeStructureUseCase $activateUseCase
    ) {
        $this->authorize('setupGovernance', $organisation);

        // Get the active structure to activate it
        $structure = $this->structureRepo->findActiveByTenant($organisation->id);

        if ($structure === null) {
            return back()->withErrors(['error' => 'No structure to activate. Define a structure first.']);
        }

        // Activate structure
        $activateUseCase->execute([
            'tenantId' => $organisation->id,
            'structureId' => $structure->getId()->value(),
        ]);

        // Mark organisation as governance_configured
        $organisation->update([
            'governance_status' => 'governance_configured',
            'governance_configured_at' => now(),
            'governance_configured_by' => auth()->id(),
        ]);

        return redirect()->route('organisations.show', $organisation)
            ->with('success', 'Governance configured. You can now activate the organisation.');
    }
}
