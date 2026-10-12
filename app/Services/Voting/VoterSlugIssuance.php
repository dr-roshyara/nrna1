<?php

namespace App\Services\Voting;

use App\Contexts\Elections\Domain\ValueObjects\VotingEntitlementReason;
use App\Models\VoterSlug;

/**
 * The result of VoterSlugIssuer::issue() (PBDIGIT-274, plan section 5): the voter either holds exactly one
 * current, valid voting link (REUSED unchanged, or ISSUED new), or gets none (ALREADY_VOTED, NOT_ENTITLED).
 */
final class VoterSlugIssuance
{
    public const REUSED = 'reused';
    public const ISSUED = 'issued';
    public const ALREADY_VOTED = 'already_voted';
    public const NOT_ENTITLED = 'not_entitled';

    private function __construct(
        public readonly string $outcome,
        public readonly ?VoterSlug $slug,
        public readonly ?VotingEntitlementReason $refusal,
    ) {
    }

    /** A valid current link already existed and is returned unchanged. */
    public static function reused(VoterSlug $slug): self
    {
        return new self(self::REUSED, $slug, null);
    }

    /** A new link was created (the previous one, if any, was retired and kept as history). */
    public static function issued(VoterSlug $slug): self
    {
        return new self(self::ISSUED, $slug, null);
    }

    /** The voter has already voted in this election: no link, nothing written. */
    public static function alreadyVoted(): self
    {
        return new self(self::ALREADY_VOTED, null, null);
    }

    /** The voter is not (or no longer) entitled: no link; a current link was retired. */
    public static function notEntitled(VotingEntitlementReason $reason): self
    {
        return new self(self::NOT_ENTITLED, null, $reason);
    }

    public function hasLink(): bool
    {
        return $this->slug !== null;
    }
}
