<?php

namespace App\Contexts\Elections\Application\Commands;

use App\Contexts\Elections\Domain\ValueObjects\VoterAdmissionPolicy;

/**
 * ChangeVoterAdmissionPolicyCommand — the Chief or Deputy proposes a new voter
 * admission policy for one election (PBDIGIT-213 C2). Pure DTO.
 */
final readonly class ChangeVoterAdmissionPolicyCommand
{
    public function __construct(
        public string $electionId,
        public VoterAdmissionPolicy $proposed,
        public string $actorId,
    ) {}
}
