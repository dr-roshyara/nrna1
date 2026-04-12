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

class ElectionVoterSuspensionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private Election $election;
    private User $chief;
    private User $deputy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->org->id]);

        $this->election = Election::factory()->forOrganisation($this->org)->real()->create([
            'status' => 'active',
        ]);

        $this->chief  = $this->makeOfficer('chief');
        $this->deputy = $this->makeOfficer('chief');
    }

    // =========================================================================
    // PROPOSE SUSPENSION
    // =========================================================================

    public function test_officer_can_propose_suspension(): void
    {
        $membership = $this->makeMembership('active');

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.propose-suspension', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect();

        $this->assertEquals('proposed', $membership->fresh()->suspension_status);
        $this->assertEquals('active', $membership->fresh()->status); // not suspended yet
    }

    public function test_cannot_propose_suspension_for_voted_voter(): void
    {
        $membership = $this->makeMembership('active');
        $membership->update(['has_voted' => true]);

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.propose-suspension', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals('none', $membership->fresh()->suspension_status);
    }

    public function test_cannot_propose_suspension_when_already_proposed(): void
    {
        $membership = $this->makeMembership('active');
        $membership->proposeSuspension($this->chief);

        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.propose-suspension', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('error');
    }

    // =========================================================================
    // CONFIRM SUSPENSION
    // =========================================================================

    public function test_second_officer_can_confirm_suspension(): void
    {
        $membership = $this->makeMembership('active');
        $membership->proposeSuspension($this->chief);

        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.confirm-suspension', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('inactive', $membership->fresh()->status);
        $this->assertEquals('confirmed', $membership->fresh()->suspension_status);
    }

    public function test_proposer_cannot_confirm_own_proposal(): void
    {
        $membership = $this->makeMembership('active');
        $membership->proposeSuspension($this->chief);

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.confirm-suspension', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('error');

        $this->assertEquals('active', $membership->fresh()->status);
    }

    // =========================================================================
    // CANCEL PROPOSAL
    // =========================================================================

    public function test_proposer_can_cancel_proposal(): void
    {
        $membership = $this->makeMembership('active');
        $membership->proposeSuspension($this->chief);

        $this->actingAs($this->chief)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.cancel-proposal', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('none', $membership->fresh()->suspension_status);
        $this->assertNull($membership->fresh()->suspension_proposed_by);
    }

    public function test_non_proposer_cannot_cancel_proposal(): void
    {
        $membership = $this->makeMembership('active');
        $membership->proposeSuspension($this->chief);

        $this->actingAs($this->deputy)
            ->withSession($this->orgSession())
            ->post(route('elections.voters.cancel-proposal', [
                'organisation' => $this->org->slug,
                'election'     => $this->election->id,
                'membership'   => $membership->id,
            ]))
            ->assertStatus(403);
    }

    // =========================================================================
    // Helpers
    // =========================================================================

    private function orgSession(): array
    {
        return ['current_organisation_id' => $this->org->id];
    }

    private function makeOfficer(string $role): User
    {
        $user = User::factory()->create([
            'organisation_id'   => $this->org->id,
            'email_verified_at' => now(),
        ]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $user->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        ElectionOfficer::create([
            'organisation_id' => $this->org->id,
            'user_id'         => $user->id,
            'role'            => $role,
            'status'          => 'active',
            'appointed_by'    => $user->id,
            'appointed_at'    => now(),
            'accepted_at'     => now(),
        ]);
        return $user;
    }

    private function makeMembership(string $status): ElectionMembership
    {
        $voter = User::factory()->create(['organisation_id' => $this->org->id]);
        UserOrganisationRole::create([
            'id'              => (string) Str::uuid(),
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        return ElectionMembership::create([
            'user_id'         => $voter->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => $status,
            'assigned_by'     => $this->chief->id,
            'assigned_at'     => now(),
        ]);
    }
}
