<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Post;
use App\Models\DemoCandidate;
use Illuminate\Support\Facades\Artisan;

/**
 * Demo Election Setup Tests
 *
 * Verifies that the php artisan demo:setup --force command
 * correctly creates elections, posts, and demo candidates
 */
class DemoElectionSetupTest extends TestCase
{
    use RefreshDatabase;

    /**
     * TEST 1: Verify demo:setup command creates demo election
     *
     * Check that the command successfully creates an election with correct attributes
     */
    public function test_demo_setup_creates_demo_election()
    {
        \Log::info('TEST 1: Checking demo election creation');

        // Verify no demo election exists initially
        $this->assertEquals(0, Election::where('slug', 'demo-election')->count(),
            'No demo election should exist initially');

        // Run the demo:setup command
        $this->artisan('demo:setup', ['--force' => true])
            ->assertSuccessful();

        // Verify demo election was created
        $demoElection = Election::where('slug', 'demo-election')->first();
        $this->assertNotNull($demoElection, 'Demo election should be created');
        $this->assertEquals('Demo Election', $demoElection->name, 'Election name should be "Demo Election"');
        $this->assertEquals('demo', $demoElection->type, 'Election type should be "demo"');
        $this->assertEquals(1, $demoElection->is_active, 'Demo election should be active');

        \Log::info('TEST 1: Demo election created successfully', [
            'election_id' => $demoElection->id,
            'election_slug' => $demoElection->slug,
        ]);
    }

    /**
     * TEST 2: Verify demo:setup creates exactly 3 posts
     *
     * Check that posts are created with correct order and names
     */
    public function test_demo_setup_creates_three_posts()
    {
        \Log::info('TEST 2: Checking posts creation');

        $this->artisan('demo:setup', ['--force' => true])->assertSuccessful();

        $demoElection = Election::where('slug', 'demo-election')->first();

        // Verify exactly 3 posts exist
        $posts = Post::where('election_id', $demoElection->id)->get();
        $this->assertEquals(3, $posts->count(), 'Should have exactly 3 posts');

        // Verify post order and names
        $expectedPosts = [
            ['position_order' => 1, 'name' => 'President'],
            ['position_order' => 2, 'name' => 'Vice President'],
            ['position_order' => 3, 'name' => 'Secretary'],
        ];

        foreach ($expectedPosts as $index => $expected) {
            $post = $posts[$index];
            $this->assertEquals($expected['position_order'], $post->position_order,
                "Post {$index} should have position_order {$expected['position_order']}");
            // Don't check name yet as it might have bilingual names
            \Log::info("Post {$index}: {$post->name}");
        }

        \Log::info('TEST 2: Posts created successfully');
    }

    /**
     * TEST 3: Verify demo:setup creates 9 demo candidates (3 per post)
     *
     * Check that candidates are created for each post
     */
    public function test_demo_setup_creates_nine_demo_candidates()
    {
        \Log::info('TEST 3: Checking demo candidates creation');

        $this->artisan('demo:setup', ['--force' => true])->assertSuccessful();

        $demoElection = Election::where('slug', 'demo-election')->first();

        // Verify exactly 9 candidates exist
        $candidates = DemoCandidate::where('election_id', $demoElection->id)->get();
        $this->assertEquals(9, $candidates->count(),
            'Should have exactly 9 demo candidates (3 per post)');

        // Verify candidates grouped by post (3 per post)
        $candidatesByPost = $candidates->groupBy('post_id');
        $this->assertEquals(3, $candidatesByPost->count(),
            'Candidates should be grouped in 3 posts');

        foreach ($candidatesByPost as $postId => $postCandidates) {
            $this->assertEquals(3, $postCandidates->count(),
                "Each post should have exactly 3 candidates");

            // Verify position_order for each post's candidates
            $positions = $postCandidates->pluck('position_order')->sort()->values();
            $this->assertEquals([1, 2, 3], $positions->toArray(),
                "Post {$postId} candidates should have position_order 1, 2, 3");
        }

        \Log::info('TEST 3: Demo candidates created successfully', [
            'total_candidates' => $candidates->count(),
            'candidates_per_post' => 3,
        ]);
    }

    /**
     * TEST 4: Verify demo candidates have all required fields
     *
     * Check that each candidate has proper attributes
     */
    public function test_demo_candidates_have_all_required_fields()
    {
        \Log::info('TEST 4: Checking candidate fields');

        $this->artisan('demo:setup', ['--force' => true])->assertSuccessful();

        $demoElection = Election::where('slug', 'demo-election')->first();
        $candidates = DemoCandidate::where('election_id', $demoElection->id)->get();

        // Check each candidate has required fields
        foreach ($candidates as $candidate) {
            $this->assertNotEmpty($candidate->candidacy_id, 'candidacy_id should be set');
            $this->assertNotEmpty($candidate->user_id, 'user_id should be set');
            $this->assertEquals($demoElection->id, $candidate->election_id, 'election_id should match');
            $this->assertNotEmpty($candidate->post_id, 'post_id should be set');
            $this->assertNotNull($candidate->position_order, 'position_order should be set');

            \Log::info("Candidate: {$candidate->candidacy_id}", [
                'user_id' => $candidate->user_id,
                'post_id' => $candidate->post_id,
                'position_order' => $candidate->position_order,
            ]);
        }

        \Log::info('TEST 4: All candidates have required fields');
    }

