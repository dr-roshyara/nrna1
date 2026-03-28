<?php

namespace App\Http\Controllers\Election;

use App\Http\Controllers\Controller;
use App\Models\Candidacy;
use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class CandidacyReviewController extends Controller
{
    public function index(Organisation $organisation, Election $election): Response
    {
        abort_if($election->type === 'demo', 404);
        $this->authorize('managePosts', $election);

        $applications = CandidacyApplication::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('organisation_id', $organisation->id)
            ->with([
                'user' => fn ($q) => $q->withoutGlobalScopes(),
                'post' => fn ($q) => $q->withoutGlobalScopes(),
            ])
            ->orderBy('created_at')
            ->get()
            ->map(fn ($app) => [
                'id'           => $app->id,
                'status'       => $app->status,
                'candidacy_id' => $app->candidacy_id,
                'submitted_at' => $app->created_at->format('Y-m-d H:i'),
                'manifesto'        => $app->manifesto,
                'photo'            => $app->photo,
                'proposer_name'    => $app->proposer_name,
                'supporter_name'   => $app->supporter_name,
                'rejection_reason' => $app->rejection_reason,
                'user' => [
                    'id'    => $app->user?->id,
                    'name'  => $app->user?->name ?? '—',
                    'email' => $app->user?->email,
                    'photo' => $app->user?->profile_photo_path,
                ],
                'post' => [
                    'id'   => $app->post?->id,
                    'name' => $app->post?->name ?? '—',
                ],
            ]);

        return Inertia::render('Election/Candidacy/Applications', [
            'organisation' => $organisation->only('id', 'name', 'slug'),
            'election'     => $election->only('id', 'name', 'slug'),
            'applications' => $applications->values(),
        ]);
    }

    public function review(
        Request $request,
        Organisation $organisation,
        Election $election,
        CandidacyApplication $application
    ): RedirectResponse {
        abort_if($election->type === 'demo', 404);
        $this->authorize('managePosts', $election);
        abort_if($application->election_id !== $election->id, 403);
        abort_if($application->organisation_id !== $organisation->id, 403);
        abort_if($application->status !== 'pending', 422, 'Application has already been processed.');

        $data = $request->validate([
            'action'           => ['required', 'in:approve,reject'],
            'rejection_reason' => ['nullable', 'string', 'max:500', 'required_if:action,reject'],
        ]);

        DB::transaction(function () use ($application, $data, $organisation) {
            $now = now();
            $reviewerId = auth()->id();

            if ($data['action'] === 'reject') {
                $application->update([
                    'status'           => 'rejected',
                    'rejection_reason' => $data['rejection_reason'],
                    'reviewed_at'      => $now,
                    'reviewed_by'      => $reviewerId,
                ]);
                return;
            }

            // Create draft candidacy from the application
            $candidacy = Candidacy::withoutGlobalScopes()->create([
                'post_id'         => $application->post_id,
                'organisation_id' => $organisation->id,
                'user_id'         => $application->user_id,
                'name'            => $application->user?->name,
                'description'     => $application->manifesto,
                'image_path_1'    => $application->photo,
                'position_order'  => 0,
                'status'          => Candidacy::STATUS_DRAFT,
            ]);

            $application->update([
                'status'       => 'approved',
                'candidacy_id' => $candidacy->id,
                'reviewed_at'  => $now,
                'reviewed_by'  => $reviewerId,
            ]);
        });

        $message = $data['action'] === 'approve'
            ? 'Application approved. A draft candidate entry has been created — visit Posts & Candidates to publish.'
            : 'Application rejected.';

        return back()->with('success', $message);
    }
}
