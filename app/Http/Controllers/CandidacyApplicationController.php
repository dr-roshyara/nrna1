<?php

namespace App\Http\Controllers;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\UserOrganisationRole;
use App\Traits\ChecksElectionAccess;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CandidacyApplicationController extends Controller
{
    use ChecksElectionAccess;

    private function ensureOrganisationMember(Organisation $organisation): void
    {
        abort_if(
            !UserOrganisationRole::where('user_id', auth()->id())
                ->where('organisation_id', $organisation->id)
                ->exists(),
            403
        );
    }

    public function create(Organisation $organisation): Response
    {
        $this->ensureOrganisationMember($organisation);
        $user = auth()->user();

        $activeElections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->where('state', 'nomination')
            ->with(['posts' => fn ($q) => $q->withoutGlobalScopes()->orderBy('position_order')])
            ->get()
            ->map(fn ($e) => [
                'id'    => $e->id,
                'name'  => $e->name,
                'slug'  => $e->slug,
                'posts' => $e->posts->map(fn ($p) => [
                    'id'               => $p->id,
                    'name'             => $p->name,
                    'is_national_wide' => (bool) $p->is_national_wide,
                    'state_name'       => $p->state_name,
                    'required_number'  => $p->required_number,
                ])->values(),
            ]);

        $appliedElectionIds = CandidacyApplication::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->whereIn('status', ['pending', 'approved'])
            ->pluck('election_id')
            ->all();

        return Inertia::render('Organisations/CandidacyCreate', [
            'organisation'       => $organisation->only('id', 'name', 'slug'),
            'activeElections'    => $activeElections->values(),
            'appliedElectionIds' => $appliedElectionIds,
        ]);
    }

    public function index(Organisation $organisation): Response
    {
        $this->ensureOrganisationMember($organisation);

        $applications = CandidacyApplication::where('user_id', auth()->id())
            ->where('organisation_id', $organisation->id)
            ->with(['election:id,name', 'post:id,name'])
            ->orderByDesc('created_at')
            ->get()
            ->map(fn ($a) => [
                'id'            => $a->id,
                'election_name' => $a->election?->name,
                'post_name'     => $a->post?->name,
                'status'        => $a->status,
                'status_label'  => $this->getStatusLabel($a->status),
                'created_at'    => $a->created_at->format('Y-m-d'),
                'manifesto'     => $a->manifesto,
                'photo'         => $a->photo,
            ]);

        return Inertia::render('Organisations/CandidacyList', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'applications' => $applications->values(),
        ]);
    }

    public function applyForm(Organisation $organisation, Election $election): Response
    {
        abort_if($election->type === 'demo', 404, 'Candidacy applications are not available for demo elections.');

        abort_unless(
            $election->state === 'nomination',
            403,
            'Candidacy applications are only available during the nomination phase.'
        );

        $this->ensureOrganisationMember($organisation);
        $user = auth()->user();

        abort_unless(
            $this->canAccessElection($organisation, $election->id, $user->id),
            403,
            'You are not authorised to access this election.'
        );

        $existingApplication = CandidacyApplication::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->whereIn('status', ['pending', 'approved', 'rejected'])
            ->latest()
            ->first();

        $posts = Post::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('organisation_id', $organisation->id)
            ->orderBy('position_order')
            ->get()
            ->map(fn ($p) => [
                'id'               => $p->id,
                'name'             => $p->name,
                'nepali_name'      => $p->nepali_name,
                'is_national_wide' => (bool) $p->is_national_wide,
                'state_name'       => $p->state_name,
                'required_number'  => $p->required_number,
                'position_order'   => $p->position_order,
            ]);

        return Inertia::render('Election/Candidacy/Apply', [
            'organisation'        => $organisation->only('id', 'name', 'slug'),
            'election'            => $election->only('id', 'name', 'slug', 'state', 'start_date', 'end_date'),
            'posts'               => $posts->values(),
            'existingApplication' => $existingApplication ? [
                'id'           => $existingApplication->id,
                'post_id'      => $existingApplication->post_id,
                'post_name'    => $existingApplication->post?->name,
                'status'       => $existingApplication->status,
                'submitted_at' => $existingApplication->created_at->format('Y-m-d'),
                'photo'        => $existingApplication->photo,
            ] : null,
        ]);
    }

    public function store(Request $request, Organisation $organisation): Response
    {
        $user = auth()->user();
        $this->ensureOrganisationMember($organisation);

        $validated = $request->validate([
            'election_id'    => 'required|uuid|exists:elections,id',
            'post_id'        => 'required|uuid|exists:posts,id',
            'supporter_name' => 'required|string|max:255',
            'proposer_name'  => 'required|string|max:255',
            'manifesto'      => 'nullable|string|max:5000',
            'photo'          => 'nullable|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $election = Election::withoutGlobalScopes()
            ->where('id', $validated['election_id'])
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        abort_if($election->type === 'demo', 404);
        abort_unless($election->state === 'nomination', 403);

        abort_unless(
            $this->canAccessElection($organisation, $validated['election_id'], $user->id),
            403
        );

        try {
            DB::transaction(function () use ($user, $organisation, $election, $validated, $request) {
                Post::withoutGlobalScopes()
                    ->where('id', $validated['post_id'])
                    ->where('election_id', $election->id)
                    ->firstOrFail();

                $existing = CandidacyApplication::where('user_id', $user->id)
                    ->where('election_id', $election->id)
                    ->whereIn('status', ['pending', 'approved'])
                    ->lockForUpdate()
                    ->exists();

                if ($existing) {
                    throw new \RuntimeException(
                        'You have already submitted an application for this election.'
                    );
                }

                $photoPath = null;
                if ($request->hasFile('photo')) {
                    $photoPath = $request->file('photo')->store(
                        "candidacy/{$organisation->id}/photos",
                        'public'
                    );
                }

                CandidacyApplication::create([
                    'user_id'         => $user->id,
                    'organisation_id' => $organisation->id,
                    'election_id'     => $election->id,
                    'post_id'         => $validated['post_id'],
                    'supporter_name'  => $validated['supporter_name'],
                    'proposer_name'   => $validated['proposer_name'],
                    'manifesto'       => $validated['manifesto'] ?? null,
                    'photo'           => $photoPath,
                    'status'          => CandidacyApplication::STATUS_PENDING,
                ]);
            });

            return Inertia::render('Thankyou/Thankyou', [
                'message' => 'Your candidacy application has been submitted successfully and is now under review.',
                'subMessage' => 'You will be notified once a decision has been made.',
            ]);

        } catch (\RuntimeException $e) {
            return back()->withErrors(['form' => $e->getMessage()]);
        }
    }

    private function getStatusLabel(string $status): string
    {
        return match ($status) {
            'pending'  => 'Under Review',
            'approved' => 'Approved',
            'rejected' => 'Not Approved',
            default    => $status,
        };
    }
}
