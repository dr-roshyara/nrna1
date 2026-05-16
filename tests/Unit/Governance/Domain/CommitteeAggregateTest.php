<?php

declare(strict_types=1);

namespace Tests\Unit\Governance\Domain;

use PHPUnit\Framework\TestCase;
use Tests\Support\DomainIdFactory;

use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
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

        $committee->addMember($memberId);

        $this->assertTrue($committee->isMemberAssigned($memberId));
    }

    /**
     * INVARIANT: Adding same member twice is idempotent
     * Second add should be ignored
     */
    public function test_adding_duplicate_member_is_idempotent(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        $committee->addMember($memberId);
        $committee->addMember($memberId); // second add

        $members = $committee->getMembers();

        // Should have only ONE member, not two
        $this->assertCount(1, $members);
    }

    /**
     * INVARIANT: Adding same member twice emits event only once
     */
    public function test_duplicate_add_emits_event_once(): void
    {
        $committee = Committee::create(
            CommitteeId::generate(),
            DomainIdFactory::tenant()
        );

        $memberId = MemberId::generate();

        $committee->addMember($memberId);
        $events1 = $committee->pullEvents();

        $committee->addMember($memberId); // second add
        $events2 = $committee->pullEvents();

        $this->assertCount(1, $events1);
        $this->assertCount(0, $events2); // no event on duplicate
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

        $committee->addMember($memberId);
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
        $committee->addMember($memberId);

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

        $committee->addMember($member1);
        $committee->addMember($member2);
        $committee->addMember($member3);

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

        $committee->addMember(MemberId::generate());

        $events1 = $committee->pullEvents();
        $events2 = $committee->pullEvents();

        $this->assertCount(1, $events1);
        $this->assertCount(0, $events2);
    }
}
