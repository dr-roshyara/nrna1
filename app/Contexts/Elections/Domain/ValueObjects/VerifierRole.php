<?php

namespace App\Contexts\Elections\Domain\ValueObjects;

/**
 * VerifierRole — the role in which a human verifier created identity evidence
 * (PBDIGIT-213, ADR_20261004_1549 D3). Recorded on the evidence as `verifier_role`.
 */
enum VerifierRole: string
{
    case ElectionChief       = 'ELECTION_CHIEF';
    case ElectionDeputyChief = 'ELECTION_DEPUTY_CHIEF';
}
