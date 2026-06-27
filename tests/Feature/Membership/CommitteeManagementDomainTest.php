<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Contexts\Membership\Domain\Committee\Committee;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Membership\Domain\ValueObjects\NominationType;
use App\Contexts\Membership\Domain\ValueObjects\RolePath;
use App\Contexts\Membership\Infrastructure\Repositories\EloquentCommitteeRepository;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use App\Models\Organisation;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeManagementDomainTest extends TestCase
{
    use RefreshDatabase;

    private TenantId $tenantId;
    private CommitteeId $committeeId;
    private Organisation $organisation;
    private EloquentCommitteeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Test Org',
            'code' => 'TEST',
            'slug' => 'test-org',
        ]);

        $this->tenantId = TenantId::fromString($this->organisation->id);
        $this->repository = new EloquentCommitteeRepository();
        $this->committeeId = CommitteeId::generate();
    }

    public function test_assign_member_to_committee_creates_active_assignment(): void
    {
        $committee = Committee::createCentral(
            id: $this->committeeId,
            tenantId: $this->tenantId,
            name: 'Test Central Committee',
            code: 'CENTRAL-TEST-001',
            geoReference: null
        );

        $memberId = new MemberId('TEST-MEMBER-001');
        $rolePath = RolePath::fromString('1.1.1');

        $assignment = $committee->assignMember(
            memberId: $memberId,
            rolePath: $rolePath,
            nominationType: NominationType::elected(),
            memberGeography: null,
            electionDate: new DateTimeImmutable('2026-01-10'),
            termEndDate: new DateTimeImmutable('2028-01-15'),
            appointedByUserId: null,
            notes: 'Elected during annual convention',
            metadata: []
        );

        $this->repository->saveForTenant($committee);

        $this->assertDatabaseHas('committee_assignments', [
            'id' => $assignment->getId()->value(),
            'committee_id' => $this->committeeId->value(),
            'member_id' => $memberId->value(),
            'is_active' => true,
        ]);
    }

    public function test_remove_member_from_committee_marks_assignment_inactive(): void
    {
        $committee = Committee::createCentral(
            id: $this->committeeId,
            tenantId: $this->tenantId,
            name: 'Test Central Committee',
            code: 'CENTRAL-TEST-001',
            geoReference: null
        );

        $memberId = new MemberId('TEST-MEMBER-001');
        $rolePath = RolePath::fromString('1.1.1');

        $assignment = $committee->assignMember(
            memberId: $memberId,
            rolePath: $rolePath,
            nominationType: NominationType::elected(),
            memberGeography: null,
            electionDate: new DateTimeImmutable('2026-01-10'),
            termEndDate: new DateTimeImmutable('2028-01-15'),
            appointedByUserId: null,
            notes: 'Elected during annual convention',
            metadata: []
        );

        $this->repository->saveForTenant($committee);

        $assignmentId = $assignment->getId()->value();

        $this->assertDatabaseHas('committee_assignments', [
            'id' => $assignmentId,
            'is_active' => true,
        ]);

        $committee->removeMember($assignment->getMemberId(), new DateTimeImmutable());
        $this->repository->saveForTenant($committee);

        $this->assertDatabaseHas('committee_assignments', [
            'id' => $assignmentId,
            'is_active' => false,
        ]);

        $record = \DB::table('committee_assignments')
            ->where('id', $assignmentId)
            ->first();

        $this->assertNotNull($record->left_date, 'left_date should be set on removal');
    }
}
