<?php

namespace App\Services;

use App\Models\Election;
use App\Models\Post;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Assembles ballot data (posts + candidates) for a real election.
 *
 * Extracted from VoteController::create()'s real-election branches so the
 * exact same query/mapping logic can be reused by the read-only ballot
 * preview feature without duplicating it. Demo-election assembly stays
 * inline in VoteController — out of scope for this service.
 */
final class BallotAssemblyService
{
    public function buildNationalPosts(Election $election): Collection
    {
        return Post::withoutGlobalScopes()
            ->with(['candidacies' => function ($query) {
                $query->withoutGlobalScopes()->with('user')->orderBy('position_order');
            }])
            ->where('election_id', $election->id)
            ->where('is_national_wide', true)
            ->orderBy('position_order')
            ->get()
            ->map(function ($post) {
                return [
                    'post_id' => $post->id,
                    'name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'required_number' => $post->required_number,
                    'candidates' => $post->candidacies->map(function ($c) {
                        return [
                            'candidacy_id' => $c->id,
                            'user' => [
                                'id' => $c->user_id,
                                'name' => $c->user?->name ?? $c->name ?? 'Candidate',
                            ],
                            'post_id' => $c->post_id,
                            'image_path_1' => $c->image_path_1,
                            'candidacy_name' => $c->name,
                            'proposer_name' => null,
                            'supporter_name' => null,
                            'position_order' => $c->position_order,
                        ];
                    })->values(),
                ];
            })->values();
    }

    /**
     * @param string $region Empty string returns no regional posts — this is
     *     load-bearing behavior both the real vote flow and the ballot
     *     preview depend on (a voter/viewer with no region set sees none).
     */
    public function buildRegionalPosts(Election $election, string $region): Collection
    {
        if ($region === '') {
            return collect();
        }

        return Post::withoutGlobalScopes()
            ->with(['candidacies' => function ($query) {
                $query->withoutGlobalScopes()->with('user')->orderBy('position_order');
            }])
            ->where('election_id', $election->id)
            ->where('is_national_wide', false)
            ->where('state_name', trim($region))
            ->orderBy('position_order')
            ->get()
            ->map(function ($post) {
                return [
                    'post_id' => $post->id,
                    'name' => $post->name,
                    'nepali_name' => $post->nepali_name,
                    'required_number' => $post->required_number,
                    'candidates' => $post->candidacies->map(function ($c) {
                        return [
                            'candidacy_id' => $c->id,
                            'user' => [
                                'id' => $c->user_id,
                                'name' => $c->user?->name ?? $c->name ?? 'Candidate',
                            ],
                            'post_id' => $c->post_id,
                            'image_path_1' => $c->image_path_1,
                            'candidacy_name' => $c->name,
                            'proposer_name' => null,
                            'supporter_name' => null,
                            'position_order' => $c->position_order,
                        ];
                    })->values(),
                ];
            })->values();
    }

    public function buildElectionProp(Election $election): array
    {
        $settings = Cache::remember(
            "election-settings-{$election->id}",
            300,
            function () use ($election) {
                return [
                    'no_vote_option_enabled' => $election->isNoVoteEnabled(),
                    'no_vote_option_label' => $election->no_vote_option_label ?? 'Abstain',
                    'selection_constraint_type' => $election->getSelectionConstraintType(),
                    'selection_constraint_min' => $election->selection_constraint_min,
                    'selection_constraint_max' => $election->selection_constraint_max,
                ];
            }
        );

        $hasRegionalPosts = Post::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('is_national_wide', false)
            ->exists();

        return array_merge([
            'id' => $election->id,
            'name' => $election->name,
            'type' => $election->type,
            'description' => $election->description,
            'is_active' => \App\Application\Election\Facades\ElectionLifecycle::of($election)->canVote(),
            'has_regional_posts' => $hasRegionalPosts,
        ], $settings);
    }
}
