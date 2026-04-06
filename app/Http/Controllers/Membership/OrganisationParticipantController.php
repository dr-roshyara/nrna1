<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Organisation;
use App\Models\OrganisationParticipant;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OrganisationParticipantController extends Controller
{
    // ── index ─────────────────────────────────────────────────────────────────

    public function index(Request $request, Organisation $organisation): Response
    {
        $this->authorizeAdmin($request->user(), $organisation);

        $request->validate([
            'type'      => 'nullable|in:staff,guest,election_committee',
            'direction' => 'nullable|in:asc,desc',
        ]);

        $query = OrganisationParticipant::where('organisation_id', $organisation->id)
            ->with('user')
            ->orderBy('assigned_at', $request->input('direction', 'desc'));

        if ($request->filled('type')) {
            $query->where('participant_type', $request->type);
        }

        $participants = $query->paginate(20)->through(fn ($p) => [
            'id'               => $p->id,
            'name'             => $p->user?->name ?? '—',
            'email'            => $p->user?->email ?? '—',
            'participant_type' => $p->participant_type,
            'role'             => $p->role,
            'assigned_at'      => $p->assigned_at?->toIso8601String(),
            'expires_at'       => $p->expires_at?->toIso8601String(),
            'is_expired'       => $p->isExpired(),
        ]);

        return Inertia::render('Organisations/Membership/Participants/Index', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'participants' => $participants,
            'filters'      => $request->only(['type', 'direction']),
            'stats'        => [
                'staff'              => OrganisationParticipant::where('organisation_id', $organisation->id)->staff()->count(),
                'guests'             => OrganisationParticipant::where('organisation_id', $organisation->id)->guests()->active()->count(),
                'election_committee' => OrganisationParticipant::where('organisation_id', $organisation->id)->electionCommittee()->count(),
            ],
        ]);
    }

    // ── store ─────────────────────────────────────────────────────────────────

    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorizeAdmin($request->user(), $organisation);

        $validated = $request->validate([
            'email'            => ['required', 'email', 'exists:users,email'],
            'participant_type' => ['required', 'in:staff,guest,election_committee'],
            'role'             => ['nullable', 'string', 'max:100'],
            'expires_at'       => ['nullable', 'date', 'after:now'],
            'permissions'      => ['nullable', 'array'],
        ]);

        $user = User::where('email', $validated['email'])->firstOrFail();

        OrganisationParticipant::create([
            'organisation_id'  => $organisation->id,
            'user_id'          => $user->id,
            'participant_type' => $validated['participant_type'],
            'role'             => $validated['role'] ?? null,
            'assigned_at'      => now(),
            'expires_at'       => $validated['expires_at'] ?? null,
            'permissions'      => $validated['permissions'] ?? null,
        ]);

        return back()->with('success', 'Participant added successfully.');
    }

    // ── destroy ───────────────────────────────────────────────────────────────

    public function destroy(Request $request, Organisation $organisation, OrganisationParticipant $participant): RedirectResponse
    {
        $this->authorizeAdmin($request->user(), $organisation);

        abort_if($participant->organisation_id !== $organisation->id, 404);

        $participant->delete(); // soft delete

        return back()->with('success', 'Participant removed.');
    }

    // ── helpers ───────────────────────────────────────────────────────────────

    private function authorizeAdmin(mixed $user, Organisation $organisation): void
    {
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');

        abort_if(! in_array($role, ['owner', 'admin']), 403);
    }
}
