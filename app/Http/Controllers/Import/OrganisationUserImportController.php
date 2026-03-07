<?php

namespace App\Http\Controllers\Import;

use App\Http\Controllers\Controller;
use App\Services\OrganisationUserImportService;
use App\Models\Organisation;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OrganisationUserImportController extends Controller
{
    public function __construct()
    {
        // Routes already have auth, verified, ensure.organisation middleware
    }

    /**
     * Check if user is an owner of the organisation
     */
    protected function requireOwner(Organisation $organisation): void
    {
        $user = auth()->user();
        $isOwner = \App\Models\UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->where('role', 'owner')
            ->exists();

        if (!$isOwner) {
            abort(403, 'Only organisation owners can manage imports');
        }
    }

    /**
     * Show import page
     *
     * GET /organisations/{organisation}/users/import
     */
    public function index(Organisation $organisation)
    {
        $this->requireOwner($organisation);

        return Inertia::render('Organisations/Users/Import', [
            'organisation' => [
                'id' => $organisation->id,
                'name' => $organisation->name,
                'slug' => $organisation->slug,
            ],
            'elections' => $organisation->elections()
                ->where('status', 'active')
                ->get(['id', 'name']),
        ]);
    }

    /**
     * Download import template
     *
     * GET /organisations/{organisation}/users/import/template
     */
    public function template(Organisation $organisation)
    {
        $this->requireOwner($organisation);

        $service = new OrganisationUserImportService($organisation);
        return $service->downloadTemplate();
    }

    /**
     * Preview import (validate without saving)
     *
     * POST /organisations/{organisation}/users/import/preview
     */
    public function preview(Request $request, Organisation $organisation)
    {
        $this->requireOwner($organisation);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
        ]);

        $service = new OrganisationUserImportService($organisation);
        $result = $service->preview($request->file('file'));

        return response()->json([
            'preview' => $result['preview'],
            'stats' => $result['stats'],
        ]);
    }

    /**
     * Process import
     *
     * POST /organisations/{organisation}/users/import/process
     */
    public function process(Request $request, Organisation $organisation)
    {
        $this->requireOwner($organisation);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:10240',
            'confirmed' => 'required|boolean|accepted',
        ]);

        $service = new OrganisationUserImportService($organisation);
        $result = $service->import($request->file('file'));

        $message = sprintf(
            'Import completed: %d created, %d updated, %d skipped',
            $result['created'],
            $result['updated'],
            $result['skipped']
        );

        return redirect()
            ->route('organisations.show', $organisation->slug)
            ->with('success', $message);
    }

    /**
     * Export current users
     *
     * GET /organisations/{organisation}/users/export
     */
    public function export(Organisation $organisation)
    {
        $this->requireOwner($organisation);

        $service = new OrganisationUserImportService($organisation);
        return $service->export();
    }
}
