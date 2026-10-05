<?php

namespace App\Contexts\Elections\Application\DTO;

use App\Contexts\Elections\Domain\ValueObjects\EntryAuthorizationReason;

/**
 * EntryAuthorization — the result of VotingAuthorization::forEntry(). Derived, never stored.
 *
 * `Allowed` lets the voter enter or continue the voting process now; it authorizes no cast
 * (the final cast decides again via forCast() — ADR_20261005_0950 §3.1).
 */
final readonly class EntryAuthorization
{
    public function __construct(
        public EntryAuthorizationReason $reason,
    ) {}

    public function isAllowed(): bool
    {
        return $this->reason->isAllowed();
    }
}
