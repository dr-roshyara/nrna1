<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class InMemoryCommitteeAssociationRepository implements CommitteeAssociationRepositoryPort
{
    /** @var array<string, CommitteeAssociation> */
    private array $associations = [];

    public function saveForTenant(CommitteeAssociation $association, TenantId $tenantId): void
    {
        $key = sprintf('%s:%s:%s', $tenantId->value(), $association->memberId->value(), $association->committeeId->value());
        $this->associations[$key] = $association;
    }

    public function findActiveByCommitteeForTenant(CommitteeId $committeeId, TenantId $tenantId): array
    {
        $result = [];
        foreach ($this->associations as $association) {
            if ($association->committeeId->equals($committeeId) && $association->status->equals(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus::ACTIVE)) {
                $result[] = $association;
            }
        }
        return $result;
    }

    public function findActiveByMemberForTenant(MemberId $memberId, TenantId $tenantId): array
    {
        $result = [];
        foreach ($this->associations as $association) {
            if ($association->memberId->equals($memberId) && $association->status->equals(\App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus::ACTIVE)) {
                $result[] = $association;
            }
        }
        return $result;
    }

    public function findByMemberAndCommitteeForTenant(MemberId $memberId, CommitteeId $committeeId, TenantId $tenantId): ?CommitteeAssociation
    {
        foreach ($this->associations as $association) {
            if ($association->memberId->equals($memberId) && $association->committeeId->equals($committeeId)) {
                return $association;
            }
        }
        return null;
    }
}
