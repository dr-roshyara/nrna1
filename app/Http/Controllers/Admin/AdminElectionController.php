<?php

namespace App\Http\Controllers\Admin;

use App\Models\Election;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function approve(Request $request, Election $election): RedirectResponse
    {
        $request->validate([
            'approval_notes' => 'nullable|string|max:500',
        ]);

        try {
            $election->approve(auth()->id(), $request->input('approval_notes'));
            return back()->with('success', 'Election approved successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to approve election: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, Election $election): RedirectResponse
    {
        $request->validate([
            'rejection_reason' => 'required|string|min:10|max:500',
        ]);

        try {
            $election->reject(auth()->id(), $request->input('rejection_reason'));
            return back()->with('success', 'Election rejected successfully.');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to reject election: ' . $e->getMessage());
        }
    }
}
