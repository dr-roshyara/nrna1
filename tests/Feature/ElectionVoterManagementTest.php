<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

/**
 * Feature tests for the ElectionVoterController.
 *
 * Covers: voter list (index), assign (store), bulk assign, remove (destroy),
 * export (CSV), policy enforcement, and ElectionPage eligibility flag.
 *
 * TDD: all tests written first, confirmed RED, then implementation added.
 *
 * Architecture refs:
 *   20260317_2314_implementation_realvote_electionmembership.md
 *   20260317_2316_build_election_page.md
 */
class ElectionVoterManagementTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $committee;   // role = commission  → can manage voters
    private User $voter;       // role = voter       → can only view
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Reset BelongsToTenant static cache — it persists across tests and
        // would poison queries with a stale platform org ID.
        Election::resetPlatformOrgCache();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        // Set session BEFORE creating users so UserFactory's BelongsToTenant
        // creating hook uses this org instead of the platform org fallback.
        session(['current_organisation_id' => $this->org->id]);

        // Committee member (commission role)
        $this->committee = User::factory()->create(['email_verified_at' => now()]);
        $this->attachToOrg($this->committee, 'commission');

        // Regular voter — organisation_id must match $this->org so TenantContext
        // middleware sets session('current_organisation_id') correctly on requests.
        $this->voter = User::factory()->create([
            'email_verified_at'  => now(),
            'organisation_id'    => $this->org->id,
        ]);
        $this->attachToOrg($this->voter, 'voter');

        // Real election for this org
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function attachToOrg(User $user, string $role): void
    {
        $this->org->users()->attach($user->id, [
            'id'   => (string) Str::uuid(),
            'role' => $role,
        ]);
    }

    /** GET the voter management index page as a given user */
    private function getVoterIndex(User $user)
    {
        return $this->actingAs($user)
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]));
    }

    // =========================================================================
    // TEST 1 — index: committee member sees voter list
    // =========================================================================

    public function test_committee_member_can_view_voter_list(): void
    {
        // Assign a voter so there is data to show
        ElectionMembership::assignVoter($this->voter->id, $this->election->id, $this->committee->id);

        $response = $this->getVoterIndex($this->committee);

        $response->assertOk();
        $response->assertInertia(fn ($page) => $page
            ->component('Elections/Voters/Index')
            ->has('voters')
            ->has('stats')
            ->has('election')
            ->has('organisation')
        );
    }

    // =========================================================================
    // TEST 2 — index: outsider is redirected (ensure.organisation middleware)
    // =========================================================================

    public function test_non_member_cannot_view_voter_list(): void
    {
        $outsider = User::factory()->create(['email_verified_at' => now()]);

        $response = $this->actingAs($outsider)
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]));

        // ensure.organisation middleware redirects non-members to dashboard
        $response->assertRedirect();
    }

    // =========================================================================
    // TEST 3 — store: committee member can assign a voter
    // =========================================================================

    public function test_committee_member_can_assign_voter(): void
    {
        $newMember = User::factory()->create(['email_verified_at' => now()]);
        $this->attachToOrg($newMember, 'voter');

        $response = $this->actingAs($this->committee)
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]), [
                'user_id' => $newMember->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('election_memberships', [
            'user_id'     => $newMember->id,
            'election_id' => $this->election->id,
            'role'        => 'voter',
            'status'      => 'active',
        ]);
    }

    // =========================================================================
    // TEST 4 — store: assigning a non-org-member returns a validation error
    // =========================================================================

    public function test_assign_non_org_member_is_rejected(): void
    {
        $outsider = User::factory()->create(['email_verified_at' => now()]);
        // NOT attached to $this->org

        $response = $this->actingAs($this->committee)
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]), [
                'user_id' => $outsider->id,
            ]);

        $response->assertRedirect();
        $response->assertSessionHasErrors('user_id');

        $this->assertDatabaseMissing('election_memberships', [
            'user_id'     => $outsider->id,
            'election_id' => $this->election->id,
        ]);
    }

    // =========================================================================
    // TEST 5 — destroy: committee member can remove a voter
    // =========================================================================

    public function test_committee_member_can_remove_voter(): void
    {
        $membership = ElectionMembership::assignVoter(
            $this->voter->id,
            $this->election->id,
            $this->committee->id
        );

        $response = $this->actingAs($this->committee)
            ->delete(route('elections.voters.destroy', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertEquals('removed', $membership->fresh()->status);
        $this->assertArrayHasKey('removed_reason', $membership->fresh()->metadata);
    }

    // =========================================================================
    // TEST 6 — store: regular voter role cannot manage voters (policy denies)
    // =========================================================================

    public function test_regular_voter_cannot_manage_voters(): void
    {
        $newMember = User::factory()->create(['email_verified_at' => now()]);
        $this->attachToOrg($newMember, 'voter');

        $response = $this->actingAs($this->voter)
            ->post(route('elections.voters.store', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]), [
                'user_id' => $newMember->id,
            ]);

        $response->assertStatus(403);
        $this->assertDatabaseMissing('election_memberships', [
            'user_id'     => $newMember->id,
            'election_id' => $this->election->id,
        ]);
    }

    // =========================================================================
    // TEST 7 — bulk store: committee member can bulk assign voters
    // =========================================================================

    public function test_committee_member_can_bulk_assign_voters(): void
    {
        $m1 = User::factory()->create(['email_verified_at' => now()]);
        $m2 = User::factory()->create(['email_verified_at' => now()]);
        $this->attachToOrg($m1, 'voter');
        $this->attachToOrg($m2, 'voter');

        $response = $this->actingAs($this->committee)
            ->post(route('elections.voters.bulk', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]), [
                'user_ids' => [$m1->id, $m2->id],
            ]);

        $response->assertRedirect();
        $response->assertSessionHas('bulk_result');

        $result = session('bulk_result');
        $this->assertEquals(2, $result['success']);
        $this->assertEquals(0, $result['invalid']);
    }

    // =========================================================================
    // TEST 8 — export: returns CSV download for committee member
    // =========================================================================

    public function test_committee_member_can_export_voter_csv(): void
    {
        ElectionMembership::assignVoter($this->voter->id, $this->election->id, $this->committee->id);

        $response = $this->actingAs($this->committee)
            ->get(route('elections.voters.export', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
            ]));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
    }

    // =========================================================================
    // TEST 9 — demo election returns 404 from voter management routes
    // =========================================================================

    public function test_demo_election_voter_index_returns_404(): void
    {
        $demoElection = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'demo',
        ]);

        $response = $this->actingAs($this->committee)
            ->get(route('elections.voters.index', [
                'organisation' => $this->org->slug,
                'election'     => $demoElection->id,
            ]));

        $response->assertNotFound();
    }

    // =========================================================================
    // TEST 10 — ElectionPage: eligible voter has is_eligible = true in props
    // =========================================================================

    public function test_election_page_passes_membership_eligibility_as_true(): void
    {
        // The voter is in election_memberships with active status
        ElectionMembership::assignVoter($this->voter->id, $this->election->id);

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('election.dashboard'));

        // Controller must pass is_eligible = true when user has active membership
        $response->assertInertia(fn ($page) => $page
            ->where('authUser.is_eligible', true)
        );
    }

    // =========================================================================
    // TEST 11 — ElectionPage: non-member voter has is_eligible = false
    // =========================================================================

    public function test_election_page_marks_non_member_voter_as_ineligible(): void
    {
        // voter is NOT in election_memberships for this election

        $response = $this->actingAs($this->voter)
            ->withSession(['current_organisation_id' => $this->org->id])
            ->get(route('election.dashboard'));

        $response->assertInertia(fn ($page) => $page
            ->where('authUser.is_eligible', false)
        );
    }
}
