<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Application\Membership\Ports\CommitteeAssociationRepositoryPort;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\Exceptions\DuplicateActiveAssociationException;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidAssociationTransitionException;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Repositories\MemberRepositoryInterface;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DomainException;

final class CommitteeAssociationLifecyclePolicy
{
    private const ALLOWED_TRANSITIONS = [
        'active' => ['suspended', 'terminated'],
        'suspended' => ['active', 'terminated'],
        'terminated' => [],
    ];

    public function __construct(
        private readonly CommitteeAssociationRepositoryPort $repository,
        private readonly MemberRepositoryInterface $memberRepository,
    ) {}

    public function assertCanCreate(
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
    ): void {
        // Guard 1: Member MUST exist before committee assignment (critical invariant)
        $member = $this->memberRepository->find($memberId, $tenantId);
        if (!$member) {
            throw new DomainException(
                "Cannot assign member to committee: Member does not exist. " .
                "Member ID: {$memberId->value()}. " .
                "Create Member record first via proper provisioning flow."
            );
        }

        // Rule 1: No duplicate ACTIVE associations
        // (member can have at most one ACTIVE association per committee)
        $actives = $this->repository->findActiveByMemberForTenant($memberId, $tenantId);
        $duplicate = collect($actives)->first(
            fn($assoc) => $assoc->committeeId->value() === $committeeId->value()
        );

        if ($duplicate !== null) {
            throw DuplicateActiveAssociationException::forMemberAndCommittee(
                $memberId->value(),
                $committeeId->value(),
            );
        }

        // Rule 2: Cannot transition TERMINATED → ACTIVE directly
        // (TERMINATED is terminal; reapplication must go through application process)
        // But this check is complex: reapplication ALSO creates new associations
        // The distinction is enforcement at the APPLICATION layer, not repository layer
        // For now, this is a future refinement
    }

    public function assertCanTransition(
        CommitteeAssociation $existing,
        MembershipStatus $newStatus,
    ): void {
        $from = $existing->status->value;
        $to = $newStatus->value;

        $allowed = self::ALLOWED_TRANSITIONS[$from] ?? [];

        if (!in_array($to, $allowed, true)) {
            throw InvalidAssociationTransitionException::transition($from, $to);
        }
    }
}
