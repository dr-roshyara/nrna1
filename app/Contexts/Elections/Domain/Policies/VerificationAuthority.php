<?php

namespace App\Contexts\Elections\Domain\Policies;

use App\Contexts\Elections\Domain\ValueObjects\ElectionLeadershipRole;
use App\Contexts\Elections\Domain\ValueObjects\VerifierRole;

/**
 * VerificationAuthority — WHO may create HUMAN-produced identity evidence
 * (PBDIGIT-213, ADR_20261004_1549 D2 / §3).
 *
 * "Identity verification may be performed by the Chief or Deputy Chief of the
 * election." The leadership role is election-scoped by construction (it comes from
 * the VerificationAuthoritySource for one election); a global or organisation-wide
 * role never reaches this policy. Authority is separate from the admission policy
 * (WHAT is required) and is not a property of every future requirement.
 */
final class VerificationAuthority
{
    public function roleFor(?ElectionLeadershipRole $leadership): ?VerifierRole
    {
        return match ($leadership) {
            ElectionLeadershipRole::Chief       => VerifierRole::ElectionChief,
            ElectionLeadershipRole::DeputyChief => VerifierRole::ElectionDeputyChief,
            null                                => null,
        };
    }
}
