<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Membership;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason;
use App\Contexts\Membership\Domain\Membership\ValueObjects\LineageId;
use App\Contexts\Membership\Domain\Membership\ValueObjects\MembershipStatus;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Shared\Domain\Concerns\RecordsEvents;
use App\Contexts\Membership\Domain\Membership\Events\MembershipSuspended;
use App\Contexts\Membership\Domain\Membership\Events\MembershipRestored;
use App\Contexts\Membership\Domain\Membership\Events\MembershipTerminated;
use App\Contexts\Membership\Domain\Membership\Events\MembershipReapplied;

/**
 * MembershipLineage Aggregate Root
 *
 * Represents a complete constitutional lifecycle of a member's relationship with a committee.
 * Owns all episodes (immutable state snapshots) and lifecycle transitions.
 *
 * PHASE A2.4 (Current):
 * - establish() owns ACTIVE status invariant for initial episode
 * - Repository contract is lineage-only (no episode-level queries)
 * - Application layer can ONLY mutate via lifecycle methods
 *
 * COMPLETED PHASES:
 * - A2.2: Aggregates episodes, queries current status, preserves history
 * - A2.3: suspend(), restore(), terminate(), reapply() methods with state machine rules
 */
final class MembershipLineage
{
    use RecordsEvents;

    /** @var CommitteeAssociation[] */
    private array $episodes = [];

    public function __construct(
        public readonly LineageId $lineageId,
        public readonly MemberId $memberId,
        public readonly CommitteeId $committeeId,
        public readonly TenantId $tenantId,
    ) {}

    /**
     * Establish a new membership lineage (initial episode).
     *
     * Called when a member's application is first approved.
     * Creates the lineage identity and the initial ACTIVE episode internally.
     * The aggregate enforces: first episode is ALWAYS ACTIVE.
     *
     * @param LineageId $lineageId Constitutional identity
     * @param MemberId $memberId Member of the relationship
     * @param CommitteeId $committeeId Committee of the relationship
     * @param TenantId $tenantId Tenant (organisation)
     * @param ApplicationReason $reason Why the member joined (RESIDENCE, EXCEPTION, MANUAL)
     * @param \DateTimeImmutable $at When the relationship began
     * @return self
     */
    public static function establish(
        LineageId $lineageId,
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
        ApplicationReason $reason,
        \DateTimeImmutable $at,
    ): self {
        // Aggregate owns: first episode is always ACTIVE
        $initialEpisode = CommitteeAssociation::create(
            memberId: $memberId,
            committeeId: $committeeId,
            associationType: $reason,
            associatedAt: $at,
        );

        $self = new self($lineageId, $memberId, $committeeId, $tenantId);
        $self->episodes[] = $initialEpisode;

        return $self;
    }

    /**
     * Reconstitute a lineage from persistence (repository only).
     *
     * Used when loading from database. Preserves full episode history.
     * Validates: lineage must have at least one episode, first episode must be ACTIVE.
     *
     * @param CommitteeAssociation[] $episodes
     */
    public static function reconstitute(
        LineageId $lineageId,
        MemberId $memberId,
        CommitteeId $committeeId,
        TenantId $tenantId,
        array $episodes,
    ): self {
        if (empty($episodes)) {
            throw new \InvalidArgumentException('Lineage must have at least one episode');
        }

        if (!$episodes[0]->status->equals(MembershipStatus::ACTIVE)) {
            throw new \InvalidArgumentException('Episode chain must contain valid transitions');
        }

        $self = new self($lineageId, $memberId, $committeeId, $tenantId);
        $self->episodes = $episodes;

        return $self;
    }

    /**
     * Current episode (most recent state snapshot).
     */
    public function current(): CommitteeAssociation
    {
        if (empty($this->episodes)) {
            throw new \LogicException('Lineage must have at least one episode');
        }

        return end($this->episodes);
    }

    /**
     * Current membership status.
     */
    public function currentStatus(): MembershipStatus
    {
        return $this->current()->status;
    }

    /**
     * Is this lineage's current operational status ACTIVE?
     *
     * Aggregate Fact: Returns current status, nothing more.
     * CRITICAL: Returns true ONLY when currentStatus() is strictly ACTIVE.
     * Bug fix: Previously returned !isTerminated(), making SUSPENDED members appear active.
     *
     * SEMANTIC NOTE: This answers "what is the status?" not "what rights does this enable?"
     * - Aggregate reports facts: operational status only
     * - Policy decides meaning: eligibility rules applied to facts
     *
     * Usage:
     * - Domain logic: check operational status for state machine transitions
     * - Policy evaluation: VotingEligibilityPolicy uses this to evaluate voting rights
     * - Never use this for authorization — use VotingEligibilityPolicy instead
     */
    public function isActive(): bool
    {
        return $this->currentStatus()->equals(MembershipStatus::ACTIVE);
    }

    /**
     * Is this lineage in SUSPENDED status?
     */
    public function isSuspended(): bool
    {
        return $this->currentStatus()->equals(MembershipStatus::SUSPENDED);
    }

    /**
     * Is this lineage in TERMINATED status?
     */
    public function isTerminated(): bool
    {
        return $this->currentStatus()->equals(MembershipStatus::TERMINATED);
    }

    /**
     * Does this lineage exist historically?
     *
     * Aggregate Fact: This lineage was once established.
     * Returns true if lineage has any episodes (has been initialized).
     * Used to distinguish "no membership relationship" from "terminated membership relationship".
     *
     * SEMANTIC NOTE: distinct from isActive() which queries operational status.
     * - exists() = membership relationship has historical episodes
     * - isActive() = current operational status is ACTIVE
     * - Both can be true (lineage exists AND status is ACTIVE)
     * - Both can be true during SUSPENDED or TERMINATED states too
     */
    public function exists(): bool
    {
        return count($this->episodes) > 0;
    }

