<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Code;
use App\Models\User;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\DemoCandidate;
use App\Models\Vote;
use App\Models\DemoVote;

/**
 * ElectionIdPositionOrderTest
 *
 * TDD Tests to verify election_id and position_order are correctly set
 * throughout the voting process for both real and demo elections.
 *
 * Critical checks:
 * 1. Posts have election_id set correctly
 * 2. Candidacies have election_id set correctly
 * 3. Candidates ordered by position_order
 * 4. Votes reference correct election_id
 * 5. Posts ordered by position_order
 * 6. Demo candidates have election_id set
 */
class ElectionIdPositionOrderTest extends TestCase
{
    use RefreshDatabase;

    protected $realElection;
    protected $demoElection;
    protected $voter;

    public function setUp(): void
    {
        parent::setUp();

        // Create real election
        $this->realElection = Election::factory()->real()->create([
            'name' => 'Real Election',
            'type' => 'real',
        ]);

        // Create demo election
        $this->demoElection = Election::factory()->demo()->create([
            'name' => 'Demo Election',
            'type' => 'demo',
        ]);

        // Create voter
        $this->voter = User::factory()->voter()->create();
    }

    // ========================================
    // ELECTION_ID VERIFICATION TESTS
    // ========================================

    /**
     * Test: Posts belong to correct election_id
     *
     * Description: When posts are created for an election,
     * election_id must be set and match
     */
    public function test_posts_have_election_id_set()
    {
        // Create posts for real election
        $post1 = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $post2 = Post::create([
            'post_id' => 'POST2',
            'name' => 'Vice President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        // Verify election_id set
        $this->assertNotNull($post1->election_id);
        $this->assertNotNull($post2->election_id);
        $this->assertEquals($this->realElection->id, $post1->election_id);
        $this->assertEquals($this->realElection->id, $post2->election_id);
    }

    /**
     * Test: Candidacies belong to correct election_id
     *
     * Description: When candidacies are created,
     * election_id must be set correctly
     */
    public function test_candidacies_have_election_id_set()
    {
        // Create post for election
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        // Create candidacy using factory (handles user relationship)
        $candidacy = Candidacy::factory()->create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND1',
            'election_id' => $this->realElection->id,
        ]);

        // Verify election_id set
        $this->assertNotNull($candidacy->election_id);
        $this->assertEquals($this->realElection->id, $candidacy->election_id);
    }

