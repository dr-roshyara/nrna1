<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Post;
use App\Models\Organisation;
use Database\Seeders\OrganisationSeeder;
use Database\Seeders\ElectionSeeder;
use Database\Seeders\DemoElectionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoElectionSeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to seed dependencies required by DemoElectionSeeder
     */
    protected function seedDependencies(): void
    {
        $this->seed([
            OrganisationSeeder::class,
            ElectionSeeder::class,
        ]);
    }

    /** @test */
    public function demo_election_seeder_creates_posts()
    {
        // Given: Platform and elections exist
        $this->seedDependencies();

        // When: Running DemoElectionSeeder
        $this->seed(DemoElectionSeeder::class);

        // Then: Posts exist for demo election (exactly 3)
        $election = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->firstOrFail();

        $posts = Post::where('election_id', $election->id)->get();

        $this->assertCount(3, $posts);
        $this->assertTrue($posts->contains('name', 'President'));
        $this->assertTrue($posts->contains('name', 'Vice President'));
        $this->assertTrue($posts->contains('name', 'Secretary'));
    }

    /** @test */
    public function demo_election_seeder_creates_idempotent_posts()
    {
        // Given: Platform and elections exist
        $this->seedDependencies();

        // When: Run seeder once
        $this->seed(DemoElectionSeeder::class);
        $countAfterFirst = Post::count();

        // And: Run seeder again
        $this->seed(DemoElectionSeeder::class);
        $countAfterSecond = Post::count();

        // Then: Count should not increase (idempotent)
        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    /** @test */
    public function demo_election_seeder_does_not_create_candidates()
    {
        // Given: Platform and elections exist
        $this->seedDependencies();

        // When: Running DemoElectionSeeder
        $this->seed(DemoElectionSeeder::class);

        // Then: Posts created but NOT candidates
        $election = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->firstOrFail();

        $postCount = Post::where('election_id', $election->id)->count();
        $candidateCount = DemoCandidacy::where('election_id', $election->id)->count();

        // Should have 3 posts
        $this->assertEquals(3, $postCount);

        // Should have 0 candidates (they're created by DemoCandidacySeeder)
        $this->assertEquals(0, $candidateCount);
    }
}
