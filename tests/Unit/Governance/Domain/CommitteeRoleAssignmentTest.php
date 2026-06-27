<?php

declare(strict_types=1);

namespace Tests\Unit\Governance\Domain;

use PHPUnit\Framework\TestCase;
use App\Contexts\Governance\Domain\Committee\Committee;
use App\Contexts\Governance\Domain\Committee\CommitteeId;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Governance\Domain\Committee\Enums\CommitteeRole;
use App\Contexts\Governance\Domain\Committee\Events\MemberAssignedToCommittee;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;

final class CommitteeRoleAssignmentTest extends TestCase
{
    public function test_it_assigns_member_with_role(): void
    {
        $committee = Committee::create(CommitteeId::generate(), TenantId::fromString('a1ca231c-59aa-4950-8b23-75b16d5c176a'));
        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::CHAIR);

        $events = $committee->pullEvents();

        $this->assertCount(1, $events);

        /** @var MemberAssignedToCommittee $event */
        $event = $events[0];

        $this->assertInstanceOf(MemberAssignedToCommittee::class, $event);
        $this->assertEquals(CommitteeRole::CHAIR, $event->role());
    }

    public function test_duplicate_member_assignment_is_rejected(): void
    {
        $committee = Committee::create(CommitteeId::generate(), TenantId::fromString('a1ca231c-59aa-4950-8b23-75b16d5c176a'));
        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::MEMBER);

        $this->expectException(\DomainException::class);
        $this->expectExceptionMessage('Member already assigned to this committee');

        $committee->addMember($memberId, CommitteeRole::CHAIR);
    }

    public function test_member_assignment_preserves_role_type(): void
    {
        $committee = Committee::create(CommitteeId::generate(), TenantId::fromString('a1ca231c-59aa-4950-8b23-75b16d5c176a'));
        $memberId = MemberId::generate();

        $committee->addMember($memberId, CommitteeRole::DEPUTY);

        $event = $committee->pullEvents()[0];

        $this->assertEquals(CommitteeRole::DEPUTY, $event->role());
    }

    public function test_all_role_types_are_assignable(): void
    {
        $committee = Committee::create(CommitteeId::generate(), TenantId::fromString('a1ca231c-59aa-4950-8b23-75b16d5c176a'));

        foreach (CommitteeRole::cases() as $role) {
            $committee->addMember(MemberId::generate(), $role);
        }

        $events = $committee->pullEvents();

        $this->assertCount(count(CommitteeRole::cases()), $events);

        foreach (CommitteeRole::cases() as $role) {
            $this->assertTrue(
                collect($events)->some(fn ($e) => $e->role() === $role),
                "Role {$role->value} was not assigned"
            );
        }
    }
}
