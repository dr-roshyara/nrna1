<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Election;
use App\Models\Post;

/**
 * ElectionPostRelationshipTest
 *
 * Tests the relationship between Elections and Posts
 * Each post now belongs to an election for proper scoping
 */
class ElectionPostRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test: Post can be created with election_id
     */
    public function test_post_can_be_created_with_election_id()
    {
        $election = Election::factory()->create();

        $post = Post::factory()->create([
            'election_id' => $election->id,
        ]);

        $this->assertNotNull($post->election_id);
        $this->assertEquals($election->id, $post->election_id);
    }

    /**
     * Test: Post belongs to election
     */
    public function test_post_belongs_to_election()
    {
        $election = Election::factory()->create();

        $post = Post::factory()->create([
            'election_id' => $election->id,
        ]);

        $this->assertTrue($post->election()->exists());
        $this->assertEquals($election->id, $post->election->id);
    }

    /**
     * Test: Election has many posts
     */
    public function test_election_has_many_posts()
    {
        $election = Election::factory()->create();

        $post1 = Post::factory()->create(['election_id' => $election->id]);
        $post2 = Post::factory()->create(['election_id' => $election->id]);

        $this->assertEquals(2, $election->posts()->count());
        $this->assertTrue($election->posts()->pluck('id')->contains($post1->id));
        $this->assertTrue($election->posts()->pluck('id')->contains($post2->id));
    }

    /**
     * Test: Different elections have different posts
     */
    public function test_different_elections_have_independent_posts()
    {
        $election1 = Election::factory()->create();
        $election2 = Election::factory()->create();

        $post1 = Post::factory()->create(['election_id' => $election1->id]);
        $post2 = Post::factory()->create(['election_id' => $election2->id]);

        $this->assertEquals(1, $election1->posts()->count());
        $this->assertEquals(1, $election2->posts()->count());
        $this->assertFalse($election1->posts()->pluck('id')->contains($post2->id));
        $this->assertFalse($election2->posts()->pluck('id')->contains($post1->id));
    }

    /**
     * Test: Post with election_id is fillable
     */
    public function test_post_election_id_is_fillable()
    {
        $election = Election::factory()->create();

        $post = Post::create([
            'election_id' => $election->id,
            'post_id' => 'POST001',
            'name' => 'President',
            'nepali_name' => 'राष्ट्रपति',
            'required_number' => 1,
            'position_order' => 1,
        ]);

        $this->assertNotNull($post->election_id);
        $this->assertEquals($election->id, $post->election_id);
    }

    /**
     * Test: Cascade delete removes posts when election is deleted
     */
    public function test_deleting_election_cascades_to_posts()
    {
        $election = Election::factory()->create();
        $post = Post::factory()->create(['election_id' => $election->id]);

        $postId = $post->id;

        // Delete the election
        $election->delete();

        // Post should also be deleted (cascade delete)
        $this->assertNull(Post::find($postId));
    }
}
