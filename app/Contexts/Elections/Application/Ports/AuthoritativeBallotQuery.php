<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Domain\Ballot\AuthoritativeBallot;

/**
 * AuthoritativeBallotQuery — obtains the authoritative ballot for one CastContext (voter + election)
 * (VE-01, ADR_20261005_0950 rev 3 §6). The source of the authoritative ballot only: it owns no
 * ballot-validity rule (validation stays in BallotValidator) and duplicates no assembly rule.
 */
interface AuthoritativeBallotQuery
{
    public function ballotFor(string $voterId, string $electionId): AuthoritativeBallot;
}
