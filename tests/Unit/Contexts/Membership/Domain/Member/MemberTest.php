<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Member;

use App\Contexts\Membership\Domain\Member\Member;
use App\Contexts\Membership\Domain\Member\MemberId;
use App\Contexts\Membership\Domain\Member\MemberStatus;
use App\Contexts\Membership\Domain\Member\ValueObjects\PersonalInfo;
use App\Contexts\Membership\Domain\ValueObjects\TenantId;
use App\Contexts\Membership\Domain\ValueObjects\MembershipTypeId;
use App\Contexts\Membership\Domain\Member\Events\MemberRegistered;
use App\Contexts\Membership\Domain\Member\Events\MemberActivated;
use PHPUnit\Framework\TestCase;

final class MemberTest extends TestCase
{
    private TenantId $tenantId;
    private PersonalInfo $personalInfo;
    private MembershipTypeId $membershipTypeId;

    protected function setUp(): void
    {
        $this->tenantId = TenantId::fromString('550e8400-e29b-41d4-a716-446655440000');
        $this->personalInfo = PersonalInfo::create(
            'John Doe',
            'john@example.com',
            '+1234567890'
        );
        $this->membershipTypeId = MembershipTypeId::fromString('123e4567-e89b-12d3-a456-426614174000');
    }

    public function test_member_can_be_registered(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $this->assertTrue($member->getStatus()->isActive());
        $this->assertEquals($this->tenantId, $member->getTenantId());
        $this->assertEquals($this->personalInfo, $member->getPersonalInfo());
    }

    public function test_member_records_registration_event(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $events = $member->pullEvents();
        $this->assertCount(1, $events);
        $this->assertInstanceOf(MemberRegistered::class, $events[0]);
    }

    public function test_member_can_be_activated(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $member->activate();

        $this->assertTrue($member->getStatus()->isActive());
    }

    public function test_member_cannot_be_suspended_if_already_suspended(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $member->suspend('Violation of terms');
        $this->expectException(\Exception::class);
        $member->suspend('Another reason');
    }

    public function test_member_can_be_suspended(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $member->suspend('Violation of rules');
        $this->assertTrue($member->getStatus()->isSuspended());
    }

    public function test_member_can_be_archived(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $member->archive();
        $this->assertTrue($member->getStatus()->isArchived());
    }

    public function test_member_status_transitions_are_valid(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        // Active → Suspended (valid)
        $member->suspend('Reason');
        $this->assertTrue($member->getStatus()->isSuspended());

        // Suspended → Active (valid via reactivate)
        $member->reactivate();
        $this->assertTrue($member->getStatus()->isActive());

        // Active → Archived (valid)
        $member->archive();
        $this->assertTrue($member->getStatus()->isArchived());
    }

    public function test_member_cannot_transition_from_archived(): void
    {
        $member = Member::register(
            $this->tenantId,
            $this->personalInfo,
            $this->membershipTypeId
        );

        $member->archive();

        $this->expectException(\Exception::class);
        $member->activate();
    }
}