    /**
     * Test: Query posts by election_id returns correct posts only
     *
     * Description: When querying posts for an election,
     * only posts with matching election_id should be returned
     */
    public function test_query_posts_by_election_id_filters_correctly()
    {
        // Create posts for real election
        $realPost = Post::create([
            'post_id' => 'REAL_POST',
            'name' => 'Real President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        // Create posts for demo election
        $demoPost = Post::create([
            'post_id' => 'DEMO_POST',
            'name' => 'Demo President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->demoElection->id,
        ]);

        // Query real election posts
        $realPosts = Post::where('election_id', $this->realElection->id)->get();

        // Verify only real election posts returned
        $this->assertEquals(1, $realPosts->count());
        $this->assertEquals('REAL_POST', $realPosts->first()->post_id);
        $this->assertFalse($realPosts->pluck('post_id')->contains('DEMO_POST'));
    }

    /**
     * Test: Query candidacies by election_id returns correct candidacies only
     *
     * Description: When querying candidacies for an election,
     * only candidacies with matching election_id should be returned
     */
    public function test_query_candidacies_by_election_id_filters_correctly()
    {
        // Create posts
        $realPost = Post::create([
            'post_id' => 'REAL_POST',
            'name' => 'Real President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $demoPost = Post::create([
            'post_id' => 'DEMO_POST',
            'name' => 'Demo President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->demoElection->id,
        ]);

        // Create users
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        // Create candidacies
        $realCandidacy = Candidacy::create([
            'post_id' => $realPost->post_id,
            'candidacy_id' => 'REAL_CAND',
            'user_id' => $user1->user_id,
            'election_id' => $this->realElection->id,
        ]);

        $demoCandidacy = Candidacy::create([
            'post_id' => $demoPost->post_id,
            'candidacy_id' => 'DEMO_CAND',
            'user_id' => $user2->user_id,
            'election_id' => $this->demoElection->id,
        ]);

        // Query real election candidacies
        $realCandidacies = Candidacy::where('election_id', $this->realElection->id)->get();

        // Verify only real election candidacies returned
        $this->assertEquals(1, $realCandidacies->count());
        $this->assertEquals('REAL_CAND', $realCandidacies->first()->candidacy_id);
    }

    /**
     * Test: Vote records include correct election_id
     *
     * Description: When a vote is recorded in the votes table,
     * election_id must match the election being voted in
     */
    public function test_vote_records_include_correct_election_id()
    {
        // Create vote using factory (anonymous voting)
        $vote = Vote::factory()->forElection($this->realElection)->create([
            'voting_code' => 'VOTE-' . $this->realElection->id,
            'candidate_01' => json_encode(['candidacy_id' => 'CAND1']),
        ]);

        // Verify election_id is set
        $this->assertNotNull($vote->election_id);
        $this->assertEquals($this->realElection->id, $vote->election_id);

        // Verify vote is stored in votes table (not demo_votes)
        $this->assertDatabaseHas('votes', [
            'id' => $vote->id,
            'election_id' => $this->realElection->id,
            'voting_code' => 'VOTE-' . $this->realElection->id,
        ]);
    }

    /**
     * Test: Demo votes include correct election_id
     *
     * Description: Demo votes must have election_id set to demo election
     */
    public function test_demo_votes_include_correct_election_id()
    {
        // Create demo vote using factory (anonymous voting)
        $demoVote = DemoVote::factory()->forElection($this->demoElection)->create([
            'voting_code' => 'DEMO-VOTE-' . $this->demoElection->id,
            'candidate_01' => json_encode(['candidacy_id' => 'DEMO-CAND1']),
        ]);

        // Verify election_id is set
        $this->assertNotNull($demoVote->election_id);
        $this->assertEquals($this->demoElection->id, $demoVote->election_id);

        // Verify vote is stored in demo_votes table (not votes)
        $this->assertDatabaseHas('demo_votes', [
            'id' => $demoVote->id,
            'election_id' => $this->demoElection->id,
            'voting_code' => 'DEMO-VOTE-' . $this->demoElection->id,
        ]);
    }

    /**
     * Test: Cascade delete removes all posts for an election
     *
     * Description: When election is deleted, all posts with that election_id are deleted
     */
    public function test_deleting_election_cascade_deletes_posts()
    {
        // Create post with election_id
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $postId = $post->id;

        // Delete election
        $this->realElection->delete();

        // Verify post deleted
        $this->assertNull(Post::find($postId));
    }

    /**
     * Test: Cascade delete removes all candidacies for an election
     *
     * Description: When election is deleted, all candidacies with that election_id are deleted
     */
    public function test_deleting_election_cascade_deletes_candidacies()
    {
        // Create post
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        // Create candidacy
        $user = User::factory()->create();
        $candidacy = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND1',
            'user_id' => $user->user_id,
            'election_id' => $this->realElection->id,
        ]);

        $candidacyId = $candidacy->id;

        // Delete election
        $this->realElection->delete();

        // Verify candidacy deleted
        $this->assertNull(Candidacy::find($candidacyId));
    }

    // ========================================
    // POSITION_ORDER VERIFICATION TESTS
    // ========================================

    /**
     * Test: Posts ordered by position_order
     *
     * Description: Posts should be returned in position_order sequence,
     * not insertion order
     */
    public function test_posts_ordered_by_position_order()
    {
        // Create posts out of order
        $post3 = Post::create([
            'post_id' => 'POST3',
            'name' => 'Treasurer',
            'nepali_name' => '',
            'required_number' => 1,
            'position_order' => 3,
            'election_id' => $this->realElection->id,
        ]);

        $post1 = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'position_order' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $post2 = Post::create([
            'post_id' => 'POST2',
            'name' => 'Vice President',
            'nepali_name' => '',
            'required_number' => 1,
            'position_order' => 2,
            'election_id' => $this->realElection->id,
        ]);

        // Query posts ordered by position_order
        $posts = Post::where('election_id', $this->realElection->id)
            ->orderBy('position_order')
            ->get();

        // Verify correct order (not insertion order)
        $this->assertEquals('POST1', $posts[0]->post_id);
        $this->assertEquals('POST2', $posts[1]->post_id);
        $this->assertEquals('POST3', $posts[2]->post_id);

        // Verify position_order values
        $this->assertEquals(1, $posts[0]->position_order);
        $this->assertEquals(2, $posts[1]->position_order);
        $this->assertEquals(3, $posts[2]->position_order);
    }

