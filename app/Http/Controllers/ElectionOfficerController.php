<?php

namespace App\Http\Controllers;

use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Notifications\OfficerAppointedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ElectionOfficerController extends Controller
{
    /**
     * Show the officer management page.
     */
    public function index(Organisation $organisation)
    {
        $this->authorize('manage', [ElectionOfficer::class, $organisation]);

        $officers = ElectionOfficer::with('user:id,name,email', 'appointer:id,name', 'election:id,name')
            ->where('organisation_id', $organisation->id)
            ->orderByRaw("FIELD(status, 'active', 'pending', 'inactive', 'resigned')")
            ->orderBy('role')
            ->get()
            ->map(fn ($o) => [
                'id'             => $o->id,
                'user_id'        => $o->user_id,
                'user_name'      => $o->user->name      ?? '',
                'user_email'     => $o->user->email     ?? '',
                'appointed_by'   => $o->appointer->name ?? '',
                'role'           => $o->role,
                'status'         => $o->status,
                'election_id'    => $o->election_id,
                'election_name'  => $o->election?->name,
                'appointed_at'   => $o->appointed_at?->toDateString(),
                'accepted_at'    => $o->accepted_at?->toDateString(),
            ]);

        $elections = \App\Models\Election::withoutGlobalScopes()
            ->where('organisation_id', $organisation->id)
            ->where('type', 'real')
            ->orderByDesc('created_at')
            ->get(['id', 'name']);

        $orgMembers = DB::table('user_organisation_roles as ur')
            ->join('users as u', 'u.id', '=', 'ur.user_id')
            ->where('ur.organisation_id', $organisation->id)
            ->select('u.id', 'u.name', 'u.email')
            ->orderBy('u.name')
            ->get();

        return inertia('Organisations/ElectionOfficers/Index', [
            'organisation' => $organisation->only(['id', 'name', 'slug']),
            'officers'     => $officers,
            'orgMembers'   => $orgMembers,
            'elections'    => $elections,
            'canManage'    => true,
        ]);
    }

    /**
     * Appoint a member as a pending officer.
     */
    public function store(Request $request, Organisation $organisation)
    {
        $this->authorize('manage', [ElectionOfficer::class, $organisation]);

        $request->validate([
            'user_id' => [
                'required',
                'uuid',
                function ($attribute, $value, $fail) use ($organisation) {
                    $isMember = DB::table('user_organisation_roles')
                        ->where('user_id', $value)
                        ->where('organisation_id', $organisation->id)
                        ->exists();

                    if (! $isMember) {
                        $fail('The selected user is not a member of this organisation.');
                    }
                },
                function ($attribute, $value, $fail) use ($organisation, $request) {
                    $query = ElectionOfficer::where('user_id', $value)
                        ->where('organisation_id', $organisation->id)
                        ->whereNull('deleted_at');

                    // Duplicate check is scoped to the same election (or org-wide slot)
                    if ($request->election_id) {
                        $query->where('election_id', $request->election_id);
                    } else {
                        $query->whereNull('election_id');
                    }

                    if ($query->exists()) {
                        $fail('This user is already an election officer for this election.');
                    }
                },
            ],
            'role'        => ['required', 'in:chief,deputy,commissioner'],
            'election_id' => ['nullable', 'uuid', 'exists:elections,id'],
        ]);

        $electionId = $request->election_id ?: null;

        // Restore a soft-deleted record if one exists (the DB unique constraint
        // covers trashed rows too, so a plain create() would blow up).
        $trashed = ElectionOfficer::withTrashed()
            ->where('user_id', $request->user_id)
            ->where('organisation_id', $organisation->id)
            ->where(function ($q) use ($electionId) {
                $electionId
                    ? $q->where('election_id', $electionId)
                    : $q->whereNull('election_id');
            })
            ->whereNotNull('deleted_at')
            ->first();

        if ($trashed) {
            $trashed->restore();
            $trashed->update([
                'role'         => $request->role,
                'status'       => 'pending',
                'appointed_by' => auth()->id(),
                'appointed_at' => now(),
                'accepted_at'  => null,
            ]);
            $officer = $trashed;
        } else {
            $officer = ElectionOfficer::create([
                'organisation_id' => $organisation->id,
                'user_id'         => $request->user_id,
                'election_id'     => $electionId,
                'role'            => $request->role,
                'status'          => 'pending',
                'appointed_by'    => auth()->id(),
                'appointed_at'    => now(),
            ]);
        }

        $officer->user->notify(new OfficerAppointedNotification($officer));

        return back()->with('success', 'Election officer appointed. An invitation email has been sent.');
    }

    /**
     * The appointed user accepts their own pending appointment.
     */
    public function accept(Organisation $organisation, ElectionOfficer $officer)
    {
        $this->authorize('accept', $officer);

        abort_if($officer->organisation_id !== $organisation->id, 404);
        abort_if(! $officer->isPending(), 400, 'Only pending appointments can be accepted.');

        $officer->markAccepted();

        return back()->with('success', 'You have accepted your election officer appointment.');
    }

    /**
     * Remove an officer (soft-delete).
     */
    public function destroy(Organisation $organisation, ElectionOfficer $officer)
    {
        $this->authorize('manage', [ElectionOfficer::class, $organisation]);

        abort_if($officer->organisation_id !== $organisation->id, 404);

        $officer->delete();

        return back()->with('success', 'Election officer removed successfully.');
    }
}
