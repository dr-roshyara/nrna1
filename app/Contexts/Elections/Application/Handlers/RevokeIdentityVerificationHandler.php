<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\RevokeIdentityVerificationCommand;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceStore;
use App\Contexts\Elections\Application\Ports\VerificationAuthoritySource;
use App\Contexts\Elections\Domain\Exceptions\VerifierNotAuthorised;
use App\Contexts\Elections\Domain\Policies\VerificationAuthority;

/**
 * RevokeIdentityVerificationHandler — end the CURRENT admission effect of a voter's
 * identity evidence (PBDIGIT-213 C3.4; ADR_20261004_1549 D5, §2.2 inv. 9).
 *
 * Used for a confirmed two-person suspension (reason suspension_confirmed) and for a
 * direct revocation by the election's leadership (revoked_by_officer). The historical
 * fact "X verified Y at T" stays; only the current effect ends. Returns the evidence
 * id, or null when no active evidence existed.
 */
final class RevokeIdentityVerificationHandler
{
    public function __construct(
        private readonly VerificationAuthoritySource $leadership,
        private readonly VerificationAuthority $authority,
        private readonly IdentityEvidenceStore $evidence,
    ) {}

    public function handle(RevokeIdentityVerificationCommand $command): ?string
    {
        if ($this->authority->roleFor($this->leadership->leadershipRoleOf($command->revokedBy, $command->electionId)) === null) {
            throw VerifierNotAuthorised::forElection($command->revokedBy, $command->electionId);
        }

        return $this->evidence->revokeActive($command->electionId, $command->voterId, $command->revokedBy, $command->reason);
    }
}
