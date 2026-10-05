<?php

namespace App\Contexts\Elections\Application\DTO;

use App\Contexts\Elections\Domain\ValueObjects\SecurityVerdict;

/**
 * EntryAttempt — the facts of one attempt to enter or continue the voting process (PBDIGIT-239).
 *
 * Input of VotingAuthorization::forEntry(). `security` is the E2 fact supplied by the one Security
 * Verdict Authority (VotingSecurityVerdictQuery); callers never manufacture it.
 */
final readonly class EntryAttempt
{
    public function __construct(
        public string $voterId,
        public string $electionId,
        public SecurityVerdict $security,
    ) {}
}
