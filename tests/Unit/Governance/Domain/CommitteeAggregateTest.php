<?php

declare(strict_types=1);

namespace Tests\Unit\Governance\Domain;

use PHPUnit\Framework\TestCase;
use Tests\Support\DomainIdFactory;

use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Governance\Domain\Committee\Events\MemberRemovedFromCommittee;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

/**
 * Phase 1 — Domain Contract Tests (TDD)
 *
 * Committee aggregate owns membership assignments.
 * It does NOT own Member data (name, email, status).
 * Member loading is purely Projection responsibility.
 */
final class CommitteeAggregateTest extends TestCase
{
    /**
     * INVARIANT: Committee can add members
     * Emits MemberAssignedToCommittee event
     */
    public function test_can_add_member_to_committee(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::MEMBER);

        $this->assertTrue($committee->isMemberAssigned($memberId));
    }

    /**
     * INVARIANT: Adding same member twice throws exception
     * Domain enforces uniqueness constraint
     */
    public function test_adding_duplicate_member_throws_exception(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::MEMBER);

        // Second add should throw exception
        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Member already assigned to this committee');
        $committee->addMember($memberId, CommitteeRole::MEMBER);
    }

    /**
     * INVARIANT: Duplicate add throws before emitting event
     * Uniqueness enforced at domain boundary
     */
    public function test_duplicate_add_throws_before_event(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::MEMBER);
        $events1 = $committee->pullEvents();

        // Second add should throw
        $this->expectException(\DomainException::class);
        $committee->addMember($memberId, CommitteeRole::MEMBER);

        // Events should only have the first add
        $this->assertCount(1, $events1);
    }

    /**
     * INVARIANT: Committee can remove members
     * Emits MemberRemovedFromCommittee event
     */
    public function test_can_remove_member_from_committee(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::MEMBER);
        $committee->pullEvents(); // clear events

        $committee->removeMember($memberId);

        $this->assertFalse($committee->isMemberAssigned($memberId));
    }

    /**
     * INVARIANT: Removing non-existent member is safe
     * No exception, no event
     */
    public function test_removing_nonexistent_member_is_safe(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        try {
            $committee->removeMember($memberId);
            $this->assertTrue(true, 'No exception on removing non-existent member');
        } catch (\Exception $e) {
            $this->fail("Should not throw: {$e->getMessage()}");
        }
    }

    /**
     * INVARIANT: MemberAssignedToCommittee event carries full context
     * (no lazy loading required)
     */
    public function test_member_assigned_event_is_self_sufficient(): void
    {
        $committeeId = CommitteeId::generate();
        $tenantId = DomainIdFactory::tenant();
        $memberId = MemberId::generate();

        $committee = Committee::create($committeeId, $tenantId);
        $committee->addMember($memberId, CommitteeRole::MEMBER);

        $events = $committee->pullEvents();

        $this->assertCount(1, $events);

        $event = $events[0];

        $this->assertInstanceOf(MemberAssignedToCommittee::class, $event);
        $this->assertTrue($event->committeeId->equals($committeeId));
        $this->assertTrue($event->memberId->equals($memberId));
        $this->assertTrue($event->tenantId->equals($tenantId));
    }

    /**
     * INVARIANT: Multiple members can be assigned
     */
    public function test_multiple_members_can_be_assigned(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $member1 = MemberId::generate();
        $member2 = MemberId::generate();
        $member3 = MemberId::generate();

        $committee->addMember($member1, CommitteeRole::MEMBER);
        $committee->addMember($member2, CommitteeRole::MEMBER);
        $committee->addMember($member3, CommitteeRole::MEMBER);

        $members = $committee->getMembers();

        $this->assertCount(3, $members);
    }

    /**
     * INVARIANT: Events are cleared after pulling
     * (no event duplication on subsequent pulls)
     */
    public function test_events_are_cleared_on_pull(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $committee->addMember(MemberId::generate(), CommitteeRole::MEMBER);

        $events1 = $committee->pullEvents();
        $events2 = $committee->pullEvents();

        $this->assertCount(1, $events1);
        $this->assertCount(0, $events2);
    }
}
