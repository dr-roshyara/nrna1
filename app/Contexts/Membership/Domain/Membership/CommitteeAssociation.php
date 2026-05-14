<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;

final readonly class CommitteeAssociation
{
    public function __construct(
        public AssociationId $associationId,
        public MemberId $memberId,
        public CommitteeId $committeeId,
        public ApplicationReason $associationType,
        public \DateTimeImmutable $associatedAt,
        public MembershipStatus $status,
        public ?string $actorId = null,
        public ?string $transitionReason = null,
        public ?\DateTimeImmutable $transitionedAt = null,
    ) {
    }

    /**
     * Create new association with generated identity
     */
    public static function create(
        MemberId $memberId,
        CommitteeId $committeeId,
        ApplicationReason $associationType,
        \DateTimeImmutable $associatedAt,
        MembershipStatus $status,
    ): self {
        return new self(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: $associationType,
            associatedAt: $associatedAt,
            status: $status,
        );
    }

    /**
     * Suspend membership with institutional justification
     * Returns new instance (immutable)
     */
    public function suspend(string $actorId, string $reason, \DateTimeImmutable $at): self
    {
        if (!$this->status->equals(MembershipStatus::ACTIVE)) {
            throw Exceptions\InvalidAssociationTransitionException::cannotSuspendFrom($this->status);
        }

        return new self(
            associationId: $this->associationId,
            memberId: $this->memberId,
            committeeId: $this->committeeId,
            associationType: $this->associationType,
            associatedAt: $this->associatedAt,
            status: MembershipStatus::SUSPENDED,
            actorId: $actorId,
            transitionReason: $reason,
            transitionedAt: $at,
        );
    }

    /**
     * Restore suspended membership
     * Returns new instance (immutable)
     */
    public function restore(string $actorId, \DateTimeImmutable $at): self
    {
        if (!$this->status->equals(MembershipStatus::SUSPENDED)) {
            throw Exceptions\InvalidAssociationTransitionException::cannotRestoreFrom($this->status);
        }

        return new self(
            associationId: $this->associationId,
            memberId: $this->memberId,
            committeeId: $this->committeeId,
            associationType: $this->associationType,
            associatedAt: $this->associatedAt,
            status: MembershipStatus::ACTIVE,
            actorId: $actorId,
            transitionReason: null,
            transitionedAt: $at,
        );
    }

    /**
     * Terminate membership permanently
     * Returns new instance (immutable)
     */
    public function terminate(string $actorId, string $reason, \DateTimeImmutable $at): self
    {
        if ($this->status->equals(MembershipStatus::TERMINATED)) {
            throw Exceptions\InvalidAssociationTransitionException::cannotTerminateFrom($this->status);
        }

        return new self(
            associationId: $this->associationId,
            memberId: $this->memberId,
            committeeId: $this->committeeId,
            associationType: $this->associationType,
            associatedAt: $this->associatedAt,
            status: MembershipStatus::TERMINATED,
            actorId: $actorId,
            transitionReason: $reason,
            transitionedAt: $at,
        );
    }
}
