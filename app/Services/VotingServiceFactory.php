<?php

namespace App\Services;

use App\Models\Election;

/**
 * VotingServiceFactory
 *
 * Factory pattern for voting services.
 * Returns appropriate voting service based on election type (demo or real).
 * Both services share identical voting logic but operate on different models.
 */
class VotingServiceFactory
{
    /**
     * Create appropriate voting service for an election
     *
     * @param \App\Models\Election $election
     * @return \App\Services\VotingService
     */
    public static function make(Election $election): VotingService
    {
        if ($election->isDemo()) {
            return new DemoVotingService($election);
        }

        return new RealVotingService($election);
    }

    /**
     * Create service from session-selected election
     * Used when election is stored in session
     *
     * @return \App\Services\VotingService|null
     */
    public static function makeFromSession(): ?VotingService
    {
        $electionId = session('selected_election_id');

        if (!$electionId) {
            return null;
        }

        $election = Election::find($electionId);

        if (!$election) {
            return null;
        }

        return self::make($election);
    }
}
