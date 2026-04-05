<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Services\VoterImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VoterImportController extends Controller
{
    // ── Import page ───────────────────────────────────────────────────────────

    public function create(Organisation $organisation, string $election): Response
    {
        $election = $this->resolveElection($election);

        $this->authorize('manageVoters', $election);

        return Inertia::render('Elections/Voters/Import', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $election->only('id', 'slug', 'name'),
        ]);
    }

    // ── Template ──────────────────────────────────────────────────────────────

    public function template(Organisation $organisation, string $election): BinaryFileResponse
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);

        return (new VoterImportService($election))->downloadTemplate();
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview(Request $request, Organisation $organisation, string $election)
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $result = (new VoterImportService($election))->preview($request->file('file'));

        return response()->json($result);
    }

    // ── Import ────────────────────────────────────────────────────────────────

    public function import(Request $request, Organisation $organisation, string $election): RedirectResponse
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);

        $request->validate([
            'file'      => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'confirmed' => 'required|accepted',
        ]);

        $result = (new VoterImportService($election))->import($request->file('file'));

        $message = sprintf(
            'Voter import completed: %d registered, %d already existing, %d skipped.',
            $result['created'],
            $result['already_existing'],
            $result['skipped']
        );

        return back()->with('success', $message);
    }

    // ── Internal ──────────────────────────────────────────────────────────────

    private function resolveElection(string $slug): Election
    {
        $election = Election::withoutGlobalScopes()
            ->where('slug', $slug)
            ->firstOrFail();

        abort_if($election->type === 'demo', 404, 'Voter import is not available for demo elections.');

        return $election;
    }
}
