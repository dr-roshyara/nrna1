<?php

namespace App\Services\Voting;

use App\Exceptions\Voting\VoterSlugLifetimeMisconfigured;
use Illuminate\Support\Facades\Log;

/**
 * The one reader of voting.time_in_minutes for code and voting windows (PBDIGIT-274 S6, plan section 6,
 * O-10 = B). The value is validated by the single strict rule VoterSlugLifetime::fromConfig; an invalid value
 * fails closed (503) and is logged with a reason code and type only, never the value.
 */
final class VotingDuration
{
    public static function minutes(): int
    {
        try {
            return VoterSlugLifetime::fromConfig(config('voting.time_in_minutes'))->minutes();
        } catch (VoterSlugLifetimeMisconfigured $e) {
            Log::critical('voter_slug_lifetime_misconfigured', $e->getContext());

            throw $e;
        }
    }
}
