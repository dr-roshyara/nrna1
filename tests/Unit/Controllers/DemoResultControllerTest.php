<?php

namespace Tests\Unit\Controllers;

use Tests\TestCase;
use App\Models\User;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoVote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Session;

class DemoResultControllerTest extends TestCase
{
    // use RefreshDatabase; // Temporarily disabled due to migration infrastructure issues
    // Tests will run against the existing production database schema

    protected function setUp(): void
    {
        parent::setUp();

        // Create test users
        $this->user = User::factory()->create();
    }

    /**
     * Test MODE 1 (Global) returns global demo results with organisation_id = NULL
     */
    public function test_mode_1_returns_global_demo_results()
    {
        // Create global demo posts (organisation_id = NULL)
        $posts = DemoPost::factory()
            ->count(3)
            ->create([
                'organisation_id' => null,
            ]);

        // Act
        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        // Assert
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Demo/Result/Index')
                ->has('final_result')
                ->where('mode', 'global')
                ->where('organisation_id', null)
                ->where('posts', fn ($postsData) => count($postsData) === 3)
        );
    }

    /**
     * Test MODE 2 (Organisation) returns org-scoped demo results
     */
    public function test_mode_2_returns_organisation_scoped_results()
    {
        $organisationId = 1;

        // Create org-scoped demo posts
        DemoPost::factory()
            ->count(2)
            ->create([
                'organisation_id' => $organisationId,
            ]);

        // Create global demo posts (should not appear)
        DemoPost::factory()
            ->create([
                'organisation_id' => null,
            ]);

        // Act - Start session, set organisation context, then authenticate
        $this->withSession(['current_organisation_id' => $organisationId])
            ->actingAs($this->user)
            ->get('/demo/result')
            ->assertStatus(200)
            ->assertInertia(fn ($page) =>
                $page
                    ->component('Demo/Result/Index')
                    ->where('mode', 'organisation')
                    ->where('posts', fn ($postsData) => count($postsData) === 2)
            );
    }

    /**
     * Test data isolation: MODE 1 doesn't see MODE 2 data
     */
    public function test_data_isolation_mode1_does_not_see_mode2_data()
    {
        // Create MODE 2 posts
        DemoPost::factory()
            ->count(3)
            ->create([
                'organisation_id' => 999,
            ]);

        // Create MODE 1 posts
        $globalPosts = DemoPost::factory()
            ->count(2)
            ->create([
                'organisation_id' => null,
            ]);

        // Act
        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        // Assert - Should only see global posts
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Demo/Result/Index')
                ->where('mode', 'global')
                ->where('posts', fn ($postsData) =>
                    count($postsData) === 2 &&
                    collect($postsData)->every(fn ($post) => $post->organisation_id === null)
                )
        );
    }

    /**
     * Test vote counting is correct
     */
    public function test_vote_counting_is_accurate()
    {
        // Create demo post
        $post = DemoPost::factory()->create([
            'post_id' => 'PRES',
            'organisation_id' => null,
        ]);

        // Create demo candidates
        $candidate1 = DemoCandidacy::factory()->create([
            'post_id' => 'PRES',
            'organisation_id' => null,
        ]);

        $candidate2 = DemoCandidacy::factory()->create([
            'post_id' => 'PRES',
            'organisation_id' => null,
        ]);

        // Create demo votes - 5 for candidate 1
        $voteData1 = [
            'candidate_01' => json_encode([
                'post_id' => 'PRES',
                'candidates' => [
                    ['candidacy_id' => $candidate1->candidacy_id]
                ]
            ]),
        ];

        for ($i = 0; $i < 5; $i++) {
            DemoVote::create(array_merge(
                $voteData1,
                [
                    'organisation_id' => null,
                    'voting_code' => 'CODE' . $i,
                ]
            ));
        }

        // Create 3 votes for candidate 2
        $voteData2 = [
            'candidate_01' => json_encode([
                'post_id' => 'PRES',
                'candidates' => [
                    ['candidacy_id' => $candidate2->candidacy_id]
                ]
            ]),
        ];

        for ($i = 0; $i < 3; $i++) {
            DemoVote::create(array_merge(
                $voteData2,
                [
                    'organisation_id' => null,
                    'voting_code' => 'CODE_C2_' . $i,
                ]
            ));
        }

        // Act
        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        // Assert - verify vote counting via Inertia
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Demo/Result/Index')
                ->where('final_result', fn ($results) =>
                    $results['total_votes'] === 8 &&
                    count($results['posts']) === 1 &&
                    count($results['posts'][0]['candidates']) === 2
                )
        );
    }

    /**
     * Test "no vote" option is counted correctly
     */
    public function test_no_vote_option_counted_correctly()
    {
        // Create demo post
        $post = DemoPost::factory()->create([
            'post_id' => 'PRES',
            'organisation_id' => null,
        ]);

        // Create votes with "no vote" option
        DemoVote::create([
            'organisation_id' => null,
            'voting_code' => 'CODE1',
            'candidate_01' => json_encode([
                'post_id' => 'PRES',
                'no_vote' => true,
            ]),
        ]);

        DemoVote::create([
            'organisation_id' => null,
            'voting_code' => 'CODE2',
            'candidate_01' => json_encode([
                'post_id' => 'PRES',
                'no_vote' => true,
            ]),
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->get('/demo/global/result');

        // Assert - verify no_vote_count via Inertia
        $response->assertStatus(200);
        $response->assertInertia(fn ($page) =>
            $page
                ->component('Demo/Result/Index')
                ->where('final_result', fn ($results) =>
                    isset($results['posts'][0]) &&
                    $results['posts'][0]['no_vote_count'] === 2
                )
        );
    }

    /**
     * Test authentication is required
     */
    public function test_demo_result_requires_authentication()
    {
        // Act - No authentication
        $response = $this->get('/demo/global/result');

        // Assert
        $response->assertRedirect('/login');
    }

    /**
     * Test PDF download works for MODE 1
     */
    public function test_pdf_download_mode1_works()
    {
        // Create demo data
        DemoPost::factory()
            ->create(['organisation_id' => null]);

        // Act
        $response = $this->actingAs($this->user)
            ->get('/demo/global/result/download-pdf');

        // Assert
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test PDF download works for MODE 2
     */
    public function test_pdf_download_mode2_works()
    {
        $organisationId = 1;

        // Create org-scoped demo data
        DemoPost::factory()
            ->create(['organisation_id' => $organisationId]);

        // Act
        $response = $this->actingAs($this->user)
            ->withSession(['current_organisation_id' => $organisationId])
            ->get('/demo/result/download-pdf');

        // Assert
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/pdf');
    }

    /**
     * Test verification endpoint
     */
    public function test_verify_results_endpoint()
    {
        // Create demo post
        DemoPost::factory()->create([
            'post_id' => 'PRES',
            'organisation_id' => null,
        ]);

        // Act
        $response = $this->actingAs($this->user)
            ->get('/api/demo/verify-results/PRES');

        // Assert
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'official_results',
            'raw_vote_counts',
            'discrepancies',
            'match'
        ]);
    }
}
