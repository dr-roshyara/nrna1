<?php

namespace App\Contexts\Elections\Application\Handlers;

use App\Contexts\Elections\Application\Commands\AdmissionPolicyChange;
use App\Contexts\Elections\Application\Commands\ChangeVoterAdmissionPolicyCommand;
use App\Contexts\Elections\Application\Ports\AdmissionImpactQuery;
use App\Contexts\Elections\Application\Ports\VoterAdmissionPolicyStore;
use App\Contexts\Elections\Domain\Exceptions\AdmissionPolicyLocked;

/**
 * ChangeVoterAdmissionPolicyHandler — change an election's voter admission policy
 * (PBDIGIT-213 C2, ADR_20261004_1549 D4 / R3-1 / R3-2).
 *
 * 1. An identical proposal is a no-op (nothing written, nothing to audit) — even
 *    when the election is locked.
 * 2. A real change is refused once the election is locked (ReadyForVoting onward).
 *    The Election model guard enforces the same invariant as defence in depth.
 * 3. The affected-voter count is taken before saving.
 * 4. No ElectionMembership is written: admission is derived from policy + evidence.
 *
 * Authorization (election-scoped Chief or Deputy) is enforced at the HTTP boundary.
 */
final class ChangeVoterAdmissionPolicyHandler
{
    public function __construct(
        private readonly VoterAdmissionPolicyStore $store,
        private readonly AdmissionImpactQuery $impact,
    ) {}

    public function handle(ChangeVoterAdmissionPolicyCommand $command): AdmissionPolicyChange
    {
        $current = $this->store->policyOf($command->electionId);

        if ($command->proposed->equals($current)) {
            return new AdmissionPolicyChange($current, $current, changed: false, affectedVoters: 0);
        }

        if ($this->store->isLocked($command->electionId)) {
            throw AdmissionPolicyLocked::forElection($command->electionId);
        }

        $affected = $this->impact->registeredVotersWithoutIdentityEvidence($command->electionId);

        $this->store->save($command->electionId, $command->proposed);

        return new AdmissionPolicyChange($current, $command->proposed, changed: true, affectedVoters: $affected);
    }
}
