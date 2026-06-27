<?php

declare(strict_types=1);

namespace Tests\Feature\Membership;

use App\Contexts\Membership\Infrastructure\Models\CommitteeModel;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class CommitteeManagementTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $regularUser;
    private CommitteeModel $committee;
    private Organisation $organisation;
    private string $assignmentId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::create([
            'id' => '11111111-1111-1111-1111-111111111111',
            'name' => 'Test Org',
            'code' => 'TEST',
            'slug' => 'test-org',
        ]);

        $this->admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
            'email_verified_at' => now(),
        ]);

        $this->assignRole($this->admin, $this->organisation, 'admin');

        $this->regularUser = User::create([
            'name' => 'Regular Member',
            'email' => 'member@test.com',
            'password' => bcrypt('password'),
            'organisation_id' => $this->organisation->id,
        ]);

        $this->committee = CommitteeModel::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'central',
            'status' => 'active',
            'geo_unit_id' => null,
        ]);

        $assignment = \Database\Factories\CommitteeAssignmentFactory::new()
            ->forOrganisation($this->organisation->id)
            ->forCommittee($this->committee->id)
            ->create();

        $this->assignmentId = $assignment['id'];
    }

    public function test_admin_can_remove_member_from_committee_via_http(): void
    {
        $response = $this->actingAs($this->admin)
            ->delete(route('committees.members.remove', [
                'organisation' => $this->organisation->slug,
                'committee' => $this->committee->slug,
                'assignmentId' => $this->assignmentId,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_guest_cannot_remove_member(): void
    {
        $response = $this->delete(route('committees.members.remove', [
            'organisation' => $this->organisation->slug,
            'committee' => $this->committee->slug,
            'assignmentId' => $this->assignmentId,
        ]));

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_remove_member(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->delete(route('committees.members.remove', [
                'organisation' => $this->organisation->slug,
                'committee' => $this->committee->slug,
                'assignmentId' => $this->assignmentId,
            ]));

        $response->assertForbidden();
    }

    public function test_admin_can_update_committee_name_via_http(): void
    {
        $newName = 'Updated Committee Name';

        $response = $this->actingAs($this->admin)
            ->patch(route('committees.update', [
                'organisation' => $this->organisation->slug,
                'committee' => $this->committee->slug,
            ]), [
                'name' => $newName,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');
    }

    public function test_guest_cannot_update_committee(): void
    {
        $response = $this->patch(route('committees.update', [
            'organisation' => $this->organisation->slug,
            'committee' => $this->committee->slug,
        ]), [
            'name' => 'Updated Name',
        ]);

        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_update_committee(): void
    {
        $response = $this->actingAs($this->regularUser)
            ->patch(route('committees.update', [
                'organisation' => $this->organisation->slug,
                'committee' => $this->committee->slug,
            ]), [
                'name' => 'Updated Name',
            ]);

        $response->assertForbidden();
    }
}