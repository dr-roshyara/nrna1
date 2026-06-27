<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\CommitteeAssignment;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Domain\ValueObjects\TenantUserId;
use DateTimeImmutable;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

final class CommitteeAssignmentTest extends TestCase
{
    private const TEST_MEMBER_ID = 'TEST-MEMBER-001';
    private const TEST_TENANT_USER_ID = '01JKUSER1234567890ABCDEFGH';
    private const TEST_ROLE_PRESIDENT = '1.1.1';
    private const TEST_ROLE_MEMBER = '1.1.2';

    /** @test */
    public function reconstruct_creates_assignment_with_all_fields(): void
    {
        $id = CommitteeAssignmentId::generate();
        $committeeId = CommitteeId::generate();
        $memberId = new MemberId(self::TEST_MEMBER_ID);
        $rolePath = RolePath::fromString(self::TEST_ROLE_PRESIDENT);
        $joinedDate = new DateTimeImmutable('2026-01-15 10:00:00');
        $nominationType = NominationType::elected();
        $electionDate = new DateTimeImmutable('2026-01-10');
        $termEndDate = new DateTimeImmutable('2028-01-15');
        $appointedByUserId = new TenantUserId(self::TEST_TENANT_USER_ID);
        $notes = 'Elected during annual convention';
        $metadata = ['source' => 'election'];

        $assignment = CommitteeAssignment::reconstruct(
            $id, $committeeId, $memberId, $rolePath, $joinedDate,
            $nominationType, $electionDate, $termEndDate, null,
            $appointedByUserId, $notes, $metadata
        );

        $this->assertTrue($assignment->getId()->equals($id));
        $this->assertTrue($assignment->getCommitteeId()->equals($committeeId));
        $this->assertTrue($assignment->getMemberId()->equals($memberId));
        $this->assertTrue($assignment->getRolePath()->equals($rolePath));
        $this->assertEquals($joinedDate, $assignment->getJoinedDate());
        $this->assertEquals($electionDate, $assignment->getElectionDate());
        $this->assertEquals($termEndDate, $assignment->getTermEndDate());
        $this->assertNull($assignment->getLeftDate());
        $this->assertEquals(self::TEST_TENANT_USER_ID, $assignment->getAppointedByUserId()->value());
        $this->assertTrue($assignment->isActive());
        $this->assertEquals($notes, $assignment->getNotes());
        $this->assertEquals($metadata, $assignment->getMetadata());
    }

    /** @test */
    public function reconstruct_with_null_optional_fields_works(): void
    {
        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            CommitteeId::generate(),
            new MemberId(self::TEST_MEMBER_ID),
            RolePath::fromString(self::TEST_ROLE_MEMBER),
            new DateTimeImmutable(),
            NominationType::appointed(),
            null,
            null,
            null,
            null,
            null,
            []
        );

        $this->assertNull($assignment->getElectionDate());
        $this->assertNull($assignment->getTermEndDate());
        $this->assertNull($assignment->getLeftDate());
        $this->assertNull($assignment->getAppointedByUserId());
        $this->assertNull($assignment->getNotes());
        $this->assertEquals([], $assignment->getMetadata());
    }

    /** @test */
    public function reconstruct_with_leftDate_sets_isActive_false(): void
    {
        $leftDate = new DateTimeImmutable('2026-03-15');

        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            CommitteeId::generate(),
            new MemberId(self::TEST_MEMBER_ID),
            RolePath::fromString(self::TEST_ROLE_MEMBER),
            new DateTimeImmutable('2026-01-01'),
            NominationType::appointed(),
            null,
            null,
            $leftDate,
            null,
            null,
            []
        );

        $this->assertEquals($leftDate, $assignment->getLeftDate());
        $this->assertFalse($assignment->isActive());
    }

    /** @test */
    public function reconstruct_without_leftDate_sets_isActive_true(): void
    {
        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            CommitteeId::generate(),
            new MemberId(self::TEST_MEMBER_ID),
            RolePath::fromString(self::TEST_ROLE_MEMBER),
            new DateTimeImmutable('2026-01-01'),
            NominationType::appointed(),
            null,
            null,
            null,
            null,
            null,
            []
        );

        $this->assertNull($assignment->getLeftDate());
        $this->assertTrue($assignment->isActive());
    }

    /** @test */
    public function reconstruct_preserves_joinedDate_timezone(): void
    {
        $timezone = new DateTimeZone('Asia/Kathmandu');
        $joinedDate = new DateTimeImmutable('2026-01-15 10:00:00', $timezone);

        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            CommitteeId::generate(),
            new MemberId(self::TEST_MEMBER_ID),
            RolePath::fromString(self::TEST_ROLE_MEMBER),
            $joinedDate,
            NominationType::appointed(),
            null,
            null,
            null,
            null,
            null,
            []
        );

        $this->assertEquals($joinedDate->getTimestamp(), $assignment->getJoinedDate()->getTimestamp());
        $this->assertEquals($timezone->getName(), $assignment->getJoinedDate()->getTimezone()->getName());
    }

    /** @test */
    public function reconstruct_with_volunteered_nomination_type_works(): void
    {
        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            CommitteeId::generate(),
            new MemberId(self::TEST_MEMBER_ID),
            RolePath::fromString(self::TEST_ROLE_MEMBER),
            new DateTimeImmutable(),
            NominationType::volunteered(),
            null,
            null,
            null,
            null,
            null,
            []
        );

        $this->assertNull($assignment->getElectionDate());
        $this->assertTrue($assignment->isActive());
    }

    /** @test */
    public function reconstruct_with_metadata_preserves_complex_data(): void
    {
        $metadata = [
            'source' => 'election',
            'voting_round' => 2,
            'tags' => ['priority', 'verified'],
            'nested' => ['key' => 'value'],
        ];

        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            CommitteeId::generate(),
            new MemberId(self::TEST_MEMBER_ID),
            RolePath::fromString(self::TEST_ROLE_MEMBER),
            new DateTimeImmutable(),
            NominationType::appointed(),
            null,
            null,
            null,
            null,
            null,
            $metadata
        );

        $this->assertEquals($metadata, $assignment->getMetadata());
    }
}
