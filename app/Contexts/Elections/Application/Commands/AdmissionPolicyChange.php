<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;

/**
 * AdmissionPolicyChange — result of ChangeVoterAdmissionPolicyHandler.
 *
 * `changed = false` means the proposal equalled the current policy: nothing was
 * written and nothing is to be audited.
 */
final readonly class AdmissionPolicyChange
{
    public function __construct(
        public VoterAdmissionPolicy $from,
        public VoterAdmissionPolicy $to,
        public bool $changed,
        public int $affectedVoters,
    ) {}
}
