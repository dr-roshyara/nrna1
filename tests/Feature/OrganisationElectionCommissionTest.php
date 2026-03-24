<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionOfficer;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationElectionCommissionTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $owner;
    private User $voter;
    private User $chief;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org      = Organisation::factory()->create(['type' => 'tenant']);
        $this->owner    = User::factory()->create();
        $this->voter    = User::factory()->create();
        $this->chief    = User::factory()->create();
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id, 'type' => 'real', 'status' => 'active',
        ]);

        UserOrganisationRole::create(['user_id' => $this->owner->id, 'organisation_id' => $this->org->id, 'role' => 'owner']);
        UserOrganisationRole::create(['user_id' => $this->voter->id, 'organisation_id' => $this->org->id, 'role' => 'voter']);
        UserOrganisationRole::create(['user_id' => $this->chief->id, 'organisation_id' => $this->org->id, 'role' => 'voter']);

        ElectionOfficer::create([
            'user_id' => $this->chief->id, 'organisation_id' => $this->org->id,
            'election_id' => $this->election->id, 'role' => 'chief', 'status' => 'active',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('organisations.election-commission', $this->org->slug))
             ->assertRedirect(route('login'));
    }

    public function test_plain_voter_gets_403(): void
    {
        $this->actingAs($this->voter)
             ->get(route('organisations.election-commission', $this->org->slug))
             ->assertStatus(403);
    }

    public function test_owner_can_access_commission(): void
    {
        $this->actingAs($this->owner)
             ->get(route('organisations.election-commission', $this->org->slug))
             ->assertStatus(200)
             ->assertInertia(fn ($page) =>
                 $page->component('Organisations/ElectionCommission')
                      ->has('organisation')
                      ->has('elections')
                      ->has('stats')
             );
    }

    public function test_election_chief_can_access_commission(): void
    {
        $this->actingAs($this->chief)
             ->get(route('organisations.election-commission', $this->org->slug))
             ->assertStatus(200)
             ->assertInertia(fn ($page) =>
                 $page->component('Organisations/ElectionCommission')
             );
    }

    public function test_commission_includes_election_slugs_for_management_links(): void
    {
        $this->actingAs($this->owner)
             ->get(route('organisations.election-commission', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->has('elections.0.id')
                      ->has('elections.0.slug')
                      ->has('elections.0.status')
             );
    }

    public function test_non_member_cannot_access_commission(): void
    {
        $this->actingAs(User::factory()->create())
             ->get(route('organisations.election-commission', $this->org->slug))
             ->assertRedirect(); // middleware redirects non-members
    }
}
