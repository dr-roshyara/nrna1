<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\UserOrganisationRole;
use App\Services\ParticipantImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ParticipantImportController extends Controller
{
    // ── Import page ───────────────────────────────────────────────────────────

    public function create(Organisation $organisation): Response
    {
        $this->requireAdmin($organisation);

        return Inertia::render('Organisations/Membership/Participants/Import', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
        ]);
    }

    // ── Template ──────────────────────────────────────────────────────────────

    public function template(Organisation $organisation): BinaryFileResponse
    {
        $this->requireAdmin($organisation);

        return (new ParticipantImportService($organisation))->downloadTemplate();
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview(Request $request, Organisation $organisation)
    {
        $this->requireAdmin($organisation);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $result = (new ParticipantImportService($organisation))
            ->preview($request->file('file'));

        return response()->json($result);
    }

    // ── Import ────────────────────────────────────────────────────────────────

    public function import(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->requireAdmin($organisation);

        $request->validate([
            'file'      => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'confirmed' => 'required|accepted',
        ]);

        $result = (new ParticipantImportService($organisation))
            ->import($request->file('file'));

        $message = sprintf(
            'Import completed: %d created, %d updated, %d skipped.',
            $result['created'],
            $result['updated'],
            $result['skipped']
        );

        return back()->with('success', $message);
    }

    // ── Access control ────────────────────────────────────────────────────────

    private function requireAdmin(Organisation $organisation): void
    {
        $role = UserOrganisationRole::where('user_id', auth()->id())
            ->where('organisation_id', $organisation->id)
            ->value('role');

        abort_if(! in_array($role, ['owner', 'admin'], true), 403);
    }
}
