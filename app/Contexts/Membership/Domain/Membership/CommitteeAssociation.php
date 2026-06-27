<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\Exceptions\InvalidMembershipConstructionException;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\AssociationId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Membership\Domain\Membership\ValueObjects\TransitionReason;
use App\Contexts\Shared\Domain\ValueObjects\ActorId;

final readonly class CommitteeAssociation
{
    public function __construct(
        public AssociationId $associationId,
        public MemberId $memberId,
        public CommitteeId $committeeId,
        public ApplicationReason $associationType,
        public \DateTimeImmutable $associatedAt,
        public MembershipStatus $status,
        public ?ActorId $actorId = null,
        public ?TransitionReason $transitionReason = null,
        public ?\DateTimeImmutable $transitionedAt = null,
    ) {
        MembershipTransitionPolicy::assertAuditRequirementsMet(
            $status,
            $actorId,
            $transitionReason,
            $transitionedAt,
        );
    }

    /**
     * Factory: Establish a new constitutional relationship (domain command path).
     *
     * TRUST BOUNDARY: Domain-Controlled Creation
     * - All inputs are domain value objects — no primitive leakage
     * - Output is ALWAYS ACTIVE (aggregate invariant enforced)
     * - Impossible to produce invalid initial state
     *
     * SAFETY GUARANTEES:
     * - Generated identity (@see AssociationId::generate())
     * - First episode always satisfies constitutional constraints
     * - All subsequent transitions must use transition methods
     *
     * INVARIANT: No audit fields set (ACTIVE doesn't require actor/reason/timestamp)
     *
     * @see MembershipLineage::establish() — uses this to create initial episode
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
     * Reconstitute an episode from database persistence (trust-restricted reconstruction).
     *
     * TRUST BOUNDARY: Persistence Snapshot Boundary
     * - Assumes persistence layer has already validated row existence and schema constraints
     * - This is NOT a "safe loader" — invalid DB state is fatal by design
     * - Rejects corrupted audit fields (e.g., SUSPENDED without actorId) → throws DomainException
     * - Result MUST satisfy identical invariants as create()-produced episodes
     *
     * SEMANTIC DISTINCTION from create():
     * - create() = New relationship, domain command path
     * - rehydrate() = Historical reconstruction, persistence path only
     * - replay() [F3.3] = Event-stream reconstruction, immutable-event path
     *
     * TYPE CONVERSION:
     * - String parameters ($actorId, $transitionReason) converted to typed VOs
     * - Maintains type safety internally while accepting persistence layer strings
     * - Null-handling: preserves null audit fields for ACTIVE episodes
     *
     * PRE-DEPLOYMENT GUARANTEE:
     * Before deploying this code, verify no invalid rows exist in persistence layer.
     * Trust boundary assumes database is correct or rejects it loudly.
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
            actorId: $actorId !== null ? ActorId::fromString($actorId) : null,
            transitionReason: $transitionReason !== null ? TransitionReason::fromString($transitionReason) : null,
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
            actorId: ActorId::fromString($actorId),
            transitionReason: TransitionReason::fromString($reason),
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
            actorId: ActorId::fromString($actorId),
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
            actorId: ActorId::fromString($actorId),
            transitionReason: TransitionReason::fromString($reason),
            transitionedAt: $at,
        );
    }

    /**
     * Reconstruct episode from immutable event stream (F3.3 event sourcing — reserved).
     *
     * TRUST BOUNDARY: Event Stream Boundary (F3.3 - Not Yet Implemented)
     * - Event stream is the authoritative source of truth
     * - Events are immutable once recorded and replayed in sequence
     * - Deterministic reconstruction: same input sequence always produces identical state
     * - Output is identical to the state that would result from executing the historical operation sequence
     *
     * SEMANTIC DISTINCTION from other factories:
     * - create() = Domain command path (new relationship)
     * - rehydrate() = Persistence snapshot path (point-in-time database reconstruction)
     * - replay() = Event sequence path (immutable historical reconstruction)
     *
     * IMPLEMENTATION DETAILS (Phase F3.3):
     * - Accept EventCollection or array of domain events
     * - Apply each event in order, validating event sequence validity
     * - Reconstruct lineage from first MembershipEstablished event onward
     * - Produce identical episode as if human operations had re-occurred
     *
     * FORMAL GUARANTEE:
     * For any event sequence E, replay(E) reconstructs an episode such that:
     * - status = final state after applying all events
     * - actorId, transitionReason, transitionedAt = audit fields from final transition event
     * - All intermediate states satisfy constitutional validity
     *
     * @see F3.3 implementation plan: event-sourcing integration
     */
    // public static function replay(EventCollection $events): self
    // {
    //     // Deterministic reconstruction from immutable event sequence
    //     // Implementation: Phase F3.3
    // }
}
