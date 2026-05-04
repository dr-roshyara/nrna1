<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\AssignmentDTO;
use App\Contexts\Membership\Application\Committee\DTOs\CommitteeDashboardDTO;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use Illuminate\Support\Facades\DB;

final class GetCommitteeDashboard
{
    public function execute(CommitteeId $id, TenantId $tenantId): CommitteeDashboardDTO
    {
        // CQRS read: Direct DB query for committee info (no aggregate loading)
        $committee = DB::table('committees')
            ->where('organisation_id', $tenantId->value())
            ->where('id', $id->value())
            ->first();

        if (!$committee) {
            throw new CommitteeNotFoundException($id);
        }

        // Load active assignments with member names
        $assignments = DB::table('committee_assignments')
            ->where('committee_id', $id->value())
            ->where('is_active', true)
            ->get()
            ->map(function ($assignment) {
                return new AssignmentDTO(
                    id: $assignment->id,
                    memberId: $assignment->member_id,
                    memberName: null, // Member name would require join to members table if needed
                    roleLabel: RolePath::fromString($assignment->role_path)->label(),
                    rolePath: $assignment->role_path,
                    joinedDate: new DateTimeImmutable($assignment->joined_date),
                );
            })
            ->all();

        $level = match ($committee->type) {
            'central'  => 1,
            'province' => 2,
            'district' => 3,
            'ward'     => 4,
            default    => 5,
        };

        return new CommitteeDashboardDTO(
            id: $committee->id,
            name: $committee->name,
            code: $committee->code,
            type: $committee->type,
            level: $level,
            geoReference: $committee->operational_geo_reference,
            status: $committee->status,
            assignments: $assignments,
        );
    }
}
