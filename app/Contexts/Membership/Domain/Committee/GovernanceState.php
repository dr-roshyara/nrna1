<?php

declare(strict_types=1);

namespace App\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use DomainException;

/**
 * GovernanceState — lifecycle state entity within the Committee aggregate.
 *
 * Owns the structural lifecycle state machine (ACTIVE/SUSPENDED/DISSOLVED),
 * the constitutional term (mandate period), and the parent committee
 * reference (structural hierarchy).
 *
 * This is an ENTITY within the Committee aggregate root, not a separate
 * aggregate. It validates state transitions but does NOT record domain
 * events — the aggregate root (Committee) owns event recording.
 *
 * Invariants enforced:
 * - INV-GS-01: Cannot suspend if already DISSOLVED
 * - INV-GS-02: Cannot restore if DISSOLVED (terminal state)
 * - INV-GS-03: Cannot attach to self
 * - INV-GS-04: Extended term start must not precede current term start
 */
final class GovernanceState
{
    private StructuralOperationalState $state;
    private ?TermPeriod $term;
    private ?CommitteeId $parentId;

    public function __construct()
    {
        $this->state = StructuralOperationalState::ACTIVE;
        $this->term = null;
        $this->parentId = null;
    }

    /**
     * Reconstruct from persistence (bypasses fresh initial state).
     */
    public static function reconstruct(
        StructuralOperationalState $state,
        ?TermPeriod $term = null,
        ?CommitteeId $parentId = null,
    ): self {
        $instance = new self();
        $instance->state = $state;
        $instance->term = $term;
        $instance->parentId = $parentId;
        return $instance;
    }

    // ─── State accessors ─────────────────────────────────────────

    public function state(): StructuralOperationalState
    {
        return $this->state;
    }

    public function term(): ?TermPeriod
    {
        return $this->term;
    }

    public function parentId(): ?CommitteeId
    {
        return $this->parentId;
    }

    public function isActive(): bool
    {
        return $this->state === StructuralOperationalState::ACTIVE;
    }

    public function isSuspended(): bool
    {
        return $this->state === StructuralOperationalState::SUSPENDED;
    }

    public function isDissolved(): bool
    {
        return $this->state === StructuralOperationalState::DISSOLVED;
    }

    // ─── Lifecycle mutations ──────────────────────────────────────

    /**
     * Suspend this committee.
     *
     * @throws DomainException If already DISSOLVED (INV-GS-01)
     */
    public function suspend(): void
    {
        if ($this->state === StructuralOperationalState::DISSOLVED) {
            throw new DomainException(
                'Dissolved committee cannot be suspended (INV-GS-01)'
            );
        }

        $this->state = StructuralOperationalState::SUSPENDED;
    }

    /**
     * Restore a suspended committee.
     *
     * @throws DomainException If already DISSOLVED — terminal state (INV-GS-02)
     */
    public function restore(): void
    {
        if ($this->state === StructuralOperationalState::DISSOLVED) {
            throw new DomainException(
                'Dissolved committees cannot be restored — terminal state (INV-GS-02)'
            );
        }

        $this->state = StructuralOperationalState::ACTIVE;
    }

    /**
     * Dissolve this committee (terminal state).
     *
     * Dissolution is final. Reinstatement requires a new Committee +
     * GovernanceDecision.
     */
    public function dissolve(): void
    {
        $this->state = StructuralOperationalState::DISSOLVED;
    }

    /**
     * Attach to a parent committee in the structural hierarchy.
     *
     * The aggregate root passes its own ID so GovernanceState can
     * enforce INV-GS-03: cannot attach to self.
     *
     * @throws DomainException If the given parent ID equals the committee's own ID
     */
    public function attachToParent(CommitteeId $parentId, CommitteeId $ownId): void
    {
        if ($ownId->equals($parentId)) {
            throw new DomainException(
                'Committee cannot attach to itself (INV-GS-03)'
            );
        }

        $this->parentId = $parentId;
    }

    /**
     * Start a constitutional term.
     */
    public function startTerm(TermPeriod $period): void
    {
        $this->term = $period;
    }

    /**
     * Extend the current term.
     *
     * @throws DomainException If new term start precedes current term start (INV-GS-04)
     */
    public function extendTerm(TermPeriod $newPeriod): void
    {
        if ($this->term !== null && $newPeriod->start() < $this->term->start()) {
            throw new DomainException(
                'Extended term cannot begin before current term start (INV-GS-04)'
            );
        }

        $this->term = $newPeriod;
    }
}
