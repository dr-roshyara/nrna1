<?php

namespace Tests\Feature\Membership;

use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeAssignmentId;
use App\Contexts\Membership\Domain\ValueObjects\CommitteeId;
use App\Contexts\Membership\Domain\ValueObjects\MemberId;
use App\Contexts\Shared\Domain\ValueObjects\TenantId;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CommitteeManagementHttpTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private User $admin;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();
        $this->admin = User::factory()->create(['organisation_id' => $this->organisation->id]);
        $this->member = User::factory()->create(['organisation_id' => $this->organisation->id]);

        UserOrganisationRole::where('user_id', $this->admin->id)
            ->where('organisation_id', $this->organisation->id)
            ->update(['role' => 'admin']);
    }

    public function test_guest_cannot_create_committee(): void
    {
        $response = $this->get(route('committees.create', ['organisation' => $this->organisation]));
        $response->assertRedirect(route('login'));
    }

    public function test_non_admin_cannot_create_committee(): void
    {
        $response = $this->actingAs($this->member)
            ->get(route('committees.create', ['organisation' => $this->organisation]));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_create_form(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('committees.create', ['organisation' => $this->organisation]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Committee/Create')
            ->has('committeeTypes', 4)
        );
    }

    public function test_admin_can_create_committee(): void
    {
        $response = $this->actingAs($this->admin)
            ->post(route('committees.store', ['organisation' => $this->organisation]), [
                'name' => 'Test Committee',
                'code' => 'TEST-001',
                'type' => 'district',
                'geo_reference' => 'np.3.15',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Committee created successfully.');

        $this->assertDatabaseHas('committees', [
            'organisation_id' => $this->organisation->id,
            'name' => 'Test Committee',
            'code' => 'TEST-001',
            'type' => 'district',
        ]);
    }

    public function test_admin_can_create_committee_with_geo_selections(): void
    {
        $this->organisation->update([
            'geographic_levels' => [
                ['index' => 1, 'type' => 'static', 'db_level' => null, 'label' => 'Worldwide', 'local_label' => null, 'required' => true],
                ['index' => 2, 'type' => 'region', 'db_level' => null, 'label' => 'Region', 'local_label' => null, 'required' => true],
                ['index' => 3, 'type' => 'country', 'db_level' => null, 'label' => 'Country', 'local_label' => null, 'required' => true],
                ['index' => 4, 'type' => 'geo_unit', 'db_level' => 1, 'label' => 'Province', 'local_label' => null, 'required' => false],
            ],
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('committees.store', ['organisation' => $this->organisation]), [
                'name' => 'Europe Committee',
                'code' => 'EU-001',
                'type' => 'district',
                'geo_selections' => [
                    'region' => 'europe',
                    'country' => 'de',
                    'geo' => [3, 15],
                ],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Committee created successfully.');

        $this->assertDatabaseHas('committees', [
            'organisation_id' => $this->organisation->id,
            'name' => 'Europe Committee',
            'code' => 'EU-001',
            'type' => 'district',
            'region_code' => 'europe',
            'country_code' => 'DE',
        ]);
    }

    public function test_admin_can_view_edit_form(): void
    {
        $committee = $this->createTestCommittee();

        $response = $this->actingAs($this->admin)
            ->get(route('committees.edit', [
                'organisation' => $this->organisation,
                'committeeId' => $committee->id,
            ]));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Committee/Edit')
            ->where('committee.id', $committee->id)
            ->where('committee.name', $committee->name)
        );
    }

    public function test_admin_can_update_committee(): void
    {
        $committee = $this->createTestCommittee();

        $response = $this->actingAs($this->admin)
            ->patch(route('committees.update', [
                'organisation' => $this->organisation,
                'committeeId' => $committee->id,
            ]), [
                'name' => 'Updated Committee',
                'status' => 'inactive',
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success', 'Committee updated successfully.');

        $this->assertDatabaseHas('committees', [
            'id' => $committee->id,
            'name' => 'Updated Committee',
            'status' => 'inactive',
        ]);
    }

    public function test_admin_can_search_members(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('members.search', ['organisation' => $this->organisation]) . '?q=' . substr($this->member->name, 0, 3));

        $response->assertStatus(200);
        $response->assertJsonCount(1);
        $response->assertJsonFragment(['id' => $this->member->id]);
    }

    public function test_member_search_requires_minimum_query_length(): void
    {
        $response = $this->actingAs($this->admin)
            ->get(route('members.search', ['organisation' => $this->organisation]), [
                'q' => 'a',
            ]);

        $response->assertStatus(200);
        $response->assertJsonCount(0);
    }

    public function test_assign_member_route_exists(): void
    {
        $committee = $this->createTestCommittee();

        $response = $this->actingAs($this->admin)
            ->post(route('committees.members.assign', [
                'organisation' => $this->organisation,
                'committeeId' => $committee->id,
            ]), [
                'member_id' => $this->member->id,
                'role_path' => '1.1',
                'nomination_type' => 'appointed',
            ]);

        $this->assertContains($response->status(), [200, 302, 422]);
    }

    public function test_remove_member_route_exists(): void
    {
        $committee = $this->createTestCommittee();

        $response = $this->actingAs($this->admin)
            ->delete(route('committees.members.remove', [
                'organisation' => $this->organisation,
                'committeeId' => $committee->id,
                'assignmentId' => CommitteeAssignmentId::generate()->value(),
            ]));

        $this->assertContains($response->status(), [200, 302, 404, 422]);
    }

    private function createTestCommittee()
    {
        $code = 'TEST-' . rand(1000, 9999);
        $slug = \Illuminate\Support\Str::slug('Test Committee') . '-' . rand(1000, 9999);
        \DB::table('committees')->insert([
            'id' => CommitteeId::generate()->value(),
            'organisation_id' => $this->organisation->id,
            'name' => 'Test Committee',
            'code' => $code,
            'slug' => $slug,
            'type' => 'district',
            'level' => 3,
            'status' => 'active',
            'operational_geo_reference' => 'np.3.15',
            'formation_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return \DB::table('committees')->where('code', $code)->first();
    }

    private function createTestAssignment($committee, User $member)
    {
        $id = CommitteeAssignmentId::generate()->value();
        \DB::table('committee_assignments')->insert([
            'id' => $id,
            'committee_id' => $committee->id,
            'member_id' => $member->id,
            'organisation_id' => $this->organisation->id,
            'role_path' => '1.1',
            'nomination_type' => 'appointed',
            'is_active' => true,
            'joined_date' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        return \DB::table('committee_assignments')->where('id', $id)->first();
    }
}
