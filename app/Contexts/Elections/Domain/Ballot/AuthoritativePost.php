<?php

namespace App\Contexts\Elections\Domain\Ballot;

/**
 * AuthoritativePost — one post of the voter's authoritative ballot (VE-01, ADR_20261005_0950 rev 3 §3.0).
 *
 * Built server-side from the election's ballot authority, never from transport data. `maxSelections` is
 * posts.required_number as the per-post MAXIMUM (D2); `candidacies` are the electable (approved, D5)
 * candidacies of this post, keyed by candidacy id with their display name.
 */
final class AuthoritativePost
{
    /** @param array<string, string> $candidacies candidacy id => display name */
    public function __construct(
        public readonly string $postId,
        public readonly string $name,
        public readonly string $nepaliName,
        public readonly int $maxSelections,
        public readonly array $candidacies,
    ) {}

    public function hasCandidacy(string $candidacyId): bool
    {
        return array_key_exists($candidacyId, $this->candidacies);
    }
}
