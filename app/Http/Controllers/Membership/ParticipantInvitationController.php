<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Mail\OrganisationInvitationMail;
use App\Models\Organisation;
use App\Models\OrganisationInvitation;
use App\Models\OrganisationParticipant;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class ParticipantInvitationController extends Controller
{
    private const PARTICIPANT_TYPES = ['staff', 'guest', 'election_committee'];

    public function index(Request $request, Organisation $organisation)
    {
        $this->authorizeAdmin($request, $organisation);

        $pendingInvitations = OrganisationInvitation::with('invitedBy')
            ->where('organisation_id', $organisation->id)
            ->where('invitation_type', 'participant')
            ->pending()
            ->latest()
            ->get()
            ->map(fn ($inv) => [
                'id'               => $inv->id,
                'email'            => $inv->email,
                'participant_type' => $inv->participant_type,
                'invited_by'       => $inv->invitedBy?->name,
                'expires_at'       => $inv->expires_at->toDateString(),
                'created_at'       => $inv->created_at->toDateString(),
            ]);

        return Inertia::render('Organisations/Membership/Participants/Invite', [
            'organisation'       => $organisation->only('id', 'name', 'slug'),
            'pendingInvitations' => $pendingInvitations,
            'participantTypes'   => self::PARTICIPANT_TYPES,
        ]);
    }

    public function store(Request $request, Organisation $organisation)
    {
        $this->authorizeAdmin($request, $organisation);

        $validated = $request->validate([
            'email'            => ['required', 'email', 'max:255'],
            'participant_type' => ['required', 'in:' . implode(',', self::PARTICIPANT_TYPES)],
            'message'          => ['nullable', 'string', 'max:500'],
        ]);

        // Rate limiting: max 10 invitations per 5 minutes
        $recentInvites = OrganisationInvitation::where('organisation_id', $organisation->id)
            ->where('invited_by', $request->user()->id)
            ->where('created_at', '>', now()->subMinutes(5))
            ->count();

        if ($recentInvites >= 10) {
            return back()->withErrors(['email' => 'Too many invitations. Please wait a moment.']);
        }

        // Cancel any existing pending participant invitation for this email
        OrganisationInvitation::where('organisation_id', $organisation->id)
            ->where('email', $validated['email'])
            ->where('invitation_type', 'participant')
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        $invitation = OrganisationInvitation::create([
            'organisation_id'  => $organisation->id,
            'email'            => $validated['email'],
            'role'             => $validated['participant_type'],
            'invitation_type'  => 'participant',
            'participant_type' => $validated['participant_type'],
            'token'            => Str::random(64),
            'status'           => 'pending',
            'invited_by'       => $request->user()->id,
            'expires_at'       => now()->addDays(7),
            'message'          => $validated['message'] ?? null,
        ]);

        $acceptUrl = route('organisations.participant-invitations.accept', $invitation->token);
        Mail::to($invitation->email)->send(new OrganisationInvitationMail($invitation, $acceptUrl));

        return back()->with('success', 'Invitation sent to ' . $invitation->email);
    }

    public function destroy(Request $request, Organisation $organisation, OrganisationInvitation $invitation)
    {
        $this->authorizeAdmin($request, $organisation);

        abort_if($invitation->organisation_id !== $organisation->id, 403);

        $invitation->update(['status' => 'cancelled']);

        return back()->with('success', 'Invitation cancelled.');
    }

    public function accept(string $token)
    {
        $invitation = OrganisationInvitation::with('organisation')
            ->where('token', $token)
            ->where('invitation_type', 'participant')
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        $user = auth()->user();

        // Idempotent: don't create a duplicate participant record
        OrganisationParticipant::firstOrCreate(
            [
                'organisation_id' => $invitation->organisation_id,
                'user_id'         => $user->id,
            ],
            [
                'participant_type' => $invitation->participant_type,
                'assigned_at'      => now(),
            ]
        );

        $invitation->update([
            'status'      => 'accepted',
            'accepted_by' => $user->id,
            'accepted_at' => now(),
        ]);

        return redirect()
            ->route('organisations.show', $invitation->organisation->slug)
            ->with('success', 'Welcome to ' . $invitation->organisation->name . '!');
    }

    private function authorizeAdmin(Request $request, Organisation $organisation): void
    {
        $role = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_if(! $role || ! in_array($role->role, ['owner', 'admin']), 403);
    }
}
