<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\DTOs\AssignmentDTO;
use App\Contexts\Membership\Application\Committee\DTOs\CommitteeDashboardDTO;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\User;
use DateTimeImmutable;

final class GetCommitteeDashboard
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committeeRepository,
        private readonly MembershipLineageRepositoryPort $membershipLineageRepository,
    ) {}

    public function execute(CommitteeId $id, TenantId $tenantId): CommitteeDashboardDTO
    {
        // Load committee via repository (not DB::table)
        $committee = $this->committeeRepository->findById($id);

        if (!$committee) {
            throw new CommitteeNotFoundException($id);
        }

        // Load all active lineages for this committee
        $lineages = $this->membershipLineageRepository->findActiveLineagesByCommitteeForTenant(
            $id,
            $tenantId
        );

        // Map lineages to AssignmentDTOs with member names resolved
        $assignments = array_map(function ($lineage) {
            $currentEpisode = $lineage->current();
            $user = User::find($currentEpisode->memberId->value());
            $memberName = $user?->name ?? 'Unknown Member';

            // TODO: Get actual role from application data - for now use default
            $roleLabel = 'Member';
            $rolePath = 'member';

            return new AssignmentDTO(
                id: $lineage->lineageId->value(),
                memberId: $currentEpisode->memberId->value(),
                memberName: $memberName,
                roleLabel: $roleLabel,
                rolePath: $rolePath,
                joinedDate: $currentEpisode->associatedAt,
            );
        }, $lineages);

        $level = $committee->levelIndex() ?? 5;

        return new CommitteeDashboardDTO(
            id: (string) $committee->getId(),
            name: $committee->getName()->value(),
            code: $committee->code(),
            type: (string) $committee->getType(),
            level: $level,
            geoReference: $committee->getGeoUnitId(),
            status: (string) $committee->getStatus(),
            assignments: $assignments,
        );
    }
}
