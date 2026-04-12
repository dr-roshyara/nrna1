<?php

namespace Tests\Feature\Election;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class ElectionVoterManagementTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $deputy;
    private User $commissioner;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()->forOrganisation($this->org)->real()->create([
            'status' => 'active',
        ]);

        $this->chief       = $this->makeOfficer('chief', 'active');
        $this->deputy      = $this->makeOfficer('deputy', 'active');
        $this->commissioner = $this->makeOfficer('commissioner', 'active');
    }

    // =========================================================================
    // APPROVE — single voter
    // =========================================================================

    public function test_chief_can_approve_a_voter(): void
    {
        $membership = $this->makeMembership('invited');

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.approve', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        $this->assertEquals('active', $membership->fresh()->status);
    }

    public function test_deputy_can_approve_a_voter(): void
    {
        $membership = $this->makeMembership('invited');

        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.approve', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        $this->assertEquals('active', $membership->fresh()->status);
    }

    public function test_commissioner_cannot_approve_a_voter(): void
    {
        $membership = $this->makeMembership('invited');

        $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.approve', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertForbidden();

        $this->assertEquals('invited', $membership->fresh()->status);
    }

    public function test_cannot_approve_already_active_voter(): void
    {
        $membership = $this->makeMembership('active');

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.approve', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        // Still active but with an error flash (not changed)
        $this->assertEquals('active', $membership->fresh()->status);
    }

    // =========================================================================
    // SUSPEND — single voter
    // =========================================================================

    public function test_chief_can_suspend_a_voter(): void
    {
        $membership = $this->makeMembership('active');

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.suspend', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        $this->assertEquals('inactive', $membership->fresh()->status);
    }

    public function test_deputy_can_suspend_a_voter(): void
    {
        $membership = $this->makeMembership('active');

        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.suspend', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        $this->assertEquals('inactive', $membership->fresh()->status);
    }

    public function test_commissioner_cannot_suspend_a_voter(): void
    {
        $membership = $this->makeMembership('active');

        $this->actingAs($this->commissioner)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.suspend', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertForbidden();

        $this->assertEquals('active', $membership->fresh()->status);
    }

    public function test_cannot_suspend_already_inactive_voter(): void
    {
        $membership = $this->makeMembership('inactive');

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.suspend', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        // Still inactive — unchanged
        $this->assertEquals('inactive', $membership->fresh()->status);
    }

    // =========================================================================
    // CROSS-ELECTION isolation
    // =========================================================================

    public function test_cannot_act_on_membership_belonging_to_different_election(): void
    {
        $otherElection = Election::factory()->forOrganisation($this->org)->real()->create([
            'status' => 'active',
        ]);
        // Membership in a DIFFERENT election, same org
        $membershipInOtherElection = $this->makeMembershipForElection($otherElection, 'invited');

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.approve', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,       // our election
                'membership'   => $membershipInOtherElection->id, // different election's membership
            ]))
            ->assertNotFound();
    }

    // =========================================================================
    // CROSS-ORG isolation
    // =========================================================================

    public function test_officer_from_different_org_cannot_approve_voter(): void
    {
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);
        $outsider = $this->makeOfficerInOrg('chief', 'active', $otherOrg);
        $membership = $this->makeMembership('invited');

        $response = $this->actingAs($outsider)
            ->withSession(['current_organisation_id' => $otherOrg->id])
            ->post(route('elections.voters.approve', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]));

        // 302 = ensure.organisation middleware redirects non-members before reaching the controller
        // 403 = policy blocks after middleware passes
        // 404 = BelongsToTenant scope hides the election
        $this->assertContains($response->status(), [302, 403, 404],
            'Cross-org voter approval must be blocked');
        $this->assertEquals('invited', $membership->fresh()->status);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function makeOfficer(string $role, string $status): User
    {
        return $this->makeOfficerInOrg($role, $status, $this->org);
    }

    private function makeOfficerInOrg(string $role, string $status, Organisation $org): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $org->id,
            'role'            => 'voter',
        ]);
        ElectionOfficer::create([
            'organisation_id' => $org->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => $status,
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => $status === 'active' ? now() : null,
        ]);
        return $user;
    }

    private function makeMembership(string $status): ElectionMembership
    {
        return $this->makeMembershipForElection($this->election, $status);
    }

    private function makeMembershipForElection(Election $election, string $status): ElectionMembership
    {
        $voter = User::factory()->create(['organisation_id' => $this->org->id]);
        // Required by composite FK: (user_id, organisation_id) → user_organisation_roles
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        return ElectionMembership::create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => $status,
            'assigned_by'     => $this->chief->id,
            'assigned_at'     => now(),
        ]);
    }
}
