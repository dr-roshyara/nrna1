<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Contexts\Elections\Application\Ports\AuthoritativeBallotQuery;
use App\Contexts\Elections\Domain\Ballot\AuthoritativeBallot;
use App\Contexts\Elections\Domain\Ballot\AuthoritativePost;
use App\Models\Election;
use App\Models\User;
use App\Services\BallotAssemblyService;

/**
 * Adapter: the authoritative ballot from the current ballot assembly authority (BallotAssemblyService —
 * the same assembly the ballot page and the preview use; approved candidacies only, D5).
 *
 * Regional posts follow the voter's current users.region — the existing source, used here without adopting
 * it as the constitutional rule (D6 / PBDIGIT-236 open). Abstention permission is the election's
 * no-vote option (N4).
 */
final class AssembledAuthoritativeBallotQuery implements AuthoritativeBallotQuery
{
    public function __construct(private readonly BallotAssemblyService $assembly) {}

    public function ballotFor(string $voterId, string $electionId): AuthoritativeBallot
    {
        $election = Election::withoutGlobalScopes()->findOrFail($electionId);
        $voter = User::findOrFail($voterId);

        $assembled = $this->assembly->buildNationalPosts($election)
            ->concat($this->assembly->buildRegionalPosts($election, (string) ($voter->region ?? '')));

        $posts = $assembled->map(fn (array $post) => new AuthoritativePost(
            postId: (string) $post['post_id'],
            name: (string) $post['name'],
            nepaliName: (string) ($post['nepali_name'] ?? ''),
            maxSelections: (int) $post['required_number'],
            candidacies: collect($post['candidates'])
                ->mapWithKeys(fn (array $c) => [(string) $c['candidacy_id'] => (string) ($c['user']['name'] ?? $c['candidacy_name'] ?? '')])
                ->all(),
        ))->values()->all();

        return new AuthoritativeBallot($posts, $election->isNoVoteEnabled());
    }
}
