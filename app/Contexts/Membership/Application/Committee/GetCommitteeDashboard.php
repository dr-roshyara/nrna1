<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Committee;

use App\Contexts\Membership\Application\Committee\Views\CommitteeDashboardView;
use App\Contexts\Membership\Domain\Exceptions\CommitteeNotFoundException;
use App\Contexts\Membership\Domain\Repositories\CommitteeRepositoryInterface;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class GetCommitteeDashboard
{
    public function __construct(
        private readonly CommitteeRepositoryInterface $committees
    ) {}

    public function execute(CommitteeId $id, TenantId $tenantId): CommitteeDashboardView
    {
        $committee = $this->committees->findForTenant($id, $tenantId);

        if ($committee === null) {
            throw new CommitteeNotFoundException($id);
        }

        $subCommittees = [];

        if ($committee->getOperationalGeoReference() !== null) {
            $subCommittees = $this->committees->findByGeographyForTenant(
                $committee->getOperationalGeoReference(),
                $tenantId
            );
        }

        return new CommitteeDashboardView($committee, $subCommittees);
    }
}
