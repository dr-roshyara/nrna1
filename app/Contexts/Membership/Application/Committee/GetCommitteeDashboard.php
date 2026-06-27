<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Committee\Application\ReadModel\CommitteeMembershipReadModelAdapter;
use App\Contexts\Membership\Application\Committee\DTOs\CommitteeDashboardDTO;
use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class GetCommitteeDashboard
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committeeRepository,
        private readonly MembershipLineageRepositoryPort $membershipLineageRepository,
        private readonly CommitteeMembershipReadModelAdapter $membershipReadModel,
    ) {}

    public function execute(CommitteeId $id, TenantId $tenantId): CommitteeDashboardDTO
    {
        // Load committee via repository (not DB::table)
        $committee = $this->committeeRepository->findById($id);

        if (!$committee) {
            throw new CommitteeNotFoundException($id);
        }

        // Load active members via read model adapter
        $members = $this->membershipReadModel->getActiveMembers($id);

        $level = $committee->levelIndex() ?? 5;

        return new CommitteeDashboardDTO(
            id: (string) $committee->getId(),
            name: $committee->getName()->value(),
            code: $committee->code(),
            type: (string) $committee->getType(),
            level: $level,
            geoReference: $committee->getGeoUnitId(),
            status: (string) $committee->getStatus(),
            members: $members,
        );
    }
}
