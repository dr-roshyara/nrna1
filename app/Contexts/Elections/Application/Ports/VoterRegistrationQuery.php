<?php

namespace App\Contexts\Elections\Application\Ports;

/**
 * VoterRegistrationQuery — is this user a verifiable registered voter of the election?
 *
 * Verifiable = voter role, not soft-deleted, status ≠ removed (PO decision C3-D2).
 * Active, invited and inactive (suspended) voters qualify, so re-verification can
 * happen before or after reinstatement (R3-4).
 */
interface VoterRegistrationQuery
{
    public function isVerifiableVoter(string $userId, string $electionId): bool;
}
