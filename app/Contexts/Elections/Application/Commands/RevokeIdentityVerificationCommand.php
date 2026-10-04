<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Contexts\Elections\Domain\ValueObjects\RevocationReason;

/**
 * RevokeIdentityVerificationCommand — end the CURRENT admission effect of a voter's
 * identity evidence (PBDIGIT-213 C3; ADR_20261004_1549 D5). Pure DTO.
 */
final readonly class RevokeIdentityVerificationCommand
{
    public function __construct(
        public string $electionId,
        public string $voterId,
        public string $revokedBy,
        public RevocationReason $reason,
    ) {}
}
