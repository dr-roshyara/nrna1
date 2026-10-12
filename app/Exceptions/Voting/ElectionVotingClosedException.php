<?php

namespace App\Exceptions\Voting;

/**
 * Voting in the election has closed (PBDIGIT-274 S7, plan decision O-2). Kept apart from an expired voting
 * session: a closed election offers no restart. Web requests are sent to the election's page with the closed
 * message (bootstrap/app.php); JSON gets 403.
 */
class ElectionVotingClosedException extends VotingException
{
    public function getUserMessage(): string
    {
        return 'Voting in this election is closed.';
    }

    public function getHttpCode(): int
    {
        return 403;
    }
}
