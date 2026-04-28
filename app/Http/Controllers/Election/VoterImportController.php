<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\Organisation;
use App\Services\VoterEligibilityService;
use App\Services\VoterImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class VoterImportController extends Controller
{
    public function __construct(private readonly VoterEligibilityService $eligibilityService) {}
    // ── Import page ───────────────────────────────────────────────────────────

    public function create(Organisation $organisation, string $election): Response
    {
        $election = $this->resolveElection($election);

        $this->authorize('manageVoters', $election);

        return Inertia::render('Elections/Voters/Import', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $election->only('id', 'slug', 'name'),
            'uses_full_membership' => $organisation->uses_full_membership ?? true,
        ]);
    }

    // ── Tutorial ──────────────────────────────────────────────────────────────

    public function tutorial(Organisation $organisation, string $election): Response
    {
        $election = $this->resolveElection($election);

        $this->authorize('manageVoters', $election);

        return Inertia::render('Elections/Voters/ImportTutorial', [
            'organisation'       => $organisation->only('id', 'name', 'slug'),
            'election'           => $election->only('id', 'slug', 'name'),
            'uses_full_membership' => $organisation->uses_full_membership ?? true,
        ]);
    }

    // ── Public Tutorial (no auth required) ─────────────────────────────────────

    public function publicTutorial(): Response
    {
        return Inertia::render('Elections/Voters/ImportTutorial', [
            'organisation'       => null,
            'election'           => null,
            'uses_full_membership' => true,
            'isPublic'           => true,
        ]);
    }

    // ── Template ──────────────────────────────────────────────────────────────

    public function template(Organisation $organisation, string $election)
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);

        return (new VoterImportService($election, $this->eligibilityService))->downloadTemplate();
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview(Request $request, Organisation $organisation, string $election)
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $service = new VoterImportService($election, $this->eligibilityService);

        // Route to correct preview method based on membership mode
        if (!$organisation->uses_full_membership) {
            $result = $service->previewElectionOnly($request->file('file'));
        } else {
            $result = $service->preview($request->file('file'));
        }

        return response()->json($result);
    }

    // ── Import ────────────────────────────────────────────────────────────────

    public function import(Request $request, Organisation $organisation, string $election)
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);

        $request->validate([
            'file'      => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
            'confirmed' => 'required|accepted',
        ]);

        // Early gate check — file not yet parsed, so we check minimum capacity
        // Full enforcement (with actual voter count) happens in VoterImportService after parsing
        try {
            $election->assertCanAcceptVoters(1);
        } catch (\DomainException $e) {
            return back()->with('error', $e->getMessage());
        }

        $service = new VoterImportService($election, $this->eligibilityService);

        // Route to correct import method based on membership mode
        if (!$organisation->uses_full_membership) {
            $result = $service->importElectionOnly($request->file('file'));
            $message = sprintf(
                'Voter import completed: %d users created, %d already existing, %d invitation emails queued.',
                $result['created'],
                $result['existing'],
                $result['invitations']
            );
        } else {
            $result = $service->import($request->file('file'));
            $message = sprintf(
                'Voter import completed: %d registered, %d already existing, %d skipped.',
                $result['created'],
                $result['already_existing'],
                $result['skipped']
            );
        }

        // Return JSON for API requests, redirect for browser requests
        if ($request->wantsJson()) {
            return response()->json(['success' => $message, 'result' => $result]);
        }

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
