<?php

namespace Tests\Unit;

use App\Models\Candidacy;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\Post;
use App\Models\User;
use App\Services\BallotAssemblyService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * BallotAssemblyService: extraction contract tests.
 *
 * These assert the exact shape AND semantic content of the ballot-assembly
 * logic extracted from VoteController::create() (national/regional post +
 * candidate queries, and the election prop). Both the real vote flow and the
 * ballot preview feature depend on this service producing identical output to
 * what VoteController::create() built inline before the extraction — this
 * suite is what makes that extraction verifiable rather than "moved code and
 * hoped."
 */
class BallotAssemblyServiceTest extends TestCase
{
    use RefreshDatabase;

    private BallotAssemblyService $service;
    private Organisation $organisation;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(BallotAssemblyService::class);
        $this->organisation = Organisation::factory()->create(['type' => 'tenant']);
        $this->election = Election::factory()->create([
            'organisation_id' => $this->organisation->id,
            'type' => 'real',
        ]);
    }

    public function test_build_national_posts_returns_expected_shape_and_content(): void
    {
        $post = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'name' => 'President',
            'is_national_wide' => true,
            'required_number' => 1,
            'position_order' => 0,
        ]);

        $candidateUser = User::factory()->forOrganisation($this->organisation)->create(['name' => 'Jane Candidate']);
        $candidacy = Candidacy::factory()->create([
            'organisation_id' => $this->organisation->id,
            'post_id' => $post->id,
            'user_id' => $candidateUser->id,
            'status' => 'approved',
            'position_order' => 0,
            'image_path_1' => 'candidacy/some-org/photos/jane.jpg',
        ]);

        $result = $this->service->buildNationalPosts($this->election);

        $this->assertCount(1, $result);
        $postData = $result[0];
        $this->assertSame($post->id, $postData['post_id']);
        $this->assertSame('President', $postData['name']);
        $this->assertSame(1, $postData['required_number']);
        $this->assertCount(1, $postData['candidates']);

        $candidateData = $postData['candidates'][0];
        $this->assertSame($candidacy->id, $candidateData['candidacy_id']);
        $this->assertSame($candidateUser->id, $candidateData['user']['id']);
        $this->assertSame('Jane Candidate', $candidateData['user']['name']);
        $this->assertSame($post->id, $candidateData['post_id']);
        $this->assertSame('candidacy/some-org/photos/jane.jpg', $candidateData['image_path_1']);
        $this->assertSame(0, $candidateData['position_order']);
    }

    public function test_build_national_posts_excludes_regional_posts(): void
    {
        Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
        ]);

        $result = $this->service->buildNationalPosts($this->election);

        $this->assertCount(0, $result);
    }

    public function test_build_regional_posts_filters_by_given_region(): void
    {
        $bagmatiPost = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
            'name' => 'Bagmati Representative',
        ]);
        Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_national_wide' => false,
            'state_name' => 'Gandaki',
            'name' => 'Gandaki Representative',
        ]);

        $result = $this->service->buildRegionalPosts($this->election, 'Bagmati');

        $this->assertCount(1, $result);
        $this->assertSame($bagmatiPost->id, $result[0]['post_id']);
        $this->assertSame('Bagmati Representative', $result[0]['name']);
    }

    public function test_build_regional_posts_returns_empty_array_for_empty_region(): void
    {
        Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
        ]);

        $result = $this->service->buildRegionalPosts($this->election, '');

        $this->assertCount(0, $result);
    }

    public function test_build_election_prop_returns_expected_shape_including_has_regional_posts_flag(): void
    {
        $this->election->update(['description' => 'A test election']);
        Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
            'is_national_wide' => false,
            'state_name' => 'Bagmati',
        ]);

        $prop = $this->service->buildElectionProp($this->election);

        $this->assertSame($this->election->id, $prop['id']);
        $this->assertSame($this->election->name, $prop['name']);
        $this->assertSame('real', $prop['type']);
        $this->assertSame('A test election', $prop['description']);
        $this->assertArrayHasKey('is_active', $prop);
        $this->assertArrayHasKey('has_regional_posts', $prop);
        $this->assertTrue($prop['has_regional_posts']);
    }

    public function test_build_election_prop_has_regional_posts_is_false_when_none_exist(): void
    {
        $prop = $this->service->buildElectionProp($this->election);

        $this->assertFalse($prop['has_regional_posts']);
    }
}
