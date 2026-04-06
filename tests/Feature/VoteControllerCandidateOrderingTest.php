<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Post;
use App\Models\DemoCandidate;
use App\Models\Candidacy;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteControllerCandidateOrderingTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that Post.demoCandidates() returns candidates ordered by position_order
     */
    public function test_post_demo_candidates_are_ordered_by_position_order()
    {
        // Create election
        $election = new Election();
        $election->name = 'Demo Election';
        $election->slug = 'demo-election-test';
        $election->type = 'demo';
        $election->is_active = true;
        $election->description = 'Test Demo Election';
        $election->start_date = now();
        $election->end_date = now()->addDays(365);
        $election->save();

        $post = Post::create([
            'post_id' => 'president-' . $election->id,
            'name' => 'President',
            'nepali_name' => 'राष्ट्रपति',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        // Create demo candidates with different position_orders
        DemoCandidate::create([
            'user_id' => 'demo-user-3',
            'post_id' => $post->post_id,
            'election_id' => $election->id,
            'candidacy_id' => 'demo-cand-3-'.time(),
            'user_name' => 'Third Candidate',
            'position_order' => 3,
        ]);

        DemoCandidate::create([
            'user_id' => 'demo-user-1',
            'post_id' => $post->post_id,
            'election_id' => $election->id,
            'candidacy_id' => 'demo-cand-1-'.time(),
            'user_name' => 'First Candidate',
            'position_order' => 1,
        ]);

        DemoCandidate::create([
            'user_id' => 'demo-user-2',
            'post_id' => $post->post_id,
            'election_id' => $election->id,
            'candidacy_id' => 'demo-cand-2-'.time(),
            'user_name' => 'Second Candidate',
            'position_order' => 2,
        ]);

        // Get candidates through relationship
        $candidates = $post->demoCandidates()->get();

        // Assert candidates are ordered by position_order
        $this->assertCount(3, $candidates);
        $this->assertEquals(1, $candidates[0]->position_order);
        $this->assertEquals(2, $candidates[1]->position_order);
        $this->assertEquals(3, $candidates[2]->position_order);
    }

    /**
     * Test DemoCandidate query returns candidates ordered by position_order
     */
    public function test_demo_candidate_query_orders_by_position_order()
    {
        // Create election
        $election = new Election();
        $election->name = 'Demo Test';
        $election->slug = 'demo-test-'.time();
        $election->type = 'demo';
        $election->is_active = true;
        $election->description = 'Test';
        $election->start_date = now();
        $election->end_date = now()->addDays(30);
        $election->save();

        // Create candidates out of order
        DemoCandidate::create([
            'user_id' => 'demo-user-3',
            'post_id' => 'post-3',
            'election_id' => $election->id,
            'candidacy_id' => 'demo-cand-3-'.time(),
            'user_name' => 'Third',
            'position_order' => 3,
        ]);

        DemoCandidate::create([
            'user_id' => 'demo-user-1',
            'post_id' => 'post-1',
            'election_id' => $election->id,
            'candidacy_id' => 'demo-cand-1-'.time(),
            'user_name' => 'First',
            'position_order' => 1,
        ]);

        DemoCandidate::create([
            'user_id' => 'demo-user-2',
            'post_id' => 'post-2',
            'election_id' => $election->id,
            'candidacy_id' => 'demo-cand-2-'.time(),
            'user_name' => 'Second',
            'position_order' => 2,
        ]);

        // Query with orderBy position_order (as VoteController does)
        $candidates = DemoCandidate::where('election_id', $election->id)
            ->orderBy('position_order')
            ->get();

        // Assert ordered correctly
        $this->assertCount(3, $candidates);
        $this->assertEquals(1, $candidates[0]->position_order);
        $this->assertEquals(2, $candidates[1]->position_order);
        $this->assertEquals(3, $candidates[2]->position_order);
    }

    /**
     * Test that position_order field exists and is stored correctly
     */
    public function test_demo_candidate_position_order_field_stores_correctly()
    {
        $election = new Election();
        $election->name = 'Test';
        $election->slug = 'test-'.time();
        $election->type = 'demo';
        $election->is_active = true;
        $election->description = 'Test';
        $election->start_date = now();
        $election->end_date = now()->addDays(1);
        $election->save();

        $candidate = DemoCandidate::create([
            'user_id' => 'user-1',
            'post_id' => 'post-1',
            'election_id' => $election->id,
            'candidacy_id' => 'cand-1-'.time(),
            'user_name' => 'Test Candidate',
            'position_order' => 5,
        ]);

        // Reload from database
        $loaded = DemoCandidate::find($candidate->id);

        $this->assertEquals(5, $loaded->position_order);
    }

    /**
     * Test candidacy position_order field stores correctly
     */
    public function test_candidacy_position_order_field_stores_correctly()
    {
        $election = new Election();
        $election->name = 'Real';
        $election->slug = 'real-'.time();
        $election->type = 'real';
        $election->is_active = true;
        $election->description = 'Test';
        $election->start_date = now();
        $election->end_date = now()->addDays(1);
        $election->save();

        // Create a Post first (Candidacy has foreign key constraint)
        $post = Post::create([
            'post_id' => 'post-1-'.time(),
            'name' => 'Test Post',
            'nepali_name' => 'परीक्षण',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        $user = new User();
        $user->user_id = 'test-user-'.time();
        $user->name = 'Test User';
        $user->email = 'test-'.time().'@example.com';
        $user->password = bcrypt('password');
        $user->save();

        $candidacy = Candidacy::create([
            'candidacy_id' => 'cand-1-'.time(),
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'position_order' => 3,
        ]);

        // Reload from database
        $loaded = Candidacy::find($candidacy->id);

        $this->assertEquals(3, $loaded->position_order);
    }

    /**
     * Test post position_order field stores correctly
     */
    public function test_post_position_order_field_stores_correctly()
    {
        $post = Post::create([
            'post_id' => 'test-post-'.time(),
            'name' => 'Test Post',
            'nepali_name' => 'परीक्षण',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 2,
        ]);

        // Reload from database
        $loaded = Post::find($post->id);

        $this->assertEquals(2, $loaded->position_order);
    }

    /**
     * Test that multiple posts maintain their own position_order sequences
     */
    public function test_multiple_posts_have_independent_position_orders()
    {
        $election = new Election();
        $election->name = 'Multi Post Test';
        $election->slug = 'multi-'.time();
        $election->type = 'demo';
        $election->is_active = true;
        $election->description = 'Test';
        $election->start_date = now();
        $election->end_date = now()->addDays(1);
        $election->save();

        $post1 = Post::create([
            'post_id' => 'president-' . $election->id,
            'name' => 'President',
            'nepali_name' => 'राष्ट्रपति',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        $post2 = Post::create([
            'post_id' => 'vice-president-' . $election->id,
            'name' => 'Vice President',
            'nepali_name' => 'उप-राष्ट्रपति',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 2,
        ]);

        // Post 1 candidates
        DemoCandidate::create([
            'user_id' => 'user-p1-1', 'post_id' => $post1->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'cand-p1-1-'.time(),
            'user_name' => 'Candidate 1', 'position_order' => 1,
        ]);
        DemoCandidate::create([
            'user_id' => 'user-p1-2', 'post_id' => $post1->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'cand-p1-2-'.time(),
            'user_name' => 'Candidate 2', 'position_order' => 2,
        ]);

        // Post 2 candidates
        DemoCandidate::create([
            'user_id' => 'user-p2-1', 'post_id' => $post2->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'cand-p2-1-'.time(),
            'user_name' => 'Candidate 1', 'position_order' => 1,
        ]);
        DemoCandidate::create([
            'user_id' => 'user-p2-2', 'post_id' => $post2->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'cand-p2-2-'.time(),
            'user_name' => 'Candidate 2', 'position_order' => 2,
        ]);

        // Verify post 1 candidates
        $post1Candidates = $post1->demoCandidates()->get();
        $this->assertEquals(2, $post1Candidates->count());
        $this->assertEquals(1, $post1Candidates[0]->position_order);
        $this->assertEquals(2, $post1Candidates[1]->position_order);

        // Verify post 2 candidates
        $post2Candidates = $post2->demoCandidates()->get();
        $this->assertEquals(2, $post2Candidates->count());
        $this->assertEquals(1, $post2Candidates[0]->position_order);
        $this->assertEquals(2, $post2Candidates[1]->position_order);
    }

    /**
     * Test DemoCandidate fillable array includes position_order
     */
    public function test_demo_candidate_fillable_includes_position_order()
    {
        $this->assertContains('position_order', (new DemoCandidate())->getFillable());
    }

    /**
     * Test Candidacy fillable array includes position_order
     */
    public function test_candidacy_fillable_includes_position_order()
    {
        $this->assertContains('position_order', (new Candidacy())->getFillable());
    }

    /**
     * Test Post fillable array includes position_order
     */
    public function test_post_fillable_includes_position_order()
    {
        $this->assertContains('position_order', (new Post())->getFillable());
    }

    /**
     * Test that position_order column exists in posts table
     */
    public function test_position_order_column_exists_in_posts_table()
    {
        $post = Post::create([
            'post_id' => 'test-post-'.time(),
            'name' => 'Test',
            'nepali_name' => 'परीक्षण',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 5,
        ]);

        $this->assertDatabaseHas('posts', [
            'post_id' => $post->post_id,
            'position_order' => 5,
        ]);
    }

    /**
     * Test that position_order column exists in candidacies table
     */
    public function test_position_order_column_exists_in_candidacies_table()
    {
        // Create a Post first (Candidacy has foreign key constraint)
        $post = Post::create([
            'post_id' => 'test-post-'.time(),
            'name' => 'Test Post',
            'nepali_name' => 'परीक्षण',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        $user = new User();
        $user->user_id = 'testuser-'.time();
        $user->name = 'Test';
        $user->email = 'test-'.time().'@test.com';
        $user->password = bcrypt('password');
        $user->save();

        $candidacy = Candidacy::create([
            'candidacy_id' => 'test-cand-'.time(),
            'user_id' => $user->user_id,
            'post_id' => $post->post_id,
            'position_order' => 3,
        ]);

        $this->assertDatabaseHas('candidacies', [
            'candidacy_id' => $candidacy->candidacy_id,
            'position_order' => 3,
        ]);
    }

    /**
     * Test that position_order column exists in demo_candidacies table
     */
    public function test_position_order_column_exists_in_demo_candidacies_table()
    {
        $election = new Election();
        $election->name = 'Demo';
        $election->slug = 'demo-'.time();
        $election->type = 'demo';
        $election->is_active = true;
        $election->description = 'Test';
        $election->start_date = now();
        $election->end_date = now()->addDays(1);
        $election->save();

        $candidate = DemoCandidate::create([
            'user_id' => 'user-'.time(),
            'post_id' => 'post-1',
            'election_id' => $election->id,
            'candidacy_id' => 'test-demo-cand-'.time(),
            'user_name' => 'Test',
            'position_order' => 2,
        ]);

        $this->assertDatabaseHas('demo_candidacies', [
            'candidacy_id' => $candidate->candidacy_id,
            'position_order' => 2,
        ]);
    }

    /**
     * Test candidates with different position_orders sort correctly
     */
    public function test_candidates_sort_correctly_when_out_of_insertion_order()
    {
        $election = new Election();
        $election->name = 'Sort Test';
        $election->slug = 'sort-'.time();
        $election->type = 'demo';
        $election->is_active = true;
        $election->description = 'Test';
        $election->start_date = now();
        $election->end_date = now()->addDays(1);
        $election->save();

        $post = Post::create([
            'post_id' => 'post-'.time(),
            'name' => 'Test',
            'nepali_name' => 'परीक्षण',
            'state_name' => 'National',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        // Insert in reverse order: 3, 2, 1
        DemoCandidate::create(['user_id' => 'u3', 'post_id' => $post->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'c3-'.time(),
            'user_name' => 'C3', 'position_order' => 3]);

        DemoCandidate::create(['user_id' => 'u2', 'post_id' => $post->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'c2-'.time(),
            'user_name' => 'C2', 'position_order' => 2]);

        DemoCandidate::create(['user_id' => 'u1', 'post_id' => $post->post_id,
            'election_id' => $election->id, 'candidacy_id' => 'c1-'.time(),
            'user_name' => 'C1', 'position_order' => 1]);

        // Query ordered by position_order
        $candidates = DemoCandidate::where('election_id', $election->id)
            ->orderBy('position_order')
            ->get();

        // Should return in correct order: 1, 2, 3
        $this->assertEquals(1, $candidates[0]->position_order);
        $this->assertEquals(2, $candidates[1]->position_order);
        $this->assertEquals(3, $candidates[2]->position_order);
    }
}
