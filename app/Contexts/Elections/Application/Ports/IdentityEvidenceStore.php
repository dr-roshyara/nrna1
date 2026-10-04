<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;

/**
 * IdentityEvidenceStore — persists the CURRENT admission effect of identity evidence
 * (ADR_20261004_1549 D3, §2.2 inv. 9).
 *
 * v1 keeps one row per (election, voter): recording again replaces the current fields
 * (a technical limitation, gap G1 — not protocol history). Revocation ends the
 * current effect; it never deletes the row.
 */
interface IdentityEvidenceStore
{
    /** Make the evidence active for (election, voter); returns the evidence id. */
    public function recordActive(IdentityEvidenceRecord $record): string;

    /** End the current effect of ACTIVE evidence; returns its id, or null if none was active. */
    public function revokeActive(string $electionId, string $voterId, string $revokedBy, RevocationReason $reason): ?string;
}
