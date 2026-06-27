<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Application\Membership\Ports;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

interface CommitteeAssociationRepositoryPort
{
    /**
     * Save a committee association (result of membership approval).
     */
    public function saveForTenant(CommitteeAssociation $association, TenantId $tenantId): void;

    /**
     * Find all active associations for a committee.
     *
     * @return CommitteeAssociation[]
     */
    public function findActiveByCommitteeForTenant(CommitteeId $committeeId, TenantId $tenantId): array;

    /**
     * Find all active associations for a member.
     *
     * @return CommitteeAssociation[]
     */
    public function findActiveByMemberForTenant(MemberId $memberId, TenantId $tenantId): array;

    /**
     * Find any association (all statuses) for a specific member+committee pair.
     * Used by governance policy to check for terminated associations.
     *
     * @return CommitteeAssociation|null
     */
    public function findByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?CommitteeAssociation;
}
