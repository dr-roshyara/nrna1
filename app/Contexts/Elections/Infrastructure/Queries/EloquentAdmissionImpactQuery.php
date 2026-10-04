<?php

namespace App\Contexts\Elections\Infrastructure\Queries;

use App\Contexts\Elections\Application\Ports\AdmissionImpactQuery;
use App\Models\Election;
use Illuminate\Support\Facades\DB;

/**
 * EloquentAdmissionImpactQuery — registered voters an admission-policy change
 * affects (PBDIGIT-213 C2, ADR_20261004_1549 R3-2; v1: none ↔ identity verification).
 *
 * Same scope rules as EloquentVotingEntitlementQuery: the organisation comes from
 * the election, never from ambient context; soft-deleted memberships do not count.
 */
final class EloquentAdmissionImpactQuery implements AdmissionImpactQuery
{
    public function registeredVotersWithoutIdentityEvidence(string $electionId): int
    {
        $organisationId = Election::withoutGlobalScope('tenant')->whereKey($electionId)->value('organisation_id');

        if ($organisationId === null) {
            return 0;
        }

        return DB::table('election_memberships as m')
            ->where('m.election_id', $electionId)
            ->where('m.organisation_id', $organisationId)
            ->where('m.role', 'voter')
            ->where('m.status', 'active')
            ->whereNull('m.deleted_at')
            ->whereNotExists(function ($evidence) {
                $evidence->select(DB::raw(1))
                    ->from('voter_verifications as v')
                    ->whereColumn('v.election_id', 'm.election_id')
                    ->whereColumn('v.user_id', 'm.user_id')
                    ->where('v.status', 'active');
            })
            ->count();
    }
}
