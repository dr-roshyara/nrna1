<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

/**
 * The voter admission policy cannot change once the election is locked
 * (ReadyForVoting onward — ADR_20261004_1549 D4).
 */
final class AdmissionPolicyLocked extends DomainException
{
    public static function forElection(string $electionId): self
    {
        return new self("The voter admission policy of election [{$electionId}] is locked.");
    }
}
