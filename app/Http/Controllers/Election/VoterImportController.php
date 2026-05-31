<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;
use App\Models\Organisation;
use App\Domain\Election\Enum\VoterSourceStrategy;
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
        $this->assertAdministrationSetupState($election);

        $mode = VoterSourceStrategy::fromElection($election);

        return Inertia::render('Elections/Voters/Import', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $election->only('id', 'slug', 'name'),
            // Phase 3 quarantine: new voter_source_strategy prop with transitional prefix
            'voter_source_strategy' => $mode->toApiValue(),
            // Backward compatibility: old uses_full_membership still available during migration
            'uses_full_membership' => $mode->isMembershipRegistry(),
        ]);
    }

    // ── Tutorial ──────────────────────────────────────────────────────────────

    public function tutorial(Organisation $organisation, string $election): Response
    {
        $election = $this->resolveElection($election);

        $this->authorize('manageVoters', $election);

        $mode = VoterSourceStrategy::fromElection($election);

        return Inertia::render('Elections/Voters/ImportTutorial', [
            'organisation'       => $organisation->only('id', 'name', 'slug'),
            'election'           => $election->only('id', 'slug', 'name'),
            // Phase 3 quarantine: new voter_source_strategy prop with transitional prefix
            'voter_source_strategy' => $mode->toApiValue(),
            // Backward compatibility: old uses_full_membership still available during migration
            'uses_full_membership' => $mode->isMembershipRegistry(),
        ]);
    }

    // ── Public Tutorial (no auth required) ─────────────────────────────────────

    public function publicTutorial(): Response
    {
        // publicTutorial() is context-free: no election exists in this scope.
        // Full-membership is the generic default for a context-free instructional view.
        // Do NOT derive from election snapshot — there is no election here.
        $defaultMode = VoterSourceStrategy::MembershipRegistry;
        return Inertia::render('Elections/Voters/ImportTutorial', [
            'organisation'       => null,
            'election'           => null,
            // Phase 3 quarantine: context-free default uses MembershipRegistry
            'voter_source_strategy' => $defaultMode->toApiValue(),
            // Backward compatibility: old uses_full_membership still available during migration
            'uses_full_membership' => $defaultMode->isMembershipRegistry(),
            'isPublic'           => true,
        ]);
    }

    // ── Template ──────────────────────────────────────────────────────────────

    public function template(Organisation $organisation, string $election)
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);
        $this->assertAdministrationSetupState($election);

        return (new VoterImportService($election, $this->eligibilityService))->downloadTemplate();
    }

    // ── Preview ───────────────────────────────────────────────────────────────

    public function preview(Request $request, Organisation $organisation, string $election)
    {
        $election = $this->resolveElection($election);
        $this->authorize('manageVoters', $election);
        $this->assertAdministrationSetupState($election);

        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $service = new VoterImportService($election, $this->eligibilityService);

        // Route to correct preview method based on election's constitutional snapshot
        if (VoterSourceStrategy::fromElection($election)->isImportedVoterRegistry()) {
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
        $this->assertAdministrationSetupState($election);

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

        // Route to correct import method based on election's constitutional snapshot
        if (VoterSourceStrategy::fromElection($election)->isImportedVoterRegistry()) {
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

    private function assertAdministrationSetupState(Election $election): void
    {
        $currentState = ElectionLifecycle::of($election)->state()->value;

        if ($currentState !== 'setup_administration') {
            \Log::warning('Voter import blocked — wrong election state', [
                'election_id'    => $election->id,
                'current_state'  => $currentState,
                'required_state' => 'setup_administration',
                'user_id'        => auth()->id(),
                'ip'             => request()->ip(),
            ]);

            abort(403, 'Voter import is only available during the administration setup phase.');
        }
    }
}
