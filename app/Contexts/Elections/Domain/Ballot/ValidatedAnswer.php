<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * ValidatedAnswer — one answered post of a ValidatedBallot: either Selected (1 … maximum candidacies of
 * the authoritative post) or Abstained (explicit, permitted by the election). "Not answered" posts are not
 * represented (N2, N3).
 */
final class ValidatedAnswer
{
    /** @param list<string> $candidacyIds */
    public function __construct(
        public readonly AuthoritativePost $post,
        public readonly bool $abstained,
        public readonly array $candidacyIds,
    ) {}
}
