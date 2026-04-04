<?php

namespace App\Http\Controllers\Organisation;

use App\Http\Controllers\Controller;
use App\Mail\OrganisationInvitationMail;
use App\Models\Organisation;
use App\Models\OrganisationInvitation;
use App\Models\UserOrganisationRole;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Inertia\Inertia;

class OrganisationMemberInvitationController extends Controller
{
    private const ALLOWED_ROLES = ['member', 'admin', 'commission'];

    public function index(Request $request, Organisation $organisation)
    {
        $this->authorizeManage($request, $organisation);

        $pendingInvitations = OrganisationInvitation::with('invitedBy')
            ->where('organisation_id', $organisation->id)
            ->pending()
            ->latest()
            ->get()
            ->map(fn ($inv) => [
                'id'         => $inv->id,
                'email'      => $inv->email,
                'role'       => $inv->role,
                'invited_by' => $inv->invitedBy?->name,
                'expires_at' => $inv->expires_at->toDateString(),
                'created_at' => $inv->created_at->toDateString(),
            ]);

        return Inertia::render('Organisations/Members/Invite', [
            'organisation'       => $organisation->only('id', 'name', 'slug'),
            'pendingInvitations' => $pendingInvitations,
            'allowedRoles'       => self::ALLOWED_ROLES,
        ]);
    }

    public function store(Request $request, Organisation $organisation)
    {
        $this->authorizeManage($request, $organisation);

        $validated = $request->validate([
            'email'   => ['required', 'email', 'max:255'],
            'role'    => ['required', 'in:' . implode(',', self::ALLOWED_ROLES)],
            'message' => ['nullable', 'string', 'max:500'],
        ]);

        // Rate limiting: max 10 invitations per 5 minutes per user
        $recentInvites = OrganisationInvitation::where('organisation_id', $organisation->id)
            ->where('invited_by', $request->user()->id)
            ->where('created_at', '>', now()->subMinutes(5))
            ->count();

        if ($recentInvites >= 10) {
            return back()->withErrors(['email' => 'Too many invitations. Please wait a moment.']);
        }

        // Prevent re-inviting an existing member
        $alreadyMember = $organisation->users()
            ->where('users.email', $validated['email'])
            ->exists();

        if ($alreadyMember) {
            return back()->withErrors(['email' => 'This person is already a member of the organisation.']);
        }

        // Cancel any existing pending invitation for this email
        OrganisationInvitation::where('organisation_id', $organisation->id)
            ->where('email', $validated['email'])
            ->where('status', 'pending')
            ->update(['status' => 'cancelled']);

        $invitation = OrganisationInvitation::create([
            'organisation_id' => $organisation->id,
            'email'           => $validated['email'],
            'role'            => $validated['role'],
            'token'           => Str::random(64),
            'status'          => 'pending',
            'invited_by'      => $request->user()->id,
            'expires_at'      => now()->addDays(7),
            'message'         => $validated['message'] ?? null,
        ]);

        $acceptUrl = route('organisations.invitations.accept', $invitation->token);
        Mail::to($invitation->email)->send(new OrganisationInvitationMail($invitation, $acceptUrl));

        return back()->with('success', 'Invitation sent to ' . $invitation->email);
    }

    public function destroy(Request $request, Organisation $organisation, OrganisationInvitation $invitation)
    {
        $this->authorizeManage($request, $organisation);

        abort_if($invitation->organisation_id !== $organisation->id, 403);

        $invitation->update(['status' => 'cancelled']);

        return back()->with('success', 'Invitation cancelled.');
    }

    public function accept(string $token)
    {
        \Log::info('INVITE_ACCEPT: start', ['token_prefix' => substr($token, 0, 8)]);

        $invitation = OrganisationInvitation::with('organisation')
            ->where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        \Log::info('INVITE_ACCEPT: invitation found', [
            'invitation_id'   => $invitation->id,
            'email'           => $invitation->email,
            'organisation_id' => $invitation->organisation_id,
            'role'            => $invitation->role,
        ]);

        $user = auth()->user();

        \Log::info('INVITE_ACCEPT: auth user', [
            'user_id'   => $user?->id,
            'user_email'=> $user?->email,
        ]);

        $alreadyMember = UserOrganisationRole::where('organisation_id', $invitation->organisation_id)
            ->where('user_id', $user->id)
            ->exists();

        \Log::info('INVITE_ACCEPT: already member check', ['already_member' => $alreadyMember]);

        if (! $alreadyMember) {
            $role = UserOrganisationRole::create([
                'organisation_id' => $invitation->organisation_id,
                'user_id'         => $user->id,
                'role'            => $invitation->role,
            ]);
            \Log::info('INVITE_ACCEPT: role created', ['role_id' => $role->id]);
        }

        $invitation->update([
            'status'      => 'accepted',
            'accepted_by' => $user->id,
            'accepted_at' => now(),
        ]);

        \Log::info('INVITE_ACCEPT: complete, redirecting to org', [
            'slug' => $invitation->organisation->slug,
        ]);

        return redirect()
            ->route('organisations.show', $invitation->organisation->slug)
            ->with('success', 'Welcome to ' . $invitation->organisation->name . '!');
    }

    private function authorizeManage(Request $request, Organisation $organisation): void
    {
        $role = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->where('user_id', $request->user()->id)
            ->first();

        abort_if(! $role || ! in_array($role->role, ['owner', 'admin']), 403);
    }
}
