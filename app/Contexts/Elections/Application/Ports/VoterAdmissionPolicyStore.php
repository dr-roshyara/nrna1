<?php

namespace App\Contexts\Elections\Application\Ports;

use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;

/**
 * VoterAdmissionPolicyStore — reads and persists an election's voter admission
 * policy and reports whether the election is locked for policy changes
 * (ADR_20261004_1549 D4: locked from ReadyForVoting onward).
 */
interface VoterAdmissionPolicyStore
{
    public function policyOf(string $electionId): VoterAdmissionPolicy;

    public function isLocked(string $electionId): bool;

    public function save(string $electionId, VoterAdmissionPolicy $policy): void;
}
