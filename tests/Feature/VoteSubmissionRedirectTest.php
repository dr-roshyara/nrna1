<?php

namespace Tests\Feature;

use App\Models\Code;
use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Organisation;
use App\Models\ElectionMembership;
use App\Models\Post;
use App\Models\Candidacy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteSubmissionRedirectTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $organisation;
    private Election $election;
    private User $user;
    private VoterSlug $voterSlug;
    private Code $code;
    private Post $post;
    private Candidacy $candidacy;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        session(['current_organisation_id' => $this->organisation->id]);

        $this->user = User::factory()->forOrganisation($this->organisation)->create();
        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'real',
            'status' => 'active',
        ]);

        ElectionMembership::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'role' => 'voter',
            'status' => 'active',
        ]);

        // Create a post and candidacy for valid vote data
        $this->post = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $this->candidacy = Candidacy::factory()->create([
            'post_id' => $this->post->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $this->voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'test-voter-slug',
            'is_active' => true,
            'status' => 'active',
            'current_step' => 3,
            'expires_at' => now()->addHour(),
        ]);

        $this->code = Code::factory()->create([
            'user_id' => $this->user->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'can_vote_now' => true,
            'has_code1_sent' => true,
            'is_code_to_open_voting_form_usable' => true,
        ]);
    }

    public function test_first_submission_redirects_to_verify_not_code_create(): void
    {
        $this->actingAs($this->user);

        // Verify code state before submission
        $codeBefore = Code::withoutGlobalScopes()->find($this->code->id);
        $this->assertTrue($codeBefore->can_vote_now, 'Code should be votable before submission');
        $this->assertTrue($codeBefore->has_code1_sent, 'Code1 should be sent');
        $this->assertTrue($codeBefore->is_code_to_open_voting_form_usable, 'Form code should be usable');

        $response = $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'user_id' => $this->user->id,
                'agree_button' => true,
                'national_selected_candidates' => [
                    [
                        'post_id' => $this->post->id,
                        'post_name' => 'Test Post',
                        'required_number' => 1,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $this->candidacy->id]
                        ]
                    ]
                ]
            ]
        );

        // Use assertRedirectToRoute which properly handles slug-based routes
        $response->assertRedirectToRoute('slug.vote.verify', ['vslug' => $this->voterSlug->slug]);
    }

    public function test_vote_submitted_flag_persisted_after_first_submission(): void
    {
        $this->actingAs($this->user);

        $this->post(
            route('slug.vote.submit', ['vslug' => $this->voterSlug->slug]),
            [
                'agree_button' => true,
                'national_selected_candidates' => [
                    [
                        'post_id' => $this->post->id,
                        'post_name' => 'Test Post',
                        'required_number' => 1,
                        'no_vote' => false,
                        'candidates' => [
                            ['candidacy_id' => $this->candidacy->id]
                        ]
                    ]
                ]
            ]
        );

        $updatedCode = Code::withoutGlobalScopes()->find($this->code->id);

        $this->assertTrue($updatedCode->vote_submitted, 'vote_submitted should be true after first_submission');
        $this->assertNotNull($updatedCode->vote_submitted_at, 'vote_submitted_at should be set');
    }

    public function test_vote_pre_check_allows_submitted_votes(): void
    {
        $this->code->vote_submitted = true;
        $this->code->save();

        $this->actingAs($this->user);

        $controller = new \App\Http\Controllers\VoteController();
        $result = $controller->vote_pre_check($this->code);

        $this->assertEquals("", $result, 'vote_pre_check should return empty string for submitted votes');
    }
}
