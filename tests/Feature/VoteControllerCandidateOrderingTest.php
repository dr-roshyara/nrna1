<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\Post;
use App\Models\Candidacy;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VoteControllerCandidateOrderingTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;
    private Organisation $organisation;
    private Post $post;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->organisation = Organisation::factory()->create([
            'type' => 'tenant',
        ]);

        $this->election = Election::factory()->create([
            'type' => 'real',
            'status' => 'active',
            'organisation_id' => $this->organisation->id,
        ]);

        $this->post = Post::factory()->create([
            'election_id' => $this->election->id,
            'organisation_id' => $this->organisation->id,
        ]);

        $this->user = User::factory()->create();
    }

    public function test_candidacy_position_order_field_stores_correctly(): void
    {
        $candidacy = Candidacy::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'organisation_id' => $this->organisation->id,
            'position_order' => 3,
        ]);

        $loaded = Candidacy::withoutGlobalScopes()->find($candidacy->id);

        $this->assertEquals(3, $loaded->position_order);
    }

    public function test_post_position_order_field_stores_correctly(): void
    {
        $post = Post::factory()->create([
            'position_order' => 2,
        ]);

        $loaded = Post::withoutGlobalScopes()->find($post->id);

        $this->assertEquals(2, $loaded->position_order);
    }

    public function test_position_order_column_exists_in_posts_table(): void
    {
        $post = Post::factory()->create([
            'position_order' => 5,
        ]);

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'position_order' => 5,
        ]);
    }

    public function test_position_order_column_exists_in_candidacies_table(): void
    {
        $candidacy = Candidacy::factory()->create([
            'post_id' => $this->post->id,
            'user_id' => $this->user->id,
            'organisation_id' => $this->organisation->id,
            'position_order' => 3,
        ]);

        $this->assertDatabaseHas('candidacies', [
            'id' => $candidacy->id,
            'position_order' => 3,
        ]);
    }

    public function test_candidacy_fillable_includes_position_order(): void
    {
        $this->assertContains('position_order', (new Candidacy())->getFillable());
    }

    public function test_post_fillable_includes_position_order(): void
    {
        $this->assertContains('position_order', (new Post())->getFillable());
    }
}
