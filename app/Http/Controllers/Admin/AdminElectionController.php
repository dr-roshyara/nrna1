<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminElectionController extends Controller
{
    public function pending(): Response
    {
        $elections = Election::withoutGlobalScopes()
            ->pendingApproval()
            ->orderBy('submitted_for_approval_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Elections/Pending', [
            'elections' => $elections,
        ]);
    }

    public function approve(Request $request, string $election): RedirectResponse
    {
        $request->validate([
            'notes' => 'nullable|string|max:500',
        ]);

        $election = Election::withoutGlobalScopes()->findOrFail($election);

        if ($election->state !== 'pending_approval') {
            return back()->with('error', 'Election is not in pending approval state.');
        }

        try {
            $election->approve(auth()->id(), $request->input('notes'));
            return back()->with('success', 'Election approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve election: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, string $election): RedirectResponse
    {
        $request->validate([
            'reason' => 'required|string|min:10|max:500',
        ]);

        $election = Election::withoutGlobalScopes()->findOrFail($election);

        if ($election->state !== 'pending_approval') {
            return back()->with('error', 'Election is not in pending approval state.');
        }

        try {
            $election->reject(auth()->id(), $request->input('reason'));
            return back()->with('success', 'Election rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject election: ' . $e->getMessage());
        }
    }

    public function all(Request $request): \Inertia\Response
    {
        $filter = $request->query('filter', 'all');
        $sort = $request->query('sort', 'created_at');
        $direction = $request->query('direction', 'desc');

        $query = Election::withoutGlobalScopes()->with('organisation:id,name');

        if ($filter === 'free') {
            $query->where('expected_voter_count', '<=', 40);
        } elseif ($filter === 'paid') {
            $query->where('expected_voter_count', '>', 40);
        }

        $allowedSorts = ['created_at', 'name', 'expected_voter_count', 'state'];
        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->orderBy('created_at', 'desc');
        }

        $elections = $query->paginate(25)
            ->withQueryString()
            ->through(fn (Election $election) => [
                'id'                    => $election->id,
                'name'                  => $election->name,
                'slug'                  => $election->slug,
                'state'                 => $election->state,
                'expected_voter_count'  => $election->expected_voter_count ?? 0,
                'is_free'               => ($election->expected_voter_count ?? 0) <= 40,
                'created_at'            => $election->created_at?->toIso8601String(),
                'organisation'          => [
                    'id'   => $election->organisation?->id,
                    'name' => $election->organisation?->name,
                ],
            ]);

        return Inertia::render('Admin/Elections/All', [
            'elections' => $elections,
            'filters'   => [
                'filter'    => $filter,
                'sort'      => $sort,
                'direction' => $direction,
            ],
        ]);
    }
}
