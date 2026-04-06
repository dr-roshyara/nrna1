<?php

namespace Tests\Feature\Organisations;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

/**
 * Integration Tests for Voter Controller
 *
 * These tests verify the complete workflow and integration of
 * the voter management system with other components.
 */
class VoterControllerIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected $organisation;
    protected $commissionMember;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create();

        $this->commissionMember = User::factory()->create();
        $this->commissionMember->organisationRoles()->attach(
            $this->organisation->id,
            ['role' => 'commission']
        );
    }

    /**
     * Test complete voter approval workflow
     *
     * @test
     */
    public function it_handles_complete_voter_approval_workflow()
    {
        // Arrange
        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
            'approvedBy' => null,
        ]);

        // Act 1: View voter in list
        $response = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $response->assertInertia(fn ($page) => $page
            ->where('isCommissionMember', true)
        );

        // Act 2: Approve the voter
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $response->assertRedirect();
        $this->assertNotNull($voter->refresh()->approvedBy);

        // Act 3: View updated list
        $response = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $response->assertInertia(fn ($page) => $page
            ->where('stats.approved', 1)
        );
    }

    /**
     * Test complete voter suspension workflow
     *
     * @test
     */
    public function it_handles_complete_voter_suspension_workflow()
    {
        // Arrange
        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
            'approvedBy' => 'Admin',
        ]);

        // Act 1: Suspend the voter
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/suspend");

        $response->assertRedirect();

        // Act 2: Verify voter is suspended
        $voter->refresh();
        $this->assertNull($voter->approvedBy);

        // Act 3: Approve again if needed
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        $response->assertRedirect();
        $this->assertNotNull($voter->refresh()->approvedBy);
    }

    /**
     * Test bulk operations workflow
     *
     * @test
     */
    public function it_handles_bulk_operations_workflow()
    {
        // Arrange - Create multiple voters
        $voters = User::factory(10)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
            'approvedBy' => null,
        ]);

        $voterIds = $voters->pluck('id')->toArray();

        // Act 1: Bulk approve
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => $voterIds,
            ]);

        $response->assertRedirect();

        // Assert all approved
        foreach ($voters as $voter) {
            $this->assertNotNull($voter->refresh()->approvedBy);
        }

        // Act 2: Bulk suspend some
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-suspend", [
                'voter_ids' => array_slice($voterIds, 0, 5),
            ]);

        $response->assertRedirect();

        // Assert partial suspension
        for ($i = 0; $i < 5; $i++) {
            $this->assertNull($voters[$i]->refresh()->approvedBy);
        }

        for ($i = 5; $i < 10; $i++) {
            $this->assertNotNull($voters[$i]->refresh()->approvedBy);
        }
    }

    /**
     * Test pagination and filtering workflow
     *
     * @test
     */
    public function it_handles_pagination_and_filtering_workflow()
    {
        // Arrange - Create many voters with different statuses
        for ($i = 0; $i < 105; $i++) {
            User::factory()->create([
                'organisation_id' => $this->organisation->id,
                'is_voter' => 1,
                'approvedBy' => $i < 30 ? 'Admin' : null,
                'name' => "Voter {$i}",
            ]);
        }

        // Act 1: Get first page
        $response = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters?per_page=50");

        $response->assertInertia(fn ($page) => $page
            ->has('voters.data', 50)
            ->where('voters.current_page', 1)
            ->has('voters.next_page_url')
        );

        // Act 2: Get second page
        $response = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters?page=2&per_page=50");

        $response->assertInertia(fn ($page) => $page
            ->where('voters.current_page', 2)
            ->has('voters.prev_page_url')
        );

        // Act 3: Filter by status
        $response = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters?status=approved");

        $response->assertInertia(fn ($page) => $page
            ->where('filters.status', 'approved')
            ->where('stats.approved', 30)
        );

        // Act 4: Search
        $response = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters?search=Voter+1");

        $response->assertInertia(fn ($page) => $page
            ->where('filters.search', 'Voter 1')
        );
    }

    /**
     * Test cache behavior in real workflow
     *
     * @test
     */
    public function it_properly_manages_cache_across_operations()
    {
        // Arrange
        $voters = User::factory(5)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        $cacheKey = "org_{$this->organisation->id}_voter_stats";

        // Act 1: First request populates cache
        $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert cache is populated
        $this->assertTrue(Cache::has($cacheKey));

        // Act 2: Approve voter invalidates cache
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voters[0]->id}/approve");

        $this->assertFalse(Cache::has($cacheKey));

        // Act 3: New request re-populates with updated data
        $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        $this->assertTrue(Cache::has($cacheKey));
    }

    /**
     * Test multiple commission members can perform actions
     *
     * @test
     */
    public function it_allows_multiple_commission_members_to_perform_actions()
    {
        // Arrange
        $cm1 = User::factory()->create(['name' => 'CM1']);
        $cm2 = User::factory()->create(['name' => 'CM2']);

        $cm1->organisationRoles()->attach($this->organisation->id, ['role' => 'commission']);
        $cm2->organisationRoles()->attach($this->organisation->id, ['role' => 'commission']);

        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act 1: CM1 approves
        $this->actingAs($cm1)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        $this->assertEquals('CM1', $voter->refresh()->approvedBy);

        // Act 2: CM2 suspends
        $this->actingAs($cm2)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/suspend");

        $this->assertNull($voter->refresh()->approvedBy);

        // Act 3: CM2 approves
        $this->actingAs($cm2)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        $this->assertEquals('CM2', $voter->refresh()->approvedBy);
    }

    /**
     * Test member role escalation to commission role
     *
     * @test
     */
    public function it_grants_new_permissions_after_role_change()
    {
        // Arrange
        $member = User::factory()->create();
        $member->organisationRoles()->attach($this->organisation->id, ['role' => 'member']);

        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act 1: Member cannot approve
        $response = $this->actingAs($member)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        $response->assertStatus(403);

        // Act 2: Upgrade to commission
        $member->organisationRoles()->updateExistingPivot(
            $this->organisation->id,
            ['role' => 'commission']
        );

        // Act 3: Now can approve
        $response = $this->actingAs($member)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        $response->assertRedirect();
        $this->assertNotNull($voter->refresh()->approvedBy);
    }

    /**
     * Test organisation with no voters
     *
     * @test
     */
    public function it_handles_organization_with_no_voters()
    {
        // Arrange
        $emptyOrg = Organisation::factory()->create();
        $member = User::factory()->create();
        $member->organisationRoles()->attach($emptyOrg->id, ['role' => 'member']);

        // Act
        $response = $this->actingAs($member)
            ->get("/organisations/{$emptyOrg->slug}/voters");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->where('stats.total', 0)
            ->where('stats.approved', 0)
            ->where('stats.pending', 0)
            ->where('stats.voted', 0)
        );
    }

    /**
     * Test IP address tracking across approvals
     *
     * @test
     */
    public function it_tracks_ip_address_per_approval()
    {
        // Arrange
        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $voter->refresh();
        $this->assertNotNull($voter->voting_ip);
    }

    /**
     * Test concurrent bulk operations don't interfere
     *
     * @test
     */
    public function it_handles_sequential_bulk_operations()
    {
        // Arrange
        $voters1 = User::factory(5)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        $voters2 = User::factory(5)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act 1: Bulk approve first group
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => $voters1->pluck('id')->toArray(),
            ]);

        // Act 2: Bulk approve second group
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => $voters2->pluck('id')->toArray(),
            ]);

        // Assert all are approved
        foreach (array_merge($voters1->toArray(), $voters2->toArray()) as $voter) {
            $this->assertNotNull(User::find($voter['id'])->approvedBy);
        }
    }

    /**
     * Test flash messages are set correctly
     *
     * @test
     */
    public function it_sets_appropriate_flash_messages()
    {
        // Arrange
        $voter = User::factory()->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $response->assertSessionHas('success');
    }
}
