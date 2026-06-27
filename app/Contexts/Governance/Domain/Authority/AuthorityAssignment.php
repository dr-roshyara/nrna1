<?php

declare(strict_types=1);

namespace App\Contexts\Governance\Domain\Authority;

use App\Contexts\Governance\Domain\Authority\Events\AuthorityDelegated;
use App\Contexts\Governance\Domain\Authority\Events\AuthorityRevoked;
use App\Contexts\Governance\Domain\Authority\Policies\DelegationLifecyclePolicy;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityAssignmentId;
use App\Contexts\Governance\Domain\Authority\ValueObjects\AuthorityEffectivePeriod;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationScope;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationStatus;
use App\Contexts\Governance\Domain\Authority\ValueObjects\DelegationType;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;

final class AuthorityAssignment
{
    private DelegationStatus $status;
    private array $events = [];

    private function __construct(
        private readonly AuthorityAssignmentId $id,
        private readonly TenantId $tenantId,
        private readonly CommitteeId $fromCommitteeId,
        private readonly CommitteeId $toCommitteeId,
        private readonly DelegationType $type,
        private readonly DelegationScope $scope,
        private readonly AuthorityEffectivePeriod $validity,
        private readonly MemberId $delegatedBy,
        private readonly DateTimeImmutable $delegatedAt,
    ) {
        $this->status = DelegationStatus::ACTIVE;
    }

    public static function delegate(
        AuthorityAssignmentId $id,
        TenantId $tenantId,
        CommitteeId $fromCommitteeId,
        CommitteeId $toCommitteeId,
        DelegationType $type,
        DelegationScope $scope,
        AuthorityEffectivePeriod $validity,
        MemberId $delegatedBy,
        DateTimeImmutable $delegatedAt,
        DateTimeImmutable $now,
        bool $delegatorHasActiveAuthority,
    ): self {
        // INV-A01: cannot delegate to self
        if ($fromCommitteeId->equals($toCommitteeId)) {
            throw new \DomainException('A committee cannot delegate authority to itself');
        }

        // INV-A04: OVERRIDE requires delegator to hold active authority
        if ($type === DelegationType::OVERRIDE && !$delegatorHasActiveAuthority) {
            throw new \DomainException(
                'OVERRIDE delegation requires the delegating committee to hold an active authority assignment'
            );
        }

        // INV-A05: delegatedAt must not be in the future
        if ($delegatedAt > $now) {
            throw new \DomainException('Delegation cannot be dated in the future');
        }

        $assignment = new self(
            $id,
            $tenantId,
            $fromCommitteeId,
            $toCommitteeId,
            $type,
            $scope,
            $validity,
            $delegatedBy,
            $delegatedAt,
        );

        $assignment->events[] = AuthorityDelegated::from(
            assignmentId: $id,
            tenantId: $tenantId,
            fromCommitteeId: $fromCommitteeId,
            toCommitteeId: $toCommitteeId,
            type: $type,
            scope: $scope,
            validFrom: $validity->start(),
            validUntil: $validity->end(),
            delegatedAt: $delegatedAt,
        );

        return $assignment;
    }

    public function revoke(MemberId $revokedBy, string $reason, DateTimeImmutable $revokedAt): void
    {
        $policy = new DelegationLifecyclePolicy();

        if (!$policy->canTransition($this->status, DelegationStatus::REVOKED)) {
            throw new \DomainException(
                'Cannot revoke: delegation is already in a terminal state (REVOKED)'
            );
        }

        $this->status = DelegationStatus::REVOKED;

        $this->events[] = AuthorityRevoked::from(
            assignmentId: $this->id,
            tenantId: $this->tenantId,
            revokedBy: $revokedBy,
            reason: $reason,
            revokedAt: $revokedAt,
        );
    }

    public function isActive(): bool
    {
        return $this->status === DelegationStatus::ACTIVE;
    }

    public function isActiveAt(DateTimeImmutable $at): bool
    {
        return $this->status === DelegationStatus::ACTIVE
            && $this->validity->isActive($at);
    }

    public function releaseEvents(): array
    {
        $released = $this->events;
        $this->events = [];

        return $released;
    }

    public function id(): AuthorityAssignmentId
    {
        return $this->id;
    }

    public function tenantId(): TenantId
    {
        return $this->tenantId;
    }

    public function fromCommitteeId(): CommitteeId
    {
        return $this->fromCommitteeId;
    }

    public function toCommitteeId(): CommitteeId
    {
        return $this->toCommitteeId;
    }

    public function type(): DelegationType
    {
        return $this->type;
    }

    public function scope(): DelegationScope
    {
        return $this->scope;
    }

    public function validity(): AuthorityEffectivePeriod
    {
        return $this->validity;
    }

    public function status(): DelegationStatus
    {
        return $this->status;
    }

    public function delegatedBy(): MemberId
    {
        return $this->delegatedBy;
    }

    public function delegatedAt(): DateTimeImmutable
    {
        return $this->delegatedAt;
    }
}
