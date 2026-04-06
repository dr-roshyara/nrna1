<?php

namespace Tests\Feature;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ElectionCandidacyApplyPageTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $member;
    private Election $election;
    private Post $post;

    protected function setUp(): void
    {
        parent::setUp();
        $this->org      = Organisation::factory()->create(['type' => 'tenant']);
        $this->member   = User::factory()->create();
        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'real',
            'status'          => 'active',
        ]);
        $this->post = Post::factory()->forElection($this->election)->create();
        UserOrganisationRole::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
    }

    public function test_guest_is_redirected_to_login(): void
    {
        $this->get(route('organisations.elections.candidacy.apply', [
            'organisation' => $this->org->slug,
            'election'     => $this->election->slug,
        ]))->assertRedirect(route('login'));
    }

    public function test_non_member_is_redirected(): void
    {
        $nonMember = User::factory()->create();

        $this->actingAs($nonMember)
             ->get(route('organisations.elections.candidacy.apply', [
                 'organisation' => $this->org->slug,
                 'election'     => $this->election->slug,
             ]))
             ->assertRedirect(); // ensure.organisation middleware
    }

    public function test_member_can_view_page(): void
    {
        $this->actingAs($this->member)
             ->get(route('organisations.elections.candidacy.apply', [
                 'organisation' => $this->org->slug,
                 'election'     => $this->election->slug,
             ]))
             ->assertOk()
             ->assertInertia(fn ($page) =>
                 $page->component('Election/Candidacy/Apply')
                      ->has('organisation')
                      ->has('election')
                      ->has('posts')
                      ->has('existingApplication')
             );
    }

    public function test_page_exposes_posts_for_this_election(): void
    {
        $secondPost = Post::factory()->forElection($this->election)->create();

        $this->actingAs($this->member)
             ->get(route('organisations.elections.candidacy.apply', [
                 'organisation' => $this->org->slug,
                 'election'     => $this->election->slug,
             ]))
             ->assertInertia(fn ($page) =>
                 $page->has('posts', 2)
             );
    }

    public function test_existing_application_is_exposed_in_props(): void
    {
        CandidacyApplication::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'post_id'         => $this->post->id,
            'supporter_name'  => 'John',
            'proposer_name'   => 'Jane',
            'status'          => 'pending',
        ]);

        $this->actingAs($this->member)
             ->get(route('organisations.elections.candidacy.apply', [
                 'organisation' => $this->org->slug,
                 'election'     => $this->election->slug,
             ]))
             ->assertInertia(fn ($page) =>
                 $page->where('existingApplication.status', 'pending')
             );
    }

    public function test_demo_election_returns_404(): void
    {
        $demoElection = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'demo',
            'status'          => 'active',
        ]);

        $this->actingAs($this->member)
             ->get(route('organisations.elections.candidacy.apply', [
                 'organisation' => $this->org->slug,
                 'election'     => $demoElection->slug,
             ]))
             ->assertNotFound();
    }
}
