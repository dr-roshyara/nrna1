<?php

namespace App\Http\Controllers;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\UserOrganisationRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CandidacyApplicationController extends Controller
{
    public function create(Organisation $organisation): Response
    {
        $user = auth()->user();

        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(! $role, 403);

        $activeElections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->where('status', 'active')
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

        return Inertia::render('Organisations/CandidacyCreate', [
            'organisation'    => $organisation->only('id', 'name', 'slug'),
            'activeElections' => $activeElections->values(),
        ]);
    }

    public function index(Organisation $organisation): Response
    {
        $user = auth()->user();

        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(! $role, 403);

        $applications = CandidacyApplication::where('user_id', $user->id)
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

    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $user = auth()->user();

        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(! $role, 403);

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
            ->where('status', 'active')
            ->firstOrFail();

        Post::withoutGlobalScopes()
            ->where('id', $validated['post_id'])
            ->where('election_id', $election->id)
            ->firstOrFail();

        // One application per election (not just per post)
        $existing = CandidacyApplication::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already submitted an application for this election. Only one application per election is allowed.');
        }

        return DB::transaction(function () use ($user, $organisation, $election, $validated, $request) {
            $photoPath = null;
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store(
                    "candidacy/{$organisation->id}/{$user->id}/photos",
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

            return redirect()
                ->route('organisations.candidacy.list', $organisation->slug)
                ->with('success', 'Your candidacy application has been submitted for review.');
        });
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
