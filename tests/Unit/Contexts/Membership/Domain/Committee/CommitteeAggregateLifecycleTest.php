<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\CommitteeAggregate;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeCreated;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeLifecycleChanged;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeParentAttached;
use App\Contexts\Membership\Domain\Committee\Events\CommitteeTermUpdated;
use App\Contexts\Membership\Domain\Committee\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\Committee\ValueObjects\StructuralOperationalState;
use App\Contexts\Membership\Domain\Committee\ValueObjects\TermPeriod;
use DateTimeImmutable;
use DomainException;
use PHPUnit\Framework\TestCase;

/**
 * CommitteeAggregateLifecycleTest — Phase 1 Invariant Enforcement
 *
 * RED → GREEN tests driving invariant compliance:
 * - INV-01: Dissolved committee cannot be restored
 * - INV-02: Cannot suspend dissolved committee
 * - INV-03: Term end must be after start (TermPeriod validates)
 * - INV-04: Extended term start must not precede current term start
 * - INV-05: Committee cannot attach to itself
 * - INV-06: Attachment must not create cycle (application validator)
 */
final class CommitteeAggregateLifecycleTest extends TestCase
{
    public function test_it_creates_committee_with_initial_active_state(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'National Committee',
            1,
        );

        $this->assertEquals('National Committee', $committee->name());
        $this->assertEquals(1, $committee->levelIndex());
        $this->assertEquals(
            StructuralOperationalState::ACTIVE,
            $committee->state()
        );
        $this->assertNull($committee->term());
        $this->assertNull($committee->parentId());
    }

    public function test_it_emits_created_event(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test Committee',
            2,
        );

        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeCreated::class, $events[0]);
    }

    /**
     * INV-05: Committee cannot attach itself as parent.
     */
    public function test_it_rejects_self_attachment(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-05');

        $committeeId = CommitteeId::generate();
        $committee = CommitteeAggregate::create($committeeId, 'Test', 1);

        $committee->attachToParent($committeeId);
    }

    /**
     * INV-05: Valid parent attachment succeeds.
     */
    public function test_it_attaches_to_valid_parent(): void
    {
        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();

        $committee = CommitteeAggregate::create($childId, 'Child', 2);
        $committee->attachToParent($parentId);

        $this->assertTrue($committee->parentId()->equals($parentId));
    }

    public function test_it_emits_parent_attached_event(): void
    {
        $childId = CommitteeId::generate();
        $parentId = CommitteeId::generate();

        $committee = CommitteeAggregate::create($childId, 'Child', 2);
        $committee->releaseEvents(); // clear create event

        $committee->attachToParent($parentId);
        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeParentAttached::class, $events[0]);
    }

    /**
     * INV-03: Term period validates end > start.
     */
    public function test_it_accepts_valid_term(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $period = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $committee->startTerm($period);

        $this->assertEquals($period, $committee->term());
    }

    public function test_it_emits_term_updated_event(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $committee->releaseEvents(); // clear create event

        $period = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $committee->startTerm($period);
        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeTermUpdated::class, $events[0]);
    }

    /**
     * INV-04: Extended term start must not precede current term start.
     */
    public function test_it_rejects_extending_term_backward(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-04');

        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $original = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $committee->startTerm($original);
        $committee->releaseEvents();

        $backward = TermPeriod::from(
            new DateTimeImmutable('-2 months'),
            new DateTimeImmutable('+2 months'),
        );

        $committee->extendTerm($backward);
    }

    /**
     * INV-04: Valid extension accepted.
     */
    public function test_it_extends_term_with_valid_forward_period(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $original = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $committee->startTerm($original);
        $committee->releaseEvents();

        $extended = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+3 months'),
        );

        $committee->extendTerm($extended);

        $this->assertEquals($extended, $committee->term());
    }

    /**
     * INV-02: Cannot suspend already-dissolved committee.
     */
    public function test_it_rejects_suspending_dissolved_committee(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-02');

        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $userId = MemberId::from('member-1');
        $committee->dissolve($userId, 'End of life');
        $committee->releaseEvents();

        $committee->suspend($userId, 'Violation');
    }

    /**
     * Valid suspension accepted.
     */
    public function test_it_suspends_active_committee(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $userId = MemberId::from('member-1');
        $committee->suspend($userId, 'Violation');

        $this->assertEquals(
            StructuralOperationalState::SUSPENDED,
            $committee->state()
        );
    }

    public function test_it_emits_suspension_event(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $committee->releaseEvents();

        $userId = MemberId::from('member-1');
        $committee->suspend($userId, 'Violation');

        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeLifecycleChanged::class, $events[0]);
    }

    /**
     * INV-01: Dissolved committee cannot be restored (terminal state).
     */
    public function test_it_rejects_restoring_dissolved_committee(): void
    {
        $this->expectException(DomainException::class);
        $this->expectExceptionMessage('INV-01');

        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $userId = MemberId::from('member-1');
        $committee->dissolve($userId, 'End of life');
        $committee->releaseEvents();

        $committee->restore($userId);
    }

    /**
     * Valid restoration (from suspended).
     */
    public function test_it_restores_suspended_committee(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $userId = MemberId::from('member-1');
        $committee->suspend($userId, 'Temporary');
        $committee->releaseEvents();

        $committee->restore($userId);

        $this->assertEquals(
            StructuralOperationalState::ACTIVE,
            $committee->state()
        );
    }

    public function test_it_emits_restoration_event(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $userId = MemberId::from('member-1');
        $committee->suspend($userId, 'Temporary');
        $committee->releaseEvents();

        $committee->restore($userId);
        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeLifecycleChanged::class, $events[0]);
    }

    /**
     * Dissolution is terminal (no reversals allowed).
     */
    public function test_it_dissolves_committee_as_terminal_state(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $userId = MemberId::from('member-1');
        $committee->dissolve($userId, 'End of life');

        $this->assertEquals(
            StructuralOperationalState::DISSOLVED,
            $committee->state()
        );
    }

    public function test_it_emits_dissolution_event(): void
    {
        $committee = CommitteeAggregate::create(
            CommitteeId::generate(),
            'Test',
            1,
        );

        $committee->releaseEvents();

        $userId = MemberId::from('member-1');
        $committee->dissolve($userId, 'End of life');

        $events = $committee->releaseEvents();

        $this->assertCount(1, $events);
        $this->assertInstanceOf(CommitteeLifecycleChanged::class, $events[0]);
    }

    /**
     * toFacts() extracts state for policies.
     */
    public function test_it_extracts_facts_for_policy_evaluation(): void
    {
        $committeeId = CommitteeId::generate();
        $committee = CommitteeAggregate::create($committeeId, 'Test', 1);

        $period = TermPeriod::from(
            new DateTimeImmutable('-1 month'),
            new DateTimeImmutable('+1 month'),
        );

        $parentId = CommitteeId::generate();
        $committee->startTerm($period);
        $committee->attachToParent($parentId);

        $facts = $committee->toFacts();

        $this->assertEquals($committeeId, $facts->id);
        $this->assertEquals('ACTIVE', $facts->operationalState);
        $this->assertEquals($period, $facts->term);
        $this->assertEquals($parentId, $facts->parentId);
    }
}
