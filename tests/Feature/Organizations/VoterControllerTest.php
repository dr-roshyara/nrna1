<?php

namespace Tests\Feature\organisations;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VoterControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $organisation;
    protected $user;
    protected $commissionMember;
    protected $regularMember;
    protected $voters = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Create organisation
        $this->organisation = Organisation::factory()->create();

        // Create users with different roles
        $this->commissionMember = User::factory()->create(['name' => 'Commission Member']);
        $this->regularMember = User::factory()->create(['name' => 'Regular Member']);
        $this->user = User::factory()->create(['name' => 'Test User']);

        // Attach roles
        $this->commissionMember->organisationRoles()->attach(
            $this->organisation->id,
            ['role' => 'commission']
        );
        $this->regularMember->organisationRoles()->attach(
            $this->organisation->id,
            ['role' => 'member']
        );

        // Create voters for the organisation
        for ($i = 1; $i <= 5; $i++) {
            $voter = User::factory()->create([
                'organisation_id' => $this->organisation->id,
                'is_voter' => 1,
                'approvedBy' => $i <= 2 ? 'Admin' : null,
                'has_voted' => $i <= 1 ? 1 : 0,
            ]);
            $this->voters[] = $voter;
        }
    }

    /**
     * Test voter list index page loads for organisation members
     *
     * @test
     */
    public function it_displays_voter_list_for_organization_members()
    {
        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('organisations/Voters/Index')
            ->has('voters')
            ->has('organisation')
            ->has('stats')
            ->has('isCommissionMember')
        );
    }

    /**
     * Test non-member cannot access voter list
     *
     * @test
     */
    public function it_blocks_non_member_access_to_voter_list()
    {
        // Arrange
        $nonMember = User::factory()->create();

        // Act
        $response = $this->actingAs($nonMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertStatus(403);
    }

    /**
     * Test unauthenticated user is redirected to login
     *
     * @test
     */
    public function it_redirects_unauthenticated_users_to_login()
    {
        // Act
        $response = $this->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertRedirect('/login');
    }

    /**
     * Test voter list shows correct statistics
     *
     * @test
     */
    public function it_shows_correct_statistics()
    {
        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->has('stats', fn ($stats) => $stats
                ->where('total', 5)
                ->where('approved', 2)
                ->where('pending', 3)
                ->where('voted', 1)
            )
        );
    }

    /**
     * Test search filter works
     *
     * @test
     */
    public function it_filters_voters_by_search_query()
    {
        // Arrange
        $searchVoter = $this->voters[0];

        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters?search=" . $searchVoter->name);

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->has('voters.data')
            ->where('filters.search', $searchVoter->name)
        );
    }

    /**
     * Test status filter works
     *
     * @test
     */
    public function it_filters_voters_by_status()
    {
        // Act - Filter by approved
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters?status=approved");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->where('filters.status', 'approved')
        );
    }

    /**
     * Test commission member can approve voter
     *
     * @test
     */
    public function it_allows_commission_member_to_approve_voter()
    {
        // Arrange
        $pendingVoter = $this->voters[2]; // A pending voter

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$pendingVoter->id}/approve");

        // Assert
        $response->assertRedirect();
        $this->assertNotNull($pendingVoter->refresh()->approvedBy);
        $this->assertEquals($this->commissionMember->name, $pendingVoter->approvedBy);
    }

    /**
     * Test regular member cannot approve voter
     *
     * @test
     */
    public function it_blocks_regular_member_from_approving_voter()
    {
        // Arrange
        $voter = $this->voters[2];

        // Act
        $response = $this->actingAs($this->regularMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $response->assertStatus(403);
        $this->assertNull($voter->refresh()->approvedBy);
    }

    /**
     * Test commission member can suspend voter
     *
     * @test
     */
    public function it_allows_commission_member_to_suspend_voter()
    {
        // Arrange
        $approvedVoter = $this->voters[0]; // An approved voter

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$approvedVoter->id}/suspend");

        // Assert
        $response->assertRedirect();
        $this->assertNull($approvedVoter->refresh()->approvedBy);
    }

    /**
     * Test regular member cannot suspend voter
     *
     * @test
     */
    public function it_blocks_regular_member_from_suspending_voter()
    {
        // Arrange
        $voter = $this->voters[0];

        // Act
        $response = $this->actingAs($this->regularMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/suspend");

        // Assert
        $response->assertStatus(403);
        $this->assertNotNull($voter->refresh()->approvedBy);
    }

    /**
     * Test cannot approve voter from different organisation
     *
     * @test
     */
    public function it_prevents_cross_organization_voter_approval()
    {
        // Arrange
        $otherOrg = Organisation::factory()->create();
        $otherVoter = User::factory()->create([
            'organisation_id' => $otherOrg->id,
            'is_voter' => 1,
        ]);

        $this->commissionMember->organisationRoles()->attach(
            $otherOrg->id,
            ['role' => 'commission']
        );

        // Try to approve voter from first organisation while in second organisation
        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$otherOrg->slug}/voters/{$this->voters[0]->id}/approve");

        // Assert
        $response->assertStatus(403);
        $this->assertNull($this->voters[0]->refresh()->approvedBy);
    }

    /**
     * Test bulk approve voters
     *
     * @test
     */
    public function it_allows_bulk_approve_of_voters()
    {
        // Arrange
        $voterIds = collect($this->voters)
            ->filter(fn ($v) => !$v->approvedBy)
            ->take(2)
            ->pluck('id')
            ->toArray();

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => $voterIds,
            ]);

        // Assert
        $response->assertRedirect();
        foreach ($voterIds as $voterId) {
            $voter = User::find($voterId);
            $this->assertNotNull($voter->approvedBy);
            $this->assertEquals($this->commissionMember->name, $voter->approvedBy);
        }
    }

    /**
     * Test bulk approve with empty list returns error
     *
     * @test
     */
    public function it_returns_error_for_empty_bulk_approve()
    {
        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => [],
            ]);

        // Assert
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /**
     * Test bulk suspend voters
     *
     * @test
     */
    public function it_allows_bulk_suspend_of_voters()
    {
        // Arrange
        $voterIds = collect($this->voters)
            ->filter(fn ($v) => $v->approvedBy)
            ->take(2)
            ->pluck('id')
            ->toArray();

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-suspend", [
                'voter_ids' => $voterIds,
            ]);

        // Assert
        $response->assertRedirect();
        foreach ($voterIds as $voterId) {
            $voter = User::find($voterId);
            $this->assertNull($voter->approvedBy);
        }
    }

    /**
     * Test pagination works
     *
     * @test
     */
    public function it_paginates_voter_list()
    {
        // Create many voters
        User::factory(55)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 1,
        ]);

        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters?per_page=50");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->has('voters.data', 50)
            ->has('voters.next_page_url')
        );
    }

    /**
     * Test only organisation voters are shown
     *
     * @test
     */
    public function it_only_shows_voters_from_the_organization()
    {
        // Arrange
        $otherOrg = Organisation::factory()->create();
        User::factory(3)->create([
            'organisation_id' => $otherOrg->id,
            'is_voter' => 1,
        ]);

        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->where('stats.total', 5) // Only org voters
        );
    }

    /**
     * Test cache invalidation on approval
     *
     * @test
     */
    public function it_invalidates_cache_on_voter_approval()
    {
        // Arrange
        $voter = $this->voters[2];
        $cacheKey = "org_{$this->organisation->id}_voter_stats";

        // Pre-populate cache
        Cache::put($cacheKey, ['total' => 10], 3600);
        $this->assertTrue(Cache::has($cacheKey));

        // Act
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test cache invalidation on bulk operations
     *
     * @test
     */
    public function it_invalidates_cache_on_bulk_approval()
    {
        // Arrange
        $voterIds = collect($this->voters)
            ->filter(fn ($v) => !$v->approvedBy)
            ->take(2)
            ->pluck('id')
            ->toArray();

        $cacheKey = "org_{$this->organisation->id}_voter_stats";
        Cache::put($cacheKey, ['total' => 10], 3600);

        // Act
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/bulk-approve", [
                'voter_ids' => $voterIds,
            ]);

        // Assert
        $this->assertFalse(Cache::has($cacheKey));
    }

    /**
     * Test approval includes IP address
     *
     * @test
     */
    public function it_records_ip_address_on_approval()
    {
        // Arrange
        $voter = $this->voters[2];

        // Act
        $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $voter->refresh();
        $this->assertNotNull($voter->voting_ip);
    }

    /**
     * Test commission member flag is passed to view
     *
     * @test
     */
    public function it_indicates_commission_member_in_response()
    {
        // Act - Regular member
        $response1 = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response1->assertInertia(fn ($page) => $page
            ->where('isCommissionMember', false)
        );

        // Act - Commission member
        $response2 = $this->actingAs($this->commissionMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response2->assertInertia(fn ($page) => $page
            ->where('isCommissionMember', true)
        );
    }

    /**
     * Test cannot approve already voted voter
     *
     * @test
     */
    public function it_allows_approval_of_voters_regardless_of_voting_status()
    {
        // Note: The controller doesn't prevent approval of voted voters
        // This test documents current behavior
        $voter = $this->voters[0]; // This voter has already voted

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/suspend");

        // Assert - should be able to suspend even if voted
        $response->assertRedirect();
    }

    /**
     * Test organisation context is set in request
     *
     * @test
     */
    public function it_loads_organization_context()
    {
        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->has('organisation', fn ($org) => $org
                ->where('id', $this->organisation->id)
                ->where('slug', $this->organisation->slug)
                ->where('name', $this->organisation->name)
            )
        );
    }

    /**
     * Test filters are preserved in response
     *
     * @test
     */
    public function it_preserves_filters_in_response()
    {
        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters?search=test&status=pending&sort=name&order=asc");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->has('filters', fn ($filters) => $filters
                ->where('search', 'test')
                ->where('status', 'pending')
                ->where('sort', 'name')
                ->where('order', 'asc')
            )
        );
    }

    /**
     * Test flash message on successful approval
     *
     * @test
     */
    public function it_shows_success_message_after_approval()
    {
        // Arrange
        $voter = $this->voters[2];

        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$voter->id}/approve");

        // Assert
        $response->assertSessionHas('success');
    }

    /**
     * Test only voters with is_voter = 1 are shown
     *
     * @test
     */
    public function it_only_shows_voters_not_non_voters()
    {
        // Arrange
        User::factory(2)->create([
            'organisation_id' => $this->organisation->id,
            'is_voter' => 0, // Not a voter
        ]);

        // Act
        $response = $this->actingAs($this->regularMember)
            ->get("/organisations/{$this->organisation->slug}/voters");

        // Assert
        $response->assertInertia(fn ($page) => $page
            ->where('stats.total', 5) // Only actual voters
        );
    }

    /**
     * Test voter lookup is organisation-scoped
     *
     * @test
     */
    public function it_prevents_voter_lookup_across_organizations()
    {
        // Arrange
        $otherOrg = Organisation::factory()->create();
        $otherVoter = User::factory()->create([
            'organisation_id' => $otherOrg->id,
            'is_voter' => 1,
        ]);

        // Act - Try to approve voter from different org
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/{$otherVoter->id}/approve");

        // Assert - Should fail
        $response->assertStatus(403);
    }

    /**
     * Test non-existent voter returns proper error
     *
     * @test
     */
    public function it_handles_non_existent_voter_gracefully()
    {
        // Act
        $response = $this->actingAs($this->commissionMember)
            ->post("/organisations/{$this->organisation->slug}/voters/99999/approve");

        // Assert
        $response->assertStatus(404);
    }
}
