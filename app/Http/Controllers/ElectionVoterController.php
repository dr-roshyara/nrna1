<?php

namespace App\Http\Controllers;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

/**
 * ElectionVoterController
 *
 * Manages voter assignments (ElectionMembership) for real elections.
 * Demo elections return 404 — voter management is not applicable to them.
 *
 * Authorization: ElectionPolicy (view = org member, manage = commission/admin)
 * Middleware: auth + verified + ensure.organisation  (via routes/organisations.php group)
 */
class ElectionVoterController extends Controller
{
    // =========================================================================
    // index — list voters assigned to the election
    // =========================================================================

    public function index(Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404, 'Voter management is not available for demo elections.');

        $this->authorize('view', $election);

        $voters = $election->memberships()
            ->with('user:id,name,email')
            ->where('role', 'voter')
            ->latest('assigned_at')
            ->paginate(25);

        return Inertia::render('Elections/Voters/Index', [
            'election'     => $election->only('id', 'name', 'type', 'status'),
            'organisation' => $organisation->only('id', 'slug', 'name'),
            'voters'       => $voters,
            'stats'        => $election->voter_stats,
        ]);
    }

    // =========================================================================
    // store — assign a single voter to the election
    // =========================================================================

    public function store(Request $request, Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manage', $election);

        $request->validate([
            'user_id' => [
                'required',
                'uuid',
                // Must be an org member
                function ($attribute, $value, $fail) use ($organisation) {
                    $isMember = DB::table('user_organisation_roles')
                        ->where('user_id', $value)
                        ->where('organisation_id', $organisation->id)
                        ->exists();
                    if (! $isMember) {
                        $fail('The selected user is not a member of this organisation.');
                    }
                },
            ],
        ]);

        try {
            ElectionMembership::assignVoter(
                $request->user_id,
                $election->id,
                auth()->id()
            );
        } catch (\Exception $e) {
            return back()->withErrors(['user_id' => $e->getMessage()]);
        }

        return back()->with('success', 'Voter assigned successfully.');
    }

    // =========================================================================
    // bulkStore — assign multiple voters in one request
    // =========================================================================

    public function bulkStore(Request $request, Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manage', $election);

        $request->validate([
            'user_ids'   => 'required|array|max:1000',
            'user_ids.*' => 'uuid',
        ]);

        // Filter to org members only
        $validIds = DB::table('user_organisation_roles')
            ->where('organisation_id', $organisation->id)
            ->whereIn('user_id', $request->user_ids)
            ->pluck('user_id')
            ->toArray();

        $invalidCount = count($request->user_ids) - count($validIds);

        $result = ElectionMembership::bulkAssignVoters(
            $validIds,
            $election->id,
            auth()->id()
        );

        $result['invalid'] = ($result['invalid'] ?? 0) + $invalidCount;

        return back()->with('bulk_result', $result);
    }

    // =========================================================================
    // destroy — remove (soft) a voter from the election
    // =========================================================================

    public function destroy(Organisation $organisation, string $election, ElectionMembership $membership)
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manage', $election);

        // Row lock prevents race condition: if voter's VoteController::store() is in a
        // live transaction, lockForUpdate() blocks until one side commits.
        DB::transaction(function () use ($membership) {
            $locked = ElectionMembership::where('id', $membership->id)
                ->lockForUpdate()
                ->firstOrFail();

            $locked->remove('Removed by ' . auth()->user()->name, auth()->user());
        });

        return back()->with('success', 'Voter removed successfully.');
    }

    // =========================================================================
    // approve — set a voter's status to active
    // =========================================================================

    public function approve(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if ($membership->status === 'active') {
            return back()->with('error', 'Voter is already approved.');
        }

        $membership->update(['status' => 'active', 'assigned_at' => now()]);
        Cache::forget("election.{$election->id}.voter_stats");

        return back()->with('success', "Voter {$membership->user->name} approved.");
    }

    // =========================================================================
    // suspend — set a voter's status to inactive
    // =========================================================================

    public function suspend(Organisation $organisation, string $election, ElectionMembership $membership): RedirectResponse
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404);

        $this->authorize('manageVoters', $election);

        if ($membership->election_id !== $election->id) {
            abort(404);
        }

        if ($membership->status === 'inactive') {
            return back()->with('error', 'Voter is already suspended.');
        }

        $membership->update(['status' => 'inactive']);
        Cache::forget("election.{$election->id}.voter_stats");

        return back()->with('success', "Voter {$membership->user->name} suspended.");
    }

    // =========================================================================
    // export — stream a CSV of assigned voters
    // =========================================================================

    public function export(Organisation $organisation, string $election)
    {
        $election = Election::withoutGlobalScopes()->findOrFail($election);
        abort_if($election->type === 'demo', 404);

        $this->authorize('view', $election);

        $voters = $election->memberships()
            ->with('user:id,name,email')
            ->where('role', 'voter')
            ->get();

        $filename = 'voters-' . $election->id . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($voters) {
            $handle = fopen('php://output', 'w');
            fprintf($handle, chr(0xEF) . chr(0xBB) . chr(0xBF)); // UTF-8 BOM
            fputcsv($handle, ['Name', 'Email', 'Status', 'Assigned At']);
            foreach ($voters as $membership) {
                fputcsv($handle, [
                    $membership->user->name  ?? '',
                    $membership->user->email ?? '',
                    $membership->status,
                    $membership->assigned_at?->toDateTimeString(),
                ]);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
