<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * AuthoritativeBallot — the posts and electable candidacies this election voter may vote on, plus the
 * election's abstention permission (VE-01, ADR_20261005_0950 rev 3 §3.0).
 *
 * Assembled server-side for one CastContext (today by the current ballot assembly authority; the
 * constitutional regional authority is D6 / PBDIGIT-236). Never derived from transport data.
 */
final class AuthoritativeBallot
{
    /** @var array<string, AuthoritativePost> */
    private array $posts = [];

    /** @param list<AuthoritativePost> $posts */
    public function __construct(array $posts, public readonly bool $abstentionAllowed)
    {
        foreach ($posts as $post) {
            $this->posts[$post->postId] = $post;
        }
    }

    public function post(string $postId): ?AuthoritativePost
    {
        return $this->posts[$postId] ?? null;
    }
}
