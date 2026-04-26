<?php

namespace App\Http\Controllers\Admin;

use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class AdminElectionController
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

    public function approve(Election $election): RedirectResponse
    {
        try {
            $election->approve(auth()->id());
            return back()->with('success', 'Election approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve election: ' . $e->getMessage());
        }
    }

    public function reject(Election $election): RedirectResponse
    {
        $reason = request()->input('reason', 'Rejected by admin');

        try {
            $election->reject(auth()->id(), $reason);
            return back()->with('success', 'Election rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject election: ' . $e->getMessage());
        }
    }
}
