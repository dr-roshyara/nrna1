<?php

namespace App\Http\Controllers;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\UserOrganisationRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CandidacyApplicationController extends Controller
{
    public function store(Request $request, Organisation $organisation): RedirectResponse
    {
        $user = auth()->user();

        $role = UserOrganisationRole::where('user_id', $user->id)
            ->where('organisation_id', $organisation->id)
            ->value('role');
        abort_if(! $role, 403);

        $validated = $request->validate([
            'election_id'    => 'required|uuid|exists:elections,id',
            'post_id'        => 'required|uuid|exists:posts,id',
            'supporter_name' => 'required|string|max:255',
            'proposer_name'  => 'required|string|max:255',
            'manifesto'      => 'nullable|string|max:5000',
            'documents'      => 'nullable|array|max:5',
            'documents.*'    => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $election = Election::withoutGlobalScopes()
            ->where('id', $validated['election_id'])
            ->where('organisation_id', $organisation->id)
            ->where('status', 'active')
            ->firstOrFail();

        Post::withoutGlobalScopes()
            ->where('id', $validated['post_id'])
            ->where('election_id', $election->id)
            ->firstOrFail();

        $existing = CandidacyApplication::where('user_id', $user->id)
            ->where('election_id', $election->id)
            ->where('post_id', $validated['post_id'])
            ->whereIn('status', ['pending', 'approved'])
            ->exists();

        if ($existing) {
            return back()->with('error', 'You have already applied for this position.');
        }

        return DB::transaction(function () use ($user, $organisation, $election, $validated, $request) {
            $documents = [];
            if ($request->hasFile('documents')) {
                foreach ($request->file('documents') as $doc) {
                    $documents[] = $doc->store("candidacy/{$organisation->id}/{$user->id}", 'public');
                }
            }

            CandidacyApplication::create([
                'user_id'         => $user->id,
                'organisation_id' => $organisation->id,
                'election_id'     => $election->id,
                'post_id'         => $validated['post_id'],
                'supporter_name'  => $validated['supporter_name'],
                'proposer_name'   => $validated['proposer_name'],
                'manifesto'       => $validated['manifesto'] ?? null,
                'documents'       => $documents ?: null,
                'status'          => CandidacyApplication::STATUS_PENDING,
            ]);

            return back()->with('success', 'Your candidacy application has been submitted for review.');
        });
    }
}
