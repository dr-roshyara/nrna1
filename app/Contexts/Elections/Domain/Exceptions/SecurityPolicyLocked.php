<?php

namespace App\Contexts\Elections\Domain\Exceptions;

use DomainException;

/**
 * The election's security policy cannot change once the election is locked
 * (ReadyForVoting onward — ADR_20261005_0950 I9, PBDIGIT-244).
 */
final class SecurityPolicyLocked extends DomainException
{
    public static function forElection(string $electionId): self
    {
        return new self("The security policy of election [{$electionId}] is locked.");
    }
}