    /**
     * All episodes (immutable history).
     *
     * @return CommitteeAssociation[]
     */
    public function episodes(): array
    {
        return $this->episodes;
    }

    /**
     * Can this lineage be reapplied?
     *
     * Only terminated lineages can be reapplied. This creates a NEW lineage, not
     * a resurrection of the old one.
     */
    public function canBeReapplied(): bool
    {
        return $this->currentStatus()->equals(MembershipStatus::TERMINATED);
    }

    /**
     * Suspend an active membership.
     *
     * Transition: ACTIVE → SUSPENDED
     * Effect: Member loses governance rights temporarily
     *
     * @throws InvalidLineageTransitionException if not in ACTIVE status
     */
    public function suspend(
        string $actorId,
        string $reason,
        \DateTimeImmutable $at,
    ): void {
        if (!$this->currentStatus()->equals(MembershipStatus::ACTIVE)) {
            throw \App\Contexts\Membership\Domain\Membership\Exceptions\InvalidLineageTransitionException::cannotSuspendNonActive();
        }

        if (empty($reason)) {
            throw new \InvalidArgumentException('Suspension reason cannot be empty');
        }

        // Delegate to entity method (returns new instance with guards)
        $newEpisode = $this->current()->suspend($actorId, $reason, $at);

        $this->episodes[] = $newEpisode;

        // Emit domain event
        $this->recordEvent(new MembershipSuspended(
            lineageId: $this->lineageId->value(),
            memberId: $this->memberId->value(),
            committeeId: $this->committeeId->value(),
            actorId: $actorId,
            reason: $reason,
            suspendedAt: $at,
        ));
    }

    /**
     * Restore a suspended membership.
     *
     * Transition: SUSPENDED → ACTIVE
     * Effect: Member regains full governance rights
     *
     * This preserves the same lineageId (institutional continuity).
     *
     * @throws InvalidLineageTransitionException if not in SUSPENDED status
     */
    public function restore(
        string $actorId,
        \DateTimeImmutable $at,
    ): void {
        if (!$this->currentStatus()->equals(MembershipStatus::SUSPENDED)) {
            throw \App\Contexts\Membership\Domain\Membership\Exceptions\InvalidLineageTransitionException::cannotRestoreNonSuspended();
        }

        // Delegate to entity method (returns new instance with guards)
        $newEpisode = $this->current()->restore($actorId, $at);

        $this->episodes[] = $newEpisode;

        // Emit domain event
        $this->recordEvent(new MembershipRestored(
            lineageId: $this->lineageId->value(),
            memberId: $this->memberId->value(),
            committeeId: $this->committeeId->value(),
            actorId: $actorId,
            restoredAt: $at,
        ));
    }

    /**
     * Terminate membership (final, irreversible).
     *
     * Transition: ACTIVE or SUSPENDED → TERMINATED
     * Effect: Member permanently loses governance rights
     *
     * CRITICAL: This is TERMINAL. No further transitions are possible.
     *
     * @throws InvalidLineageTransitionException if already TERMINATED
     */
    public function terminate(
        string $actorId,
        string $reason,
        \DateTimeImmutable $at,
    ): void {
        if ($this->currentStatus()->equals(MembershipStatus::TERMINATED)) {
            throw \App\Contexts\Membership\Domain\Membership\Exceptions\InvalidLineageTransitionException::cannotTerminateTerminated();
        }

        if (empty($reason)) {
            throw new \InvalidArgumentException('Termination reason cannot be empty');
        }

        // Delegate to entity method (returns new instance with guards)
        $newEpisode = $this->current()->terminate($actorId, $reason, $at);

        $this->episodes[] = $newEpisode;

        // Emit domain event
        $this->recordEvent(new MembershipTerminated(
            lineageId: $this->lineageId->value(),
            memberId: $this->memberId->value(),
            committeeId: $this->committeeId->value(),
            actorId: $actorId,
            reason: $reason,
            terminatedAt: $at,
        ));
    }

    /**
     * Reapply after termination (creates new initial episode).
     *
     * Transition: TERMINATED → new episode (caller creates new lineage)
     * Effect: Creates new constitutional chapter
     *
     * CRITICAL: This returns a NEW episode, not a lineage.
     * The caller is responsible for creating a new MembershipLineage
     * with a new LineageId.
     *
     * @return CommitteeAssociation The new initial episode
     *
     * @throws InvalidLineageTransitionException if not TERMINATED
     */
    public function reapplyInitialEpisode(
        \App\Contexts\Membership\Domain\Membership\ValueObjects\ApplicationReason $reason,
        \DateTimeImmutable $at,
    ): CommitteeAssociation {
        if (!$this->currentStatus()->equals(MembershipStatus::TERMINATED)) {
            throw \App\Contexts\Membership\Domain\Membership\Exceptions\InvalidLineageTransitionException::cannotReapplyNonTerminated();
        }

        // Return new episode with new AssociationId (new lineage)
        $newEpisode = CommitteeAssociation::create(
            memberId: $this->memberId,
            committeeId: $this->committeeId,
            associationType: $reason,
            associatedAt: $at,
        );

        // Emit domain event
        $this->recordEvent(new MembershipReapplied(
            memberId: $this->memberId->value(),
            committeeId: $this->committeeId->value(),
            newAssociationId: $newEpisode->associationId->value(),
            reappliedAt: $at,
        ));

        return $newEpisode;
    }

    /**
     * Add an episode (internal use only).
     *
     * Used by established() and reconstitute() to build episode history.
     * Also used by suspend(), restore(), terminate() during state transitions.
     */
    public function addEpisode(CommitteeAssociation $episode): void
    {
        $this->episodes[] = $episode;
    }
}
