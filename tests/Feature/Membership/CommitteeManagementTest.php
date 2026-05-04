<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

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
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use DateTimeImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeManagementTest extends TestCase
{
    use RefreshDatabase;

    private TenantId $tenantId;
    private User $admin;
    private CommitteeId $committeeId;
    private string $assignmentId; // Store as string for DB lookup
    private Organisation $organisation;
    private EloquentCommitteeRepository $repository;

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation
        $this->organisation = Organisation::create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Test Org',
            'code' => 'TEST',
            'slug' => 'test-org',
        ]);

        $this->tenantId = TenantId::fromString($this->organisation->id);

        // Create admin user
        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
            'email_verified_at' => now(),
        ]);

        // Assign admin role
        $role = UserOrganisationRole::create([
            'user_id' => $this->admin->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        // Verify the role was created
        $this->assertDatabaseHas('user_organisation_roles', [
            'user_id' => $this->admin->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'admin',
        ]);

        $this->repository = new EloquentCommitteeRepository();
        $this->committeeId = CommitteeId::generate();

        // Seed committee with active assignment
        $this->seedCommitteeWithActiveAssignment();
    }

    private function seedCommitteeWithActiveAssignment(): void
    {
        // Create committee using factory method
        $committee = Committee::createCentral(
            id: $this->committeeId,
            tenantId: $this->tenantId,
            name: 'Test Central Committee',
            code: 'CENTRAL-TEST-001',
            geoReference: null
        );

        // Assign a member
        $memberId = new MemberId('TEST-MEMBER-001');
        $rolePath = RolePath::fromString('1.1.1'); // President role
        
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

        // Store assignment ID for test assertions
        $this->assignmentId = $assignment->getId()->value();

        // Persist to database
        $this->repository->saveForTenant($committee);
    }

    /** @test */
    public function admin_can_remove_member_from_committee(): void
    {
        $this->actingAs($this->admin);

        // Verify assignment exists and is active before removal
        $this->assertDatabaseHas('committee_assignments', [
            'id' => $this->assignmentId,
            'is_active' => true,
        ]);

        $response = $this->delete(route('committees.members.remove', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
            'assignmentId' => $this->assignmentId,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        // Verify assignment is soft-deleted
        $this->assertDatabaseHas('committee_assignments', [
            'id' => $this->assignmentId,
            'is_active' => false,
        ]);

        $record = \DB::table('committee_assignments')
            ->where('id', $this->assignmentId)
            ->first();

        $this->assertNotNull($record->left_date, 'left_date should be set on removal');
    }

    /** @test */
    public function guest_cannot_remove_member_from_committee(): void
    {
        $response = $this->delete(route('committees.members.remove', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
            'assignmentId' => $this->assignmentId,
        ]));

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function non_admin_cannot_remove_member(): void
    {
        $member = User::create([
            'name' => 'Regular Member',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
        ]);

        $this->actingAs($member);

        $response = $this->delete(route('committees.members.remove', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
            'assignmentId' => $this->assignmentId,
        ]));

        $response->assertForbidden();
    }

    /** @test */
    public function cannot_remove_member_from_different_tenant(): void
    {
        $this->actingAs($this->admin);

        $otherOrgId = '22222222-2222-2222-2222-222222222222';

        $response = $this->delete(route('committees.members.remove', [
            'organisation' => $otherOrgId,
            'committeeId' => $this->committeeId->value(),
            'assignmentId' => $this->assignmentId,
        ]));

        $response->assertNotFound();
    }

    /** @test */
    public function cannot_remove_nonexistent_assignment(): void
    {
        $this->actingAs($this->admin);

        $fakeAssignmentId = CommitteeId::generate()->value(); // Use valid ULID format but not existing

        $response = $this->delete(route('committees.members.remove', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
            'assignmentId' => $fakeAssignmentId,
        ]));

        $response->assertRedirect();
        $response->assertSessionHas('errors');
    }

    /** @test */
    public function admin_can_update_committee_name(): void
    {
        $this->actingAs($this->admin);

        $newName = 'Updated Committee Name';

        $response = $this->patch(route('committees.update', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
        ]), [
            'name' => $newName,
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('committees', [
            'id' => $this->committeeId->value(),
            'name' => $newName,
        ]);
    }

    /** @test */
    public function admin_can_update_committee_status(): void
    {
        $this->actingAs($this->admin);

        $response = $this->patch(route('committees.update', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
        ]), [
            'status' => 'inactive',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('committees', [
            'id' => $this->committeeId->value(),
            'status' => 'inactive',
        ]);
    }

    /** @test */
    public function guest_cannot_update_committee(): void
    {
        $response = $this->patch(route('committees.update', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
        ]), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect(route('login'));
    }

    /** @test */
    public function non_admin_cannot_update_committee(): void
    {
        $member = User::create([
            'name' => 'Regular Member',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
            'email_verified_at' => now(),
        ]);

        $this->actingAs($member);

        $response = $this->patch(route('committees.update', [
            'organisation' => $this->organisation->slug,
            'committeeId' => $this->committeeId->value(),
        ]), [
            'name' => 'Updated Name',
        ]);

        $response->assertForbidden();
    }

    /** @test */
    public function cannot_update_committee_from_different_tenant(): void
    {
        $this->actingAs($this->admin);

        $otherOrgId = '22222222-2222-2222-2222-222222222222';

        $response = $this->patch(route('committees.update', [
            'organisation' => $otherOrgId,
            'committeeId' => $this->committeeId->value(),
        ]), [
            'name' => 'Updated Name',
        ]);

        $response->assertNotFound();
    }
}