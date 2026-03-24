<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrganisationVoterHubTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $member;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org    = Organisation::factory()->create(['type' => 'tenant']);
        $this->member = User::factory()->create();
        UserOrganisationRole::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('organisations.voter-hub', $this->org->slug))
             ->assertRedirect(route('login'));
    }

    public function test_non_member_cannot_access_voter_hub(): void
    {
        $this->actingAs(User::factory()->create())
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertRedirect(); // middleware redirects non-members
    }

    public function test_member_sees_voter_hub(): void
    {
        $this->actingAs($this->member)
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertStatus(200)
             ->assertInertia(fn ($page) =>
                 $page->component('Organisations/VoterHub')
                      ->has('organisation')
                      ->has('activeElections')
                      ->has('voterMemberships')
             );
    }

    public function test_voter_hub_shows_correct_membership_status(): void
    {
        $election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);
        ElectionMembership::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'has_voted'       => false,
        ]);

        $this->actingAs($this->member)
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->where('voterMemberships.' . $election->id . '.status', 'active')
                      ->where('voterMemberships.' . $election->id . '.has_voted', false)
             );
    }

    public function test_voter_hub_only_includes_active_elections(): void
    {
        Election::factory()->create([
            'organisation_id' => $this->org->id, 'type' => 'real', 'status' => 'planned',
        ]);
        $active = Election::factory()->create([
            'organisation_id' => $this->org->id, 'type' => 'real', 'status' => 'active',
        ]);

        $this->actingAs($this->member)
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->has('activeElections', 1)
                      ->where('activeElections.0.id', $active->id)
             );
    }
}
