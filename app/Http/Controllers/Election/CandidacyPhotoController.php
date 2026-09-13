<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Candidacy;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Candidate photo editing (crop/replace) — committee-only, pre-voting only.
 *
 * Authorization boundary: ElectionPolicy::managePosts() (active ElectionOfficer,
 * role chief|deputy — the same policy already gating CandidacyManagementController's
 * store/update/destroy). A commissioner (view-only committee role) is denied.
 *
 * Lifecycle boundary: the 'election.state:edit_candidate_photo' route middleware
 * (OperationCapabilityMapper) — allowed only in pre-voting states, denied from
 * VotingActive onward and while suspended.
 *
 * Both boundaries are enforced here, independently of whatever the frontend does
 * or doesn't show (see Organisations/Candidates.vue's can_edit_candidate_photos —
 * UX guidance only, never a security boundary).
 */
class CandidacyPhotoController extends Controller
{
    public function update(Request $request, Organisation $organisation, Election $election, Candidacy $candidacy): RedirectResponse
    {
        abort_if($election->type === 'demo', 404);
        $this->authorize('managePosts', $election);

        // Ownership: the candidacy must belong to BOTH the organisation and the
        // exact election named in the URL — not merely the same organisation.
        // Candidacy has no direct election_id column (see Candidacy::booted()'s
        // own comment: "candidacies.election_id doesn't exist, must join posts
        // to filter by election"), so this traverses post->election_id.
        abort_if($candidacy->organisation_id !== $organisation->id, 403);
        abort_if(!$candidacy->post || $candidacy->post->election_id !== $election->id, 403);

        $data = $request->validate([
            'photo' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        // Mandatory ordering: validate -> store new -> update candidacy -> delete
        // old. Never delete the existing photo before the new one is confirmed
        // stored and the candidacy row successfully updated — if anything above
        // this point fails, the original photo remains untouched.
        $oldPath = $candidacy->image_path_1;
        $newPath = $request->file('photo')->store("candidacies/{$organisation->id}", 'public');
        $candidacy->update(['image_path_1' => $newPath]);

        if ($oldPath) {
            Storage::disk('public')->delete($oldPath);
        }

        return back()->with('success', __('Candidate photo updated.'));
    }
}
