<?php

namespace Tests\Feature;

use App\Models\CandidacyApplication;
use App\Models\Election;
use App\Models\ElectionMembership;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CandidacyApplicationTest extends TestCase
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
            'state'           => 'nomination',
        ]);
        $this->post = Post::factory()->forElection($this->election)->create();
        UserOrganisationRole::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'role'            => 'voter',
        ]);
        ElectionMembership::create([
            'user_id'         => $this->member->id,
            'election_id'     => $this->election->id,
            'organisation_id' => $this->org->id,
            'status'          => 'active',
        ]);
    }

    public function test_guest_cannot_submit_application(): void
    {
        $this->post(route('organisations.candidacy.apply', $this->org->slug), [])
             ->assertRedirect(route('login'));
    }

    public function test_non_member_cannot_submit_application(): void
    {
        $this->actingAs(User::factory()->create())
             ->post(route('organisations.candidacy.apply', $this->org->slug), [])
             ->assertRedirect();
    }

    public function test_member_can_submit_valid_application(): void
    {
        Storage::fake('public');

        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $this->post->id,
                 'supporter_name' => 'John Supporter',
                 'proposer_name'  => 'Jane Proposer',
                 'manifesto'      => 'I will serve the community with dedication.',
             ])
             ->assertStatus(200);

        $this->assertDatabaseHas('candidacy_applications', [
            'user_id'        => $this->member->id,
            'election_id'    => $this->election->id,
            'post_id'        => $this->post->id,
            'supporter_name' => 'John Supporter',
            'proposer_name'  => 'Jane Proposer',
            'status'         => 'pending',
        ]);
    }

    public function test_application_requires_supporter_name(): void
    {
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'   => $this->election->id,
                 'post_id'       => $this->post->id,
                 'proposer_name' => 'Jane Proposer',
             ])
             ->assertSessionHasErrors('supporter_name');
    }

    public function test_application_requires_proposer_name(): void
    {
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $this->post->id,
                 'supporter_name' => 'John Supporter',
             ])
             ->assertSessionHasErrors('proposer_name');
    }

    public function test_cannot_apply_twice_for_same_election(): void
    {
        CandidacyApplication::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'post_id'         => $this->post->id,
            'supporter_name'  => 'John Supporter',
            'proposer_name'   => 'Jane Proposer',
            'status'          => 'pending',
        ]);

        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $this->post->id,
                 'supporter_name' => 'Another Supporter',
                 'proposer_name'  => 'Another Proposer',
             ])
             ->assertSessionHasErrors('form');
    }

    public function test_cannot_apply_for_different_post_in_same_election(): void
    {
        $secondPost = Post::factory()->forElection($this->election)->create();

        CandidacyApplication::create([
            'user_id'         => $this->member->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'post_id'         => $this->post->id,
            'supporter_name'  => 'John Supporter',
            'proposer_name'   => 'Jane Proposer',
            'status'          => 'pending',
        ]);

        // Attempt to apply for a DIFFERENT post in the same election — must be blocked
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $secondPost->id,
                 'supporter_name' => 'Another Supporter',
                 'proposer_name'  => 'Another Proposer',
             ])
             ->assertSessionHasErrors('form');
    }

    public function test_photo_is_uploaded_and_stored(): void
    {
        Storage::fake('public');
        $photo = UploadedFile::fake()->image('candidate.jpg', 300, 300);

        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $this->post->id,
                 'supporter_name' => 'John Supporter',
                 'proposer_name'  => 'Jane Proposer',
                 'photo'          => $photo,
             ]);

        $application = CandidacyApplication::first();
        $this->assertNotNull($application->photo);
        Storage::disk('public')->assertExists($application->photo);
    }

    public function test_photo_must_be_image(): void
    {
        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $this->post->id,
                 'supporter_name' => 'John Supporter',
                 'proposer_name'  => 'Jane Proposer',
                 'photo'          => UploadedFile::fake()->create('document.pdf', 100),
             ])
             ->assertSessionHasErrors('photo');
    }

    public function test_cannot_apply_for_demo_election(): void
    {
        $demoElection = Election::factory()->create([
            'organisation_id' => $this->org->id,
            'type'            => 'demo',
            'state'           => 'nomination',
        ]);
        $demoPost = Post::factory()->forElection($demoElection)->create();

        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $demoElection->id,
                 'post_id'        => $demoPost->id,
                 'supporter_name' => 'John Supporter',
                 'proposer_name'  => 'Jane Proposer',
             ])
             ->assertStatus(404);
    }

    public function test_cannot_apply_when_election_not_in_nomination(): void
    {
        $this->election->update(['state' => 'administration']);

        $this->actingAs($this->member)
             ->post(route('organisations.candidacy.apply', $this->org->slug), [
                 'election_id'    => $this->election->id,
                 'post_id'        => $this->post->id,
                 'supporter_name' => 'John Supporter',
                 'proposer_name'  => 'Jane Proposer',
             ])
             ->assertStatus(403);
    }

    public function test_voter_hub_includes_my_applications(): void
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
             ->get(route('organisations.voter-hub', $this->org->slug))
             ->assertInertia(fn ($page) =>
                 $page->has('myApplications', 1)
                      ->where('myApplications.0.status', 'pending')
             );
    }
}
