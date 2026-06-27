<?php

declare(strict_types=1);

namespace Tests\Unit\Contexts\Membership\Infrastructure\Repositories;

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
use App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeRepositoryTest extends TestCase
{
    use RefreshDatabase;

    private EloquentCommitteeRepository $repository;
    private TenantId $tenantId;
    private CommitteeId $committeeId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->repository = new EloquentCommitteeRepository();
        $this->tenantId = TenantId::fromString('11111111-1111-1111-1111-111111111111');
        $this->committeeId = CommitteeId::generate();
    }

    /** @test */
    public function repository_persists_assignment_on_save(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());

        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            new MemberId('MEMBER-001'),
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

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$assignment]
        );

        $this->repository->saveForTenant($committee);

        // Verify assignment was persisted
        $reloaded = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertNotNull($reloaded);
        $this->assertCount(1, $reloaded->getAssignments());
        $this->assertTrue(
            $reloaded->getAssignments()[0]->getId()->equals($assignment->getId())
        );
    }

    /** @test */
    public function repository_loads_assignments_on_find(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());

        $assignment1 = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            new MemberId('MEMBER-001'),
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
            new MemberId('MEMBER-002'),
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
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$assignment1, $assignment2]
        );

        $this->repository->saveForTenant($committee);

        $reloaded = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertCount(2, $reloaded->getAssignments());
        $this->assertTrue($reloaded->getAssignments()[0]->getMemberId()->equals(new MemberId('MEMBER-001')));
        $this->assertTrue($reloaded->getAssignments()[1]->getMemberId()->equals(new MemberId('MEMBER-002')));
    }

    /** @test */
    public function repository_syncs_removed_assignments(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());

        $assignment1 = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            new MemberId('MEMBER-001'),
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
            new MemberId('MEMBER-002'),
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

        // Save with both assignments
        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$assignment1, $assignment2]
        );

        $this->repository->saveForTenant($committee);

        // Reload and verify both exist
        $reloaded = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertCount(2, $reloaded->getAssignments());

        // Save again with only one assignment (removed the other)
        $committeeWithOne = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$assignment1]
        );

        $this->repository->saveForTenant($committeeWithOne);

        // Verify only one remains
        $reloadedAfterRemove = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertCount(1, $reloadedAfterRemove->getAssignments());
        $this->assertTrue(
            $reloadedAfterRemove->getAssignments()[0]->getId()->equals($assignment1->getId())
        );
    }

    /** @test */
    public function repository_updates_existing_assignment_on_save(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());

        $assignmentId = CommitteeAssignmentId::generate();

        $originalAssignment = CommitteeAssignment::reconstruct(
            $assignmentId,
            $this->committeeId,
            new MemberId('MEMBER-001'),
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

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$originalAssignment]
        );

        $this->repository->saveForTenant($committee);

        // Reload and verify original data
        $reloaded = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertTrue(
            $reloaded->getAssignments()[0]->getRolePath()->equals(RolePath::fromString('1.1.1'))
        );

        // Create updated assignment with same ID but different role path
        $updatedAssignment = CommitteeAssignment::reconstruct(
            $assignmentId,
            $this->committeeId,
            new MemberId('MEMBER-001'),
            RolePath::fromString('1.1.2'),
            new DateTimeImmutable('2026-01-15'),
            NominationType::elected(),
            new DateTimeImmutable('2026-01-10'),
            null,
            null,
            null,
            null,
            []
        );

        $updatedCommittee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$updatedAssignment]
        );

        $this->repository->saveForTenant($updatedCommittee);

        // Verify assignment was updated
        $reloadedAfterUpdate = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertTrue(
            $reloadedAfterUpdate->getAssignments()[0]->getRolePath()->equals(RolePath::fromString('1.1.2'))
        );
    }

    /** @test */
    public function round_trip_assign_save_reload_see_assignment(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());

        $assignment = CommitteeAssignment::reconstruct(
            CommitteeAssignmentId::generate(),
            $this->committeeId,
            new MemberId('MEMBER-001'),
            RolePath::fromString('1.1.1'),
            new DateTimeImmutable('2026-01-15'),
            NominationType::elected(),
            new DateTimeImmutable('2026-01-10'),
            new DateTimeImmutable('2028-01-15'),
            null,
            null,
            'Test notes',
            ['source' => 'election']
        );

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$assignment]
        );

        $this->repository->saveForTenant($committee);

        $reloaded = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertCount(1, $reloaded->getAssignments());

        $reloadedAssignment = $reloaded->getAssignments()[0];
        $this->assertTrue($reloadedAssignment->getMemberId()->equals(new MemberId('MEMBER-001')));
        $this->assertTrue($reloadedAssignment->getRolePath()->equals(RolePath::fromString('1.1.1')));
        $this->assertTrue($reloadedAssignment->isActive());
        $this->assertNull($reloadedAssignment->getLeftDate());
        $this->assertEquals('Test notes', $reloadedAssignment->getNotes());
        $this->assertEquals(['source' => 'election'], $reloadedAssignment->getMetadata());
    }

    /** @test */
    public function round_trip_remove_save_reload_see_no_active_assignment(): void
    {
        $structure = CommitteeStructureRegistry::forType(CommitteeType::central());

        $assignmentId = CommitteeAssignmentId::generate();
        $assignment = CommitteeAssignment::reconstruct(
            $assignmentId,
            $this->committeeId,
            new MemberId('MEMBER-001'),
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

        $committee = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            [$assignment]
        );

        $this->repository->saveForTenant($committee);

        // Verify assignment exists after first save
        $afterFirstSave = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertCount(1, $afterFirstSave->getAssignments());
        $this->assertTrue($afterFirstSave->getAssignments()[0]->isActive());

        // Save committee with empty assignments (removed assignment)
        $committeeWithoutAssignment = Committee::reconstruct(
            $this->committeeId,
            $this->tenantId,
            CommitteeType::central(),
            CommitteeName::fromString('Test Committee'),
            'TEST-001',
            null,
            CommitteeStatus::active(),
            $structure,
            []
        );

        $this->repository->saveForTenant($committeeWithoutAssignment);

        // Verify no active assignments (soft delete: left_date set, is_active = false)
        $afterRemove = $this->repository->findForTenant($this->committeeId, $this->tenantId);
        $this->assertCount(0, $afterRemove->getAssignments());
    }
}
