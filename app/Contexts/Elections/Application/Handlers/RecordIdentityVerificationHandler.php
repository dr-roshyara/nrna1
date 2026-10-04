<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\IdentityVerificationRecorded;
use App\Contexts\Elections\Application\Commands\RecordIdentityVerificationCommand;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceRecord;
use App\Contexts\Elections\Application\Ports\IdentityEvidenceStore;
use App\Contexts\Elections\Application\Ports\VerificationAuthoritySource;
use App\Contexts\Elections\Application\Ports\VoterRegistrationQuery;
use App\Contexts\Elections\Domain\Exceptions\VerifierNotAuthorised;
use App\Contexts\Elections\Domain\Exceptions\VoterNotVerifiable;
use App\Contexts\Elections\Domain\Policies\VerificationAuthority;

/**
 * RecordIdentityVerificationHandler — create OFFICER_IDENTITY_VERIFICATION evidence
 * (PBDIGIT-213 C3.2/C3.3; ADR_20261004_1549 D2, D3; PO decision C3-D2).
 *
 * 1. Target: a registered voter of this election (voter role, not deleted, not removed).
 * 2. Actor: the Chief or Deputy Chief of this election — the role is derived from the
 *    election's actual leadership, never from the request.
 * 3. Evidence: made active, recording who verified, in which role, by which method.
 *
 * The verification lifecycle window (D1) is enforced at the route boundary
 * (election.state:verify_voter_identity). Verification never changes membership
 * status: it does not lift a suspension (R3-4).
 */
final class RecordIdentityVerificationHandler
{
    public function __construct(
        private readonly VoterRegistrationQuery $registry,
        private readonly VerificationAuthoritySource $leadership,
        private readonly VerificationAuthority $authority,
        private readonly IdentityEvidenceStore $evidence,
    ) {}

    public function handle(RecordIdentityVerificationCommand $command): IdentityVerificationRecorded
    {
        if (! $this->registry->isVerifiableVoter($command->voterId, $command->electionId)) {
            throw VoterNotVerifiable::inElection($command->voterId, $command->electionId);
        }

        $role = $this->authority->roleFor(
            $this->leadership->leadershipRoleOf($command->verifierId, $command->electionId)
        ) ?? throw VerifierNotAuthorised::forElection($command->verifierId, $command->electionId);

        $verificationId = $this->evidence->recordActive(new IdentityEvidenceRecord(
            electionId: $command->electionId,
            voterId: $command->voterId,
            verifierId: $command->verifierId,
            verifierRole: $role,
            verifiedIp: $command->verifiedIp,
            fingerprintHash: $command->fingerprintHash,
            deviceComponents: $command->deviceComponents,
            notes: $command->notes,
        ));

        return new IdentityVerificationRecorded($verificationId, $role);
    }
}
