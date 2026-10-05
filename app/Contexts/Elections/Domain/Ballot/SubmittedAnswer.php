<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * SubmittedAnswer — the voter's proposed answer for one post, as normalised from transport data.
 * Untrusted: only post id, the abstention flag and candidacy ids are kept (I11).
 */
final class SubmittedAnswer
{
    /** @param list<string> $candidacyIds */
    public function __construct(
        public readonly string $postId,
        public readonly bool $abstain,
        public readonly array $candidacyIds,
    ) {}
}