    /**
     * Test: Candidacies ordered by position_order within post
     *
     * Description: Candidates within a post should be ordered by position_order
     */
    public function test_candidacies_ordered_by_position_order()
    {
        // Create post
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'position_order' => 1,
            'election_id' => $this->realElection->id,
        ]);

        // Create candidacies out of order
        $user3 = User::factory()->create();
        $cand3 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND3',
            'user_id' => $user3->user_id,
            'position_order' => 3,
            'election_id' => $this->realElection->id,
        ]);

        $user1 = User::factory()->create();
        $cand1 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND1',
            'user_id' => $user1->user_id,
            'position_order' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $user2 = User::factory()->create();
        $cand2 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND2',
            'user_id' => $user2->user_id,
            'position_order' => 2,
            'election_id' => $this->realElection->id,
        ]);

        // Query candidacies ordered by position_order
        $candidacies = Candidacy::where('post_id', $post->post_id)
            ->orderBy('position_order')
            ->get();

        // Verify correct order
        $this->assertEquals('CAND1', $candidacies[0]->candidacy_id);
        $this->assertEquals('CAND2', $candidacies[1]->candidacy_id);
        $this->assertEquals('CAND3', $candidacies[2]->candidacy_id);

        // Verify position_order values
        $this->assertEquals(1, $candidacies[0]->position_order);
        $this->assertEquals(2, $candidacies[1]->position_order);
        $this->assertEquals(3, $candidacies[2]->position_order);
    }

    /**
     * Test: Demo candidates ordered by position_order
     *
     * Description: Demo candidates should be ordered by position_order
     */
    public function test_demo_candidates_ordered_by_position_order()
    {
        // Create demo candidates out of order using factory
        $demoCand3 = DemoCandidate::factory()->forElection($this->demoElection)->create([
            'candidacy_id' => 'DEMO3',
            'position_order' => 3,
        ]);

        $demoCand1 = DemoCandidate::factory()->forElection($this->demoElection)->create([
            'candidacy_id' => 'DEMO1',
            'position_order' => 1,
        ]);

        $demoCand2 = DemoCandidate::factory()->forElection($this->demoElection)->create([
            'candidacy_id' => 'DEMO2',
            'position_order' => 2,
        ]);

        // Query demo candidates ordered by position_order
        $demoCandidates = DemoCandidate::where('election_id', $this->demoElection->id)
            ->orderBy('position_order')
            ->get();

        // Verify correct order
        $this->assertEquals('DEMO1', $demoCandidates[0]->candidacy_id);
        $this->assertEquals('DEMO2', $demoCandidates[1]->candidacy_id);
        $this->assertEquals('DEMO3', $demoCandidates[2]->candidacy_id);
    }

    /**
     * Test: Position order gap detection
     *
     * Description: System should handle gaps in position_order gracefully
     * (e.g., positions 1, 3, 5 without 2 and 4)
     */
    public function test_position_order_with_gaps_handled_correctly()
    {
        // Create post with gap in position_order
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $user1 = User::factory()->create();
        $cand1 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND1',
            'user_id' => $user1->user_id,
            'position_order' => 1,  // Gap: missing 2
            'election_id' => $this->realElection->id,
        ]);

        $user3 = User::factory()->create();
        $cand3 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND3',
            'user_id' => $user3->user_id,
            'position_order' => 3,  // Gap: missing 2
            'election_id' => $this->realElection->id,
        ]);

        // Query should still return in correct order
        $candidacies = Candidacy::where('post_id', $post->post_id)
            ->orderBy('position_order')
            ->get();

        // Verify order maintained despite gap
        $this->assertEquals(1, $candidacies[0]->position_order);
        $this->assertEquals(3, $candidacies[1]->position_order);
    }

    /**
     * Test: Reordering updates position_order correctly
     *
     * Description: When candidates are reordered,
     * position_order values should update and queries should reflect new order
     */
    public function test_reordering_updates_position_order()
    {
        // Create post and candidacies with initial order
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $user1 = User::factory()->create();
        $cand1 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND1',
            'user_id' => $user1->user_id,
            'position_order' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $user2 = User::factory()->create();
        $cand2 = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND2',
            'user_id' => $user2->user_id,
            'position_order' => 2,
            'election_id' => $this->realElection->id,
        ]);

        // Verify initial order
        $initialOrder = Candidacy::where('post_id', $post->post_id)
            ->orderBy('position_order')
            ->pluck('candidacy_id')
            ->toArray();
        $this->assertEquals(['CAND1', 'CAND2'], $initialOrder);

        // Reorder: swap positions
        $cand1->update(['position_order' => 2]);
        $cand2->update(['position_order' => 1]);

        // Verify new order
        $newOrder = Candidacy::where('post_id', $post->post_id)
            ->orderBy('position_order')
            ->pluck('candidacy_id')
            ->toArray();
        $this->assertEquals(['CAND2', 'CAND1'], $newOrder);
    }

    /**
     * Test: Position order persists across elections
     *
     * Description: Same candidacy_id can have different position_order
     * in different elections (if allowed by system)
     */
    public function test_position_order_independent_per_election()
    {
        // Create posts for both elections
        $realPost = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $demoPost = Post::create([
            'post_id' => 'POST_DEMO',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->demoElection->id,
        ]);

        // Create same user for both
        $user = User::factory()->create();

        // Create candidacy in real election (position 1)
        $realCand = Candidacy::create([
            'post_id' => $realPost->post_id,
            'candidacy_id' => 'CAND1',
            'user_id' => $user->user_id,
            'position_order' => 1,
            'election_id' => $this->realElection->id,
        ]);

        // Create candidacy in demo election (position 2)
        $user2 = User::factory()->create();
        $demoCand = Candidacy::create([
            'post_id' => $demoPost->post_id,
            'candidacy_id' => 'CAND_DEMO',
            'user_id' => $user2->user_id,
            'position_order' => 2,
            'election_id' => $this->demoElection->id,
        ]);

        // Verify positions are independent
        $realCandQuery = Candidacy::where('election_id', $this->realElection->id)
            ->where('candidacy_id', 'CAND1')
            ->first();
        $this->assertEquals(1, $realCandQuery->position_order);

        $demoCandQuery = Candidacy::where('election_id', $this->demoElection->id)
            ->where('candidacy_id', 'CAND_DEMO')
            ->first();
        $this->assertEquals(2, $demoCandQuery->position_order);
    }

    /**
     * Test: Voting preserves candidacy relationship with correct election_id
     *
     * Description: Vote records should reference candidacies with correct election_id
     */
    public function test_voting_preserves_candidacy_election_relationship()
    {
        // Create post and candidacy with election_id
        $post = Post::create([
            'post_id' => 'POST1',
            'name' => 'President',
            'nepali_name' => '',
            'required_number' => 1,
            'election_id' => $this->realElection->id,
        ]);

        $candUser = User::factory()->create();
        $candidacy = Candidacy::create([
            'post_id' => $post->post_id,
            'candidacy_id' => 'CAND1',
            'user_id' => $candUser->user_id,
            'election_id' => $this->realElection->id,
            'proposer_id' => 'proposer_1',
            'supporter_id' => 'supporter_1',
        ]);

        // Record vote for this candidacy (anonymous voting with JSON candidate data)
        $vote = Vote::factory()->forElection($this->realElection)->create([
            'voting_code' => 'VOTE-CAND-' . $candidacy->candidacy_id,
            'candidate_01' => json_encode([
                'candidacy_id' => $candidacy->candidacy_id,
                'candidacy_name' => $candidacy->user->name ?? 'Candidate Name'
            ]),
        ]);

        // Verify vote is recorded with correct election_id
        $this->assertEquals($this->realElection->id, $vote->election_id);

        // Verify vote is in the database with correct candidacy reference
        $this->assertDatabaseHas('votes', [
            'id' => $vote->id,
            'election_id' => $this->realElection->id,
            'voting_code' => 'VOTE-CAND-' . $candidacy->candidacy_id,
        ]);

        // Verify candidacy also has matching election_id
        $this->assertEquals($candidacy->election_id, $this->realElection->id);
    }
}
