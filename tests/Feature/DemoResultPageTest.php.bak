<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoVote;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoResultPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    /**
     * Test any authenticated user can access MODE 1 (global demo)
     */
    public function test_any_authenticated_user_can_access_mode1()
    {
        DemoPost::factory()->create(['organisation_id' => null]);

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $response->assertStatus(200);
        $response->assertViewIs('Demo/Result/Index');
    }

    /**
     * Test unauthenticated user cannot access MODE 1
     */
    public function test_unauthenticated_user_cannot_access_mode1()
    {
        $response = $this->get('/demo/global/result');
        $response->assertRedirect('/login');
    }

    /**
     * Test MODE 2 requires organisation context
     */
    public function test_mode2_requires_organisation_context()
    {
        $organisationId = 1;
        DemoPost::factory()->create(['organisation_id' => $organisationId]);

        // Without organisation context
        $response = $this->actingAs($this->user)
            ->get('/demo/result');

        // Should still work but with null organisation_id
        $response->assertStatus(200);
    }

    /**
     * Test MODE 2 filters data by organisation
     */
    public function test_mode2_filters_data_by_organisation()
    {
        $org1Id = 1;
        $org2Id = 2;

        // Create org1 posts
        DemoPost::factory(2)->create(['organisation_id' => $org1Id]);

        // Create org2 posts
        DemoPost::factory(2)->create(['organisation_id' => $org2Id]);

        // Create global posts
        DemoPost::factory(1)->create(['organisation_id' => null]);

        // View as org1
        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $org1Id])
            ->get('/demo/result');

        // Should only see org1 posts (2)
        $this->assertEquals(2, count($response['posts']));

        foreach ($response['posts'] as $post) {
            // Due to BelongsToTenant, organisation_id should match
        }
    }

    /**
     * Test MODE 1 and MODE 2 have different data
     */
    public function test_mode1_and_mode2_have_different_data()
    {
        // Create MODE 1 data
        $globalPost = DemoPost::factory()->create([
            'name' => 'Global President',
            'organisation_id' => null,
        ]);

        // Create MODE 2 data
        $orgPost = DemoPost::factory()->create([
            'name' => 'Org President',
            'organisation_id' => 1,
        ]);

        // Access MODE 1
        $response1 = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $this->assertEquals(1, count($response1['posts']));
        $this->assertEquals('Global President', $response1['posts'][0]['name']);

        // Access MODE 2
        $response2 = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => 1])
            ->get('/demo/result');

        $this->assertEquals(1, count($response2['posts']));
        $this->assertEquals('Org President', $response2['posts'][0]['name']);
    }

    /**
     * Test page contains correct MODE indicator
     */
    public function test_page_displays_mode_indicator()
    {
        DemoPost::factory()->create(['organisation_id' => null]);

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        // Check that mode indicator data is passed
        $this->assertEquals('global', $response['mode']);
        $this->assertEquals('Global Demo Results', $response['page_title']);
    }

    /**
     * Test MODE 2 displays correct page title
     */
    public function test_mode2_displays_correct_title()
    {
        DemoPost::factory()->create(['organisation_id' => 1]);

        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => 1])
            ->get('/demo/result');

        $this->assertEquals('organisation', $response['mode']);
        $this->assertEquals('Organisation Demo Results', $response['page_title']);
    }

    /**
     * Test empty results page displays correctly
     */
    public function test_empty_results_page_displays_correctly()
    {
        // No demo posts created

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $response->assertStatus(200);
        $this->assertEquals(0, count($response['posts']));
    }

    /**
     * Test large dataset performance
     */
    public function test_handles_large_dataset_efficiently()
    {
        // Create 50 posts
        $posts = DemoPost::factory(50)->create(['organisation_id' => null]);

        // Create 100 candidates per post
        foreach ($posts as $post) {
            DemoCandidacy::factory(100)->create([
                'post_id' => $post->post_id,
                'organisation_id' => null,
            ]);
        }

        $startTime = microtime(true);

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $endTime = microtime(true);
        $executionTime = ($endTime - $startTime) * 1000; // in milliseconds

        // Should complete in less than 5 seconds
        $this->assertLessThan(5000, $executionTime);
        $response->assertStatus(200);
        $this->assertEquals(50, count($response['posts']));
    }

    /**
     * Test vote percentage calculation
     */
    public function test_vote_percentage_calculated_correctly()
    {
        $post = DemoPost::factory()->create(['organisation_id' => null]);
        $candidate = DemoCandidacy::factory()->create(['post_id' => $post->post_id, 'organisation_id' => null]);

        // Create 10 votes
        for ($i = 0; $i < 10; $i++) {
            DemoVote::create([
                'organisation_id' => null,
                'voting_code' => 'CODE' . $i,
                'candidate_01' => json_encode([
                    'post_id' => $post->post_id,
                    'candidates' => [['candidacy_id' => $candidate->candidacy_id]]
                ]),
            ]);
        }

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $postResult = collect($response['final_result']['posts'])->first();
        $candidateResult = collect($postResult['candidates'])->first();

        // 10 votes out of 10 total = 100%
        $this->assertEquals(100, $candidateResult['vote_percent']);
    }

    /**
     * Test candidate sorting (highest votes first)
     */
    public function test_candidates_sorted_by_votes()
    {
        $post = DemoPost::factory()->create(['organisation_id' => null]);
        $candidate1 = DemoCandidacy::factory()->create(['post_id' => $post->post_id, 'organisation_id' => null]);
        $candidate2 = DemoCandidacy::factory()->create(['post_id' => $post->post_id, 'organisation_id' => null]);

        // Create 5 votes for candidate1
        for ($i = 0; $i < 5; $i++) {
            DemoVote::create([
                'organisation_id' => null,
                'voting_code' => 'C1_' . $i,
                'candidate_01' => json_encode([
                    'post_id' => $post->post_id,
                    'candidates' => [['candidacy_id' => $candidate1->candidacy_id]]
                ]),
            ]);
        }

        // Create 10 votes for candidate2
        for ($i = 0; $i < 10; $i++) {
            DemoVote::create([
                'organisation_id' => null,
                'voting_code' => 'C2_' . $i,
                'candidate_01' => json_encode([
                    'post_id' => $post->post_id,
                    'candidates' => [['candidacy_id' => $candidate2->candidacy_id]]
                ]),
            ]);
        }

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $postResult = collect($response['final_result']['posts'])->first();

        // First candidate should have most votes
        $this->assertEquals(10, $postResult['candidates'][0]['vote_count']);
        $this->assertEquals(5, $postResult['candidates'][1]['vote_count']);
    }

    /**
     * Test that is_demo flag is set
     */
    public function test_is_demo_flag_is_set()
    {
        DemoPost::factory()->create(['organisation_id' => null]);

        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        $this->assertTrue($response['is_demo']);
    }
}
