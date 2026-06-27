<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Infrastructure\Repositories;

use App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociation;
use App\Contexts\Membership\Domain\Membership\CommitteeAssociationLifecyclePolicy;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Infrastructure\Models\CommitteeAssociationModel;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class EloquentCommitteeAssociationRepository implements CommitteeAssociationRepositoryPort
{
    private ?CommitteeAssociationLifecyclePolicy $lifecyclePolicy = null;

    public function setLifecyclePolicy(CommitteeAssociationLifecyclePolicy $policy): void
    {
        $this->lifecyclePolicy = $policy;
    }

    public function saveForTenant(CommitteeAssociation $association, TenantId $tenantId): void
    {
        // Lazy load policy to avoid circular dependency
        if ($this->lifecyclePolicy === null) {
            throw new \RuntimeException('CommitteeAssociationLifecyclePolicy not initialized');
        }

        $this->lifecyclePolicy->assertCanCreate(
            $association->memberId,
            $association->committeeId,
            $tenantId,
        );

        CommitteeAssociationModel::create([
            'id' => \Illuminate\Support\Str::uuid(),
            'association_id' => $association->associationId->value(),
            'organisation_id' => $tenantId->value(),
            'member_id' => $association->memberId->value(),
            'committee_id' => $association->committeeId->value(),
            'association_type' => $association->associationType->value,
            'status' => $association->status->value,
            'associated_at' => $association->associatedAt,
        ]);
    }

    public function findActiveByCommitteeForTenant(CommitteeId $committeeId, TenantId $tenantId): array
    {
        return CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('committee_id', $committeeId->value())
            ->where('status', 'active')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function findActiveByMemberForTenant(MemberId $memberId, TenantId $tenantId): array
    {
        return CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->where('status', 'active')
            ->get()
            ->map(fn($model) => $this->toDomain($model))
            ->toArray();
    }

    public function findByMemberAndCommitteeForTenant(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): ?CommitteeAssociation {
        $model = CommitteeAssociationModel::where('organisation_id', $tenantId->value())
            ->where('member_id', $memberId->value())
            ->where('committee_id', $committeeId->value())
            ->first();

        return $model ? $this->toDomain($model) : null;
    }

    private function toDomain(CommitteeAssociationModel $model): CommitteeAssociation
    {
        return new CommitteeAssociation(
            associationId: AssociationId::fromString($model->association_id),
            memberId: MemberId::fromString($model->member_id),
            committeeId: CommitteeId::fromString($model->committee_id),
            associationType: ApplicationReason::from($model->association_type),
            associatedAt: new \DateTimeImmutable($model->associated_at->format('Y-m-d H:i:s')),
            status: MembershipStatus::from($model->status),
        );
    }
}
