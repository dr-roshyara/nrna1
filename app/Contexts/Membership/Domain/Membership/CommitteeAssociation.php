<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipConstructionException;
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
        if ($status->equals(MembershipStatus::SUSPENDED)) {
            if ($actorId === null) {
                throw InvalidMembershipConstructionException::suspendedRequiresActorId();
            }
            if ($transitionReason === null) {
                throw InvalidMembershipConstructionException::suspendedRequiresTransitionReason();
            }
            if ($transitionedAt === null) {
                throw InvalidMembershipConstructionException::suspendedRequiresTimestamp();
            }
        }

        if ($status->equals(MembershipStatus::TERMINATED)) {
            if ($actorId === null) {
                throw InvalidMembershipConstructionException::terminatedRequiresActorId();
            }
            if ($transitionReason === null) {
                throw InvalidMembershipConstructionException::terminatedRequiresTransitionReason();
            }
            if ($transitionedAt === null) {
                throw InvalidMembershipConstructionException::terminatedRequiresTimestamp();
            }
        }
    }

    /**
     * Create new association with generated identity.
     * New constitutional relationships are always ACTIVE.
     */
    public static function create(
        MemberId $memberId,
        CommitteeId $committeeId,
        ApplicationReason $associationType,
        \DateTimeImmutable $associatedAt,
    ): self {
        return new self(
            associationId: AssociationId::generate(),
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: $associationType,
            associatedAt: $associatedAt,
            status: MembershipStatus::ACTIVE,
        );
    }

    /**
     * Reconstitute an episode from database persistence.
     *
     * Explicit rehydration path — semantically distinct from new creation.
     * Applies the same construction invariants: the constitutional system
     * rejects invalid DB state as a hard error.
     */
    public static function rehydrate(
        AssociationId $associationId,
        MemberId $memberId,
        CommitteeId $committeeId,
        ApplicationReason $associationType,
        \DateTimeImmutable $associatedAt,
        MembershipStatus $status,
        ?string $actorId = null,
        ?string $transitionReason = null,
        ?\DateTimeImmutable $transitionedAt = null,
    ): self {
        return new self(
            associationId: $associationId,
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: $associationType,
            associatedAt: $associatedAt,
            status: $status,
            actorId: $actorId,
            transitionReason: $transitionReason,
            transitionedAt: $transitionedAt,
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
