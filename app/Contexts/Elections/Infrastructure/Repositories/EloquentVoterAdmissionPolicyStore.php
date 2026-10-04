<?php

namespace App\Contexts\Elections\Infrastructure\Repositories;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Contexts\Elections\Application\Ports\VoterAdmissionPolicyStore;
use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;
use App\Models\Election;

/**
 * EloquentVoterAdmissionPolicyStore — elections.voter_admission_policy (PBDIGIT-213 C2).
 *
 * Only the 'tenant' scope is removed: the election is addressed by id, never by
 * ambient context. Lock = ElectionLifecycle::isLocked() (ReadyForVoting onward);
 * the Election model guard refuses a locked write independently.
 */
final class EloquentVoterAdmissionPolicyStore implements VoterAdmissionPolicyStore
{
    public function policyOf(string $electionId): VoterAdmissionPolicy
    {
        return $this->election($electionId)->admissionPolicy();
    }

    public function isLocked(string $electionId): bool
    {
        return ElectionLifecycle::of($this->election($electionId))->isLocked();
    }

    public function save(string $electionId, VoterAdmissionPolicy $policy): void
    {
        $this->election($electionId)->update(['voter_admission_policy' => $policy->toPersistence()]);
    }

    private function election(string $electionId): Election
    {
        return Election::withoutGlobalScope('tenant')->findOrFail($electionId);
    }
}
