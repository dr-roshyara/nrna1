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

    /**
     * The "Verify All" set (PBDIGIT-213 C4-D1): voter role, status ACTIVE, not deleted,
     * and NO evidence row at all for this election — never verified. Voters who were
     * verified before, or whose evidence was revoked (incl. after a confirmed
     * suspension), are excluded: they are handled individually.
     *
     * @return list<string> user ids, deterministic order
     */
    public function bulkVerifiableVoterIds(string $electionId): array;
}
