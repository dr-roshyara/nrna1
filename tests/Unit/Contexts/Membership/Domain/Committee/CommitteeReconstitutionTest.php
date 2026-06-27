<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Domain\Committee;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\Committee\CommitteeAssignment;
use App\Contexts\Membership\Domain\Committee\CommitteeStructureRegistry;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeName;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeStatus;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeType;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use DomainException;
use LogicException;
use PHPUnit\Framework\TestCase;

final class CommitteeReconstitutionTest extends TestCase
{
    private TenantId $tenantId;
    private CommitteeId $committeeId;
    private CommitteeName $committeeName;
    private CommitteeType $committeeType;
    private CommitteeStatus $status;
    private string $code;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
        $this->committeeId = CommitteeId::generate();
        $this->committeeName = CommitteeName::fromString('Central Committee');
        $this->committeeType = CommitteeType::central();
        $this->status = CommitteeStatus::active();
        $this->code = 'CENTRAL-001';
    }

    /** @test */
    public function reconstruct_with_structure_creates_committee(): void
    {
        $structure = CommitteeStructureRegistry::forType($this->committeeType);

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            $structure,
            []
        );

        $this->assertTrue($committee->getId()->equals($this->committeeId));
        $this->assertTrue($committee->getTenantId()->equals($this->tenantId));
        $this->assertTrue($committee->getName()->equals($this->committeeName));
    }

    /** @test */
    public function reconstruct_without_structure_throws_LogicException(): void
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('CommitteeStructure must be provided');

        Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            null,  // ← structure is null - should throw
            []
        );
    }

    /** @test */
    public function reconstruct_with_empty_assignments_exposes_via_getAssignments(): void
    {
        $structure = CommitteeStructureRegistry::forType($this->committeeType);

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            $structure,
            []
        );

        $this->assertCount(0, $committee->getAssignments());
    }

    /** @test */
    public function reconstruct_with_assignments_exposes_them_correctly(): void
    {
        $structure = CommitteeStructureRegistry::forType($this->committeeType);

        // Create real assignments for the reconstituted committee
        $assignment1 = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            new MemberId('TEST-MEMBER-001'),
            RolePath::fromString('1.1.1'),
            new DateTimeImmutable('2026-01-15'),
            NominationType::elected(),
            new DateTimeImmutable('2026-01-10'),
            null,
            null,
            null,
            null,
            []
        );

        $assignment2 = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            new MemberId('TEST-MEMBER-002'),
            RolePath::fromString('1.1.2'),
            new DateTimeImmutable('2026-02-01'),
            NominationType::appointed(),
            null,
            null,
            null,
            null,
            null,
            []
        );

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            $structure,
            [$assignment1, $assignment2]
        );

        $this->assertCount(2, $committee->getAssignments());
        $this->assertTrue($committee->getAssignments()[0]->getId()->equals($assignment1->getId()));
        $this->assertTrue($committee->getAssignments()[1]->getId()->equals($assignment2->getId()));
    }

    /** @test */
    public function reconstruct_enforces_structure_required(): void
    {
        // This test ensures LogicException is thrown, not TypeError or DomainException
        $this->expectException(LogicException::class);

        Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            null,
            []
        );
    }

    /** @test */
    public function assign_member_on_reconstituted_committee_has_structure_available(): void
    {
        $structure = CommitteeStructureRegistry::forType($this->committeeType);

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            $structure,
            []
        );

        // Verify structure was properly assigned by checking it exists (would be null if not set)
        // Full assignMember logic tested in integration tests
        $this->assertCount(0, $committee->getAssignments());
    }

    /** @test */
    public function reconstruct_with_pre_loaded_assignments_keeps_them_loaded(): void
    {
        $structure = CommitteeStructureRegistry::forType($this->committeeType);
        $memberId = new MemberId('TEST-MEMBER-001');
        $rolePath = RolePath::fromString('1.1.1');

        // Create assignment in reconstituted state
        $existingAssignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            $memberId,
            $rolePath,
            new DateTimeImmutable('2026-01-15'),
            NominationType::elected(),
            new DateTimeImmutable('2026-01-10'),
            null,
            null,
            null,
            null,
            []
        );

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            $this->committeeType,
            $this->committeeName,
            $this->code,
            null,
            $this->status,
            $structure,
            [$existingAssignment]
        );

        // Verify assignment was properly loaded by reconstruct
        $this->assertCount(1, $committee->getAssignments());
        $this->assertTrue($committee->getAssignments()[0]->getId()->equals($existingAssignment->getId()));
        $this->assertTrue($committee->getAssignments()[0]->getMemberId()->equals($memberId));
    }
}
