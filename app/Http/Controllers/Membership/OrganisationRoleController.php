<?php

namespace App\Http\Controllers\Membership;

use App\Http\Controllers\Controller;
use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Member;
use App\Models\Organisation;
use App\Models\OrganisationUser;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class OrganisationRoleController extends Controller
{
    public function index(Request $request, Organisation $organisation): Response
    {
        $this->authorizeAdmin($request->user(), $organisation);

        $roles = UserOrganisationRole::where('organisation_id', $organisation->id)
            ->with('user')
            ->orderByRaw("FIELD(role, 'owner', 'admin', 'commission', 'voter', 'member')")
            ->get();

        // User IDs that already have a Member record
        $memberUserIds = OrganisationUser::withoutGlobalScopes()
            ->where('organisation_users.organisation_id', $organisation->id)
            ->whereIn('organisation_users.user_id', $roles->pluck('user_id'))
            ->join('members', 'members.organisation_user_id', '=', 'organisation_users.id')
            ->pluck('organisation_users.user_id')
            ->flip();

        // All active officer assignments for this organisation, keyed by user_id
        $officerAssignments = ElectionOfficer::where('organisation_id', $organisation->id)
            ->where('status', 'active')
            ->with('election:id,name')
            ->get()
            ->groupBy('user_id')
            ->map(fn ($records) => $records->map(fn ($o) => [
                'election_id'   => $o->election_id,
                'election_name' => $o->election?->name ?? '—',
                'role'          => $o->role,
            ])->values());

        $users = $roles->map(fn ($r) => [
            'user_id'            => $r->user_id,
            'name'               => $r->user?->name ?? '—',
            'email'              => $r->user?->email ?? '—',
            'role'               => $r->role,
            'is_member'          => $memberUserIds->has($r->user_id),
            'joined_at'          => $r->created_at?->toIso8601String(),
            'officer_assignments' => $officerAssignments->get($r->user_id, collect())->toArray(),
        ]);

        // Elections for the assign-officer dropdown
        $elections = Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->orderByDesc('created_at')
            ->get(['id', 'name', 'status']);

        return Inertia::render('Organisations/Membership/Roles/Index', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'users'        => $users,
            'elections'    => $elections,
        ]);
    }

    public function addMember(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorizeAdmin($request->user(), $organisation);

        $request->validate(['user_id' => 'required|uuid|exists:users,id']);

        $user = User::findOrFail($request->user_id);

        $orgUser = OrganisationUser::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('user_id', $user->id)
            ->first();

        if ($orgUser) {
            $alreadyMember = Member::withoutGlobalScopes()
                ->where('organisation_user_id', $orgUser->id)
                ->exists();

            if ($alreadyMember) {
                return back()->with('error', 'This user is already a formal member.');
            }
        } else {
            $orgUser = OrganisationUser::withoutGlobalScopes()->firstOrCreate(
                ['organisation_id' => $organisation->id, 'user_id' => $user->id],
                ['id' => (string) Str::uuid(), 'role' => 'member', 'status' => 'active']
            );
        }

        Member::create([
            'id'                   => (string) Str::uuid(),
            'organisation_id'      => $organisation->id,
            'organisation_user_id' => $orgUser->id,
            'membership_number'    => 'M' . strtoupper(Str::random(8)),
            'status'               => 'active',
            'fees_status'          => 'exempt',
            'joined_at'            => now(),
            'created_by'           => $request->user()->id,
        ]);

        return back()->with('success', $user->name . ' has been added as a formal member.');
    }

    public function assignOfficer(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorizeAdmin($request->user(), $organisation);

        $validated = $request->validate([
            'user_id'     => 'required|uuid|exists:users,id',
            'election_id' => 'required|uuid|exists:elections,id',
            'role'        => 'required|in:chief,deputy,commissioner',
        ]);

        // Verify the election belongs to this organisation
        $election = Election::withoutGlobalScopes()
            ->where('id', $validated['election_id'])
            ->where('organisation_id', $organisation->id)
            ->firstOrFail();

        // Check for existing assignment on this election
        $existing = ElectionOfficer::where('user_id', $validated['user_id'])
            ->where('election_id', $election->id)
            ->first();

        if ($existing) {
            // Update role if different
            if ($existing->role !== $validated['role']) {
                $existing->update(['role' => $validated['role'], 'status' => 'active']);
                $user = User::find($validated['user_id']);
                return back()->with('success', ($user->name ?? 'User') . ' officer role updated to ' . $validated['role'] . ' for "' . $election->name . '".');
            }
            return back()->with('error', 'This user is already assigned as ' . $existing->role . ' for this election.');
        }

        ElectionOfficer::create([
            'id'              => (string) Str::uuid(),
            'organisation_id' => $organisation->id,
            'user_id'         => $validated['user_id'],
            'election_id'     => $election->id,
            'role'            => $validated['role'],
            'status'          => 'active',
        ]);

        $user = User::find($validated['user_id']);
        return back()->with('success', ($user->name ?? 'User') . ' assigned as ' . $validated['role'] . ' for "' . $election->name . '".');
    }

    public function removeOfficer(Request $request, Organisation $organisation): RedirectResponse
    {
        $this->authorizeAdmin($request->user(), $organisation);

        $validated = $request->validate([
            'user_id'     => 'required|uuid|exists:users,id',
            'election_id' => 'required|uuid|exists:elections,id',
        ]);

        ElectionOfficer::where('user_id', $validated['user_id'])
            ->where('election_id', $validated['election_id'])
            ->where('organisation_id', $organisation->id)
            ->delete();

        return back()->with('success', 'Officer assignment removed.');
    }

    private function authorizeAdmin(mixed $user, Organisation $organisation): void
    {
        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');

        abort_if(! in_array($role, ['owner', 'admin']), 403);
    }
}
