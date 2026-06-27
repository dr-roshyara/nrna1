<?php

declare(strict_types=1);

namespace Tests\Doubles;

use App\Contexts\Membership\Application\Membership\Ports\MembershipLineageRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\MembershipLineage;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class InMemoryMembershipLineageRepository implements MembershipLineageRepositoryPort
{
    /** @var array<string, MembershipLineage> */
    private array $lineages = [];

    public function saveForTenant(MembershipLineage $lineage, TenantId $tenantId): void
    {
        $key = sprintf('%s:%s', $tenantId->value(), $lineage->lineageId->value());
        $this->lineages[$key] = $lineage;
    }

    public function findByLineageIdForTenant(LineageId $lineageId, TenantId $tenantId): ?MembershipLineage
    {
        $key = sprintf('%s:%s', $tenantId->value(), $lineageId->value());
        return $this->lineages[$key] ?? null;
    }

    public function findActiveByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage {
        foreach ($this->lineages as $lineage) {
            if (
                $lineage->tenantId->equals($tenantId)
                && $lineage->memberId->equals($memberId)
                && $lineage->committeeId->equals($committeeId)
                && $lineage->currentStatus()->value === 'active'
            ) {
                return $lineage;
            }
        }

        return null;
    }

    public function findByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage {
        foreach ($this->lineages as $lineage) {
            if (
                $lineage->tenantId->equals($tenantId)
                && $lineage->memberId->equals($memberId)
                && $lineage->committeeId->equals($committeeId)
            ) {
                return $lineage;
            }
        }

        return null;
    }

    public function findAllByMemberForTenant(MemberId $memberId, TenantId $tenantId): array
    {
        $result = [];

        foreach ($this->lineages as $lineage) {
            if ($lineage->tenantId->equals($tenantId) && $lineage->memberId->equals($memberId)) {
                $result[] = $lineage;
            }
        }

        return $result;
    }

    public function findActiveLineagesByMemberForTenant(MemberId $memberId, TenantId $tenantId): array
    {
        $result = [];

        foreach ($this->lineages as $lineage) {
            if (
                $lineage->tenantId->equals($tenantId)
                && $lineage->memberId->equals($memberId)
                && $lineage->isActive()
            ) {
                $result[] = $lineage;
            }
        }

        return $result;
    }

    public function findActiveLineagesByCommitteeForTenant(CommitteeId $committeeId, TenantId $tenantId): array
    {
        $result = [];

        foreach ($this->lineages as $lineage) {
            if (
                $lineage->tenantId->equals($tenantId)
                && $lineage->committeeId->equals($committeeId)
                && $lineage->isActive()
            ) {
                $result[] = $lineage;
            }
        }

        return $result;
    }

    public function findLineageByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?MembershipLineage {
        foreach ($this->lineages as $lineage) {
            if (
                $lineage->tenantId->equals($tenantId)
                && $lineage->memberId->equals($memberId)
                && $lineage->committeeId->equals($committeeId)
            ) {
                return $lineage;
            }
        }

        return null;
    }
}