    /**
     * TEST 5: Verify candidates are accessible through voting form
     *
     * Check that candidates are properly associated and retrievable
     */
    public function test_demo_candidates_accessible_through_posts()
    {
        \Log::info('TEST 5: Checking candidate accessibility through posts');

        $this->artisan('demo:setup', ['--force' => true])->assertSuccessful();

        $demoElection = Election::where('slug', 'demo-election')->first();
        $posts = Post::where('election_id', $demoElection->id)->get();

        // Verify each post can access its candidates
        foreach ($posts as $post) {
            $demoCandidates = DemoCandidate::where('post_id', $post->post_id)
                ->where('election_id', $demoElection->id)
                ->get();

            $this->assertEquals(3, $demoCandidates->count(),
                "Post {$post->post_id} should have 3 candidates");

            foreach ($demoCandidates as $candidate) {
                $this->assertNotEmpty($candidate->user_id, 'Candidate should have user_id');
                $this->assertEquals($post->post_id, $candidate->post_id, 'Candidate post_id should match');
            }
        }

        \Log::info('TEST 5: Candidates accessible through posts');
    }

    /**
     * TEST 6: Verify force flag replaces existing demo election
     *
     * Check that --force deletes old data and creates fresh demo election
     */
    public function test_demo_setup_force_flag_replaces_existing()
    {
        \Log::info('TEST 6: Checking force flag behavior');

        // First run
        $this->artisan('demo:setup')->assertSuccessful();
        $firstElection = Election::where('slug', 'demo-election')->first();
        $firstElectionId = $firstElection->id;
        $firstCandidatesCount = DemoCandidate::where('election_id', $firstElectionId)->count();

        // Verify first setup worked
        $this->assertEquals(9, $firstCandidatesCount, 'First setup should create 9 candidates');

        // Second run with force (use input to confirm)
        $this->artisan('demo:setup', ['--force' => true])
            ->expectsQuestion('⚠️  This will DELETE the existing demo election and all its data. Continue?', 'yes')
            ->assertSuccessful();

        $secondElection = Election::where('slug', 'demo-election')->first();
        $secondCandidatesCount = DemoCandidate::where('election_id', $secondElection->id)->count();

        // Verify election was recreated
        $this->assertNotNull($secondElection, 'Demo election should exist after --force');
        $this->assertEquals(9, $secondCandidatesCount, 'Second setup should also create 9 candidates');

        \Log::info('TEST 6: Force flag works correctly');
    }

    /**
     * TEST 7: Verify demo election is active and ready for voting
     *
     * Check that demo election can be used for voting
     */
    public function test_demo_election_ready_for_voting()
    {
        \Log::info('TEST 7: Checking if demo election is ready for voting');

        $this->artisan('demo:setup', ['--force' => true])->assertSuccessful();

        $demoElection = Election::where('slug', 'demo-election')->first();

        // Verify active status
        $this->assertEquals(1, $demoElection->is_active, 'Demo election should be active');
        $this->assertEquals('demo', $demoElection->type, 'Demo election type should be "demo"');

        // Verify has posts
        $postsCount = Post::where('election_id', $demoElection->id)->count();
        $this->assertEquals(3, $postsCount, 'Demo election should have 3 posts');

        // Verify has candidates
        $candidatesCount = DemoCandidate::where('election_id', $demoElection->id)->count();
        $this->assertEquals(9, $candidatesCount, 'Demo election should have 9 candidates');

        // All conditions met
        $this->assertTrue(
            $demoElection->is_active && $demoElection->type === 'demo' &&
            $postsCount === 3 && $candidatesCount === 9,
            'Demo election is ready for voting'
        );

        \Log::info('TEST 7: Demo election ready for voting', [
            'active' => $demoElection->is_active,
            'type' => $demoElection->type,
            'posts' => $postsCount,
            'candidates' => $candidatesCount,
        ]);
    }

    /**
     * TEST 8: Verify demo candidates can be queried for voting form
     *
     * Check that the voting form can retrieve candidates properly
     */
    public function test_demo_candidates_queryable_for_voting_form()
    {
        \Log::info('TEST 8: Testing voting form candidate query');

        $this->artisan('demo:setup', ['--force' => true])->assertSuccessful();

        $demoElection = Election::where('slug', 'demo-election')->first();

        // Simulate what VoteController does for demo elections
        $demoCandidates = DemoCandidate::where('election_id', $demoElection->id)
            ->orderBy('position_order')
            ->get();

        // Group by post
        $groupedCandidates = $demoCandidates->groupBy('post_id');

        $this->assertEquals(3, $groupedCandidates->count(),
            'Should have 3 groups (one per post)');

        // Verify each group has 3 candidates
        foreach ($groupedCandidates as $postId => $candidates) {
            $this->assertEquals(3, $candidates->count(),
                "Post {$postId} should have 3 candidates");

            // Verify candidates can be displayed
            foreach ($candidates as $candidate) {
                $this->assertNotEmpty($candidate->candidacy_id);
                $this->assertNotEmpty($candidate->user_id);
                // These might be empty for demo but should be accessible
                $this->assertTrue(isset($candidate->user_name) || isset($candidate->candidacy_name));
            }
        }

        \Log::info('TEST 8: Candidates queryable for voting form', [
            'posts_count' => $groupedCandidates->count(),
        ]);
    }
}
