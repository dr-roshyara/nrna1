<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\Organisation;
use App\Services\BallotAssemblyService;
use App\Traits\ChecksElectionAccess;
use Inertia\Inertia;
use Inertia\Response;

/**
 * Read-only, interactive-but-non-submittable preview of the ballot — reachable
 * via a shared (non-personal) link from ReadyForVoting through VotingActive.
 * See app/Services/BallotAssemblyService.php for the shared query/mapping logic
 * this also relies on for the real vote flow.
 *
 * Governing invariant: observational only. No voter-slug, no Code, no session
 * tenant-scoping, no IP check, no agreement gate — org access + lifecycle
 * state (enforced by the 'election.state:preview_ballot' route middleware)
 * is sufficient, matching the existing organisations.elections.candidates
 * precedent (also read-only, multi-phase-accessible, gated the same way).
 */
class BallotPreviewController extends Controller
{
    use ChecksElectionAccess;

    public function index(Organisation $organisation, Election $election): Response
    {
        abort_if($election->type === 'demo', 404);
        abort_unless($this->canAccessElection($organisation, $election->id), 403);

        $ballot = app(BallotAssemblyService::class);
        $user = auth()->user();

        return Inertia::render('Vote/BallotPreview', [
            'national_posts' => $ballot->buildNationalPosts($election),
            'regional_posts' => $ballot->buildRegionalPosts($election, (string) $user->region),
            'user_name' => $user->name,
            'user_id' => $user->id,
            'election' => $ballot->buildElectionProp($election),
        ]);
    }
}
