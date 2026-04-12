<?php

namespace App\Http\Controllers\Contribution;

use App\Http\Controllers\Controller;
use App\Models\Contribution;
use App\Models\Organisation;
use App\Services\ContributionPointsService;
use App\Services\LeaderboardService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ContributionController extends Controller
{
    public function __construct(
        private ContributionPointsService $pointsService,
        private LeaderboardService $leaderboardService,
    ) {}

    // ── My contributions ──────────────────────────────────────────────────────

    public function index(Organisation $organisation): Response
    {
        $contributions = Contribution::where('organisation_id', $organisation->id)
            ->where('user_id', auth()->id())
            ->orderByDesc('created_at')
            ->paginate(20);

        $weeklyPoints = $this->pointsService->getWeeklyPoints(
            auth()->id(),
            $organisation->id,
        );

        return Inertia::render('Contributions/Index', [
            'organisation'  => $organisation,
            'contributions' => $contributions,
            'weeklyPoints'  => $weeklyPoints,
            'weeklyCap'     => 100,
        ]);
    }

    // ── Create form ───────────────────────────────────────────────────────────

    public function create(Organisation $organisation): Response
    {
        $weeklyPoints = $this->pointsService->getWeeklyPoints(
            auth()->id(),
            $organisation->id,
        );

        return Inertia::render('Contributions/Create', [
            'organisation' => $organisation,
            'weeklyPoints' => $weeklyPoints,
            'weeklyCap'    => 100,
        ]);
    }

    // ── Store ─────────────────────────────────────────────────────────────────

    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'description'   => ['required', 'string', 'max:2000'],
            'track'         => ['required', 'in:micro,standard,major'],
            'effort_units'  => ['required', 'integer', 'min:1', 'max:100'],
            'proof_type'    => ['required', 'in:self_report,photo,document,third_party,community_attestation,institutional'],
            'team_skills'   => ['nullable', 'array'],
            'team_skills.*' => ['string', 'max:100'],
            'is_recurring'  => ['boolean'],
            'outcome_bonus' => ['integer', 'min:0', 'max:200'],
        ]);

        $contribution = Contribution::create([
            'organisation_id' => $organisation->id,
            'user_id'         => auth()->id(),
            'created_by'      => auth()->id(),
            'status'          => 'pending',
            'team_skills'     => $validated['team_skills'] ?? [],
            'is_recurring'    => $validated['is_recurring'] ?? false,
            'outcome_bonus'   => $validated['outcome_bonus'] ?? 0,
            ...$validated,
        ]);

        return redirect()
            ->route('organisations.contributions.show', [$organisation->slug, $contribution->id])
            ->with('success', 'Contribution submitted for review.');
    }

    // ── Show ──────────────────────────────────────────────────────────────────

    public function show(Organisation $organisation, Contribution $contribution): Response
    {
        if ($contribution->organisation_id !== $organisation->id) {
            abort(404);
        }

        if ($contribution->user_id !== auth()->id()) {
            abort(403);
        }

        return Inertia::render('Contributions/Show', [
            'organisation' => $organisation,
            'contribution' => $contribution->load('ledgerEntries'),
        ]);
    }

    // ── Leaderboard ───────────────────────────────────────────────────────────

    public function leaderboard(Organisation $organisation): Response
    {
        return Inertia::render('Contributions/Leaderboard', [
            'organisation' => $organisation,
            'board'        => $this->leaderboardService->get($organisation->id),
        ]);
    }
}
