<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\GovernanceState;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

/**
 * GovernanceState unit tests.
 *
 * Covers all 4 invariants and all lifecycle transitions:
 * - INV-GS-01: Cannot suspend if DISSOLVED
 * - INV-GS-02: Cannot restore if DISSOLVED
 * - INV-GS-03: Cannot attach to self
 * - INV-GS-04: Extended term start must not precede current term start
 */
final class GovernanceStateTest extends TestCase
{
    // ─── Initial state ─────────────────────────────────────────────

    public function test_fresh_state_is_active(): void
    {
        $state = new GovernanceState();

        $this->assertTrue($state->isActive());
        $this->assertFalse($state->isSuspended());
        $this->assertFalse($state->isDissolved());
        $this->assertNull($state->term());
        $this->assertNull($state->parentId());
    }

    // ─── Suspend ───────────────────────────────────────────────────

    public function test_suspend_active_committee(): void
    {
        $state = new GovernanceState();
        $state->suspend();

        $this->assertTrue($state->isSuspended());
        $this->assertFalse($state->isActive());
    }

    /**
     * INV-GS-01: Cannot suspend if already DISSOLVED.
     */
    public function test_cannot_suspend_dissolved_committee(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-GS-01');

        $state = new GovernanceState();
        $state->dissolve();
        $state->suspend();
    }

    // ─── Restore ───────────────────────────────────────────────────

    public function test_restore_suspended_committee(): void
    {
        $state = new GovernanceState();
        $state->suspend();
        $state->restore();

        $this->assertTrue($state->isActive());
    }

    /**
     * INV-GS-02: Cannot restore if DISSOLVED (terminal state).
     */
    public function test_cannot_restore_dissolved_committee(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-GS-02');

        $state = new GovernanceState();
        $state->dissolve();
        $state->restore();
    }

    // ─── Dissolve ──────────────────────────────────────────────────

    public function test_dissolve_active_committee(): void
    {
        $state = new GovernanceState();
        $state->dissolve();

        $this->assertTrue($state->isDissolved());
    }

    public function test_dissolve_is_terminal(): void
    {
        $state = new GovernanceState();
        $state->dissolve();

        $this->assertTrue($state->isDissolved());
        $this->assertFalse($state->isActive());
    }

    // ─── Attach to parent (INV-GS-03) ──────────────────────────────

    public function test_attach_to_valid_parent(): void
    {
        $state = new GovernanceState();
        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();

        $state->attachToParent($parentId, $childId);

        $this->assertTrue($parentId->equals($state->parentId()));
    }

    /**
     * INV-GS-03: Cannot attach to self.
     */
    public function test_cannot_attach_to_self(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-GS-03');

        $state = new GovernanceState();
        $committeeId = CommitteeId::generate();

        $state->attachToParent($committeeId, $committeeId);
    }

    // ─── Term management ───────────────────────────────────────────

    public function test_start_term(): void
    {
        $state = new GovernanceState();
        $period = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $state->startTerm($period);

        $this->assertSame($period, $state->term());
    }

    /**
     * INV-GS-04: Extended term start must not precede current term start.
     */
    public function test_cannot_extend_term_backward(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-GS-04');

        $state = new GovernanceState();
        $state->startTerm(TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        ));

        $state->extendTerm(TermPeriod::from(
            new DateTimeImmutable('-2 months'),
            new DateTimeImmutable('+2 months'),
        ));
    }

    public function test_extend_term_forward(): void
    {
        $state = new GovernanceState();
        $original = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );
        $extended = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+3 months'),
        );

        $state->startTerm($original);
        $state->extendTerm($extended);

        $this->assertSame($extended, $state->term());
    }

    // ─── Reconstruct from persistence ──────────────────────────────

    public function test_reconstruct_applies_all_state(): void
    {
        $parentId = CommitteeId::generate();
        $term = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $state = GovernanceState::reconstruct(
            StructuralOperationalState::SUSPENDED,
            $term,
            $parentId,
        );

        $this->assertTrue($state->isSuspended());
        $this->assertSame($term, $state->term());
        $this->assertTrue($parentId->equals($state->parentId()));
    }
}
