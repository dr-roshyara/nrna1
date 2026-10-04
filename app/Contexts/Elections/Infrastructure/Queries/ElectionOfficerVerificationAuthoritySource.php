<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Contexts\Elections\Application\Ports\VerificationAuthoritySource;
use App\Contexts\Elections\Domain\ValueObjects\ElectionLeadershipRole;
use App\Models\ElectionOfficer;

/**
 * ElectionOfficerVerificationAuthoritySource — v1 adapter (PBDIGIT-213 C3.2).
 *
 * The CURRENT TECHNICAL SOURCE of the Election Committee leadership (Chief, Deputy
 * Chief) is the election-scoped election_officers row: status active, election_id =
 * this election, role chief → Chief, deputy → DeputyChief. Commissioners and
 * organisation-wide rows (election_id NULL) grant nothing.
 *
 * This is NOT the Election Committee model (gap G10) and NOT the acceptance
 * Committee (EM-GOV-029); when that model exists only this adapter changes
 * (ADR_20261004_1549 §3).
 */
final class ElectionOfficerVerificationAuthoritySource implements VerificationAuthoritySource
{
    public function leadershipRoleOf(string $userId, string $electionId): ?ElectionLeadershipRole
    {
        $role = ElectionOfficer::withoutGlobalScopes()
            ->where('user_id', $userId)
            ->where('election_id', $electionId)
            ->where('status', 'active')
            ->whereIn('role', ['chief', 'deputy'])
            ->orderByRaw("CASE role WHEN 'chief' THEN 0 ELSE 1 END")
            ->value('role');

        return match ($role) {
            'chief'  => ElectionLeadershipRole::Chief,
            'deputy' => ElectionLeadershipRole::DeputyChief,
            default  => null,
        };
    }
}
