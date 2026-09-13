<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Support\ElectionScenarioFactory;
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

    /**
     * Boundary B one-liner (see Ballot Preview plan §7): the existing "Receipt
     * Codes" quick-action tile in VoterHub.vue is gated on
     * election.results_published_at, but voterHub()'s activeElections map never
     * included that field — the tile has been silently dead. Named and tested
     * separately from the ballot-preview feature's own suite.
     */
    public function test_voter_hub_includes_results_published_at_field(): void
    {
        $election = ElectionScenarioFactory::resultsPublished($this->org);

        $this->actingAs($this->member)
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->has('activeElections', 1)
                      ->where('activeElections.0.results_published_at', fn ($value) => $value !== null)
             );
    }

    public function test_voter_hub_includes_can_preview_ballot_true_during_voting_active(): void
    {
        $election = ElectionScenarioFactory::votingActive($this->org);

        $this->actingAs($this->member)
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->where('activeElections.0.can_preview_ballot', true)
             );
    }

    public function test_voter_hub_includes_can_preview_ballot_false_during_setup_nomination(): void
    {
        $election = ElectionScenarioFactory::setupNomination($this->org);

        $this->actingAs($this->member)
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->where('activeElections.0.can_preview_ballot', false)
             );
    }
}
