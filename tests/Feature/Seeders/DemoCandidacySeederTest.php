<?php

namespace Tests\Feature\Seeders;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Post;
use App\Models\DemoCandidacy;
use App\Models\Organisation;
use Database\Seeders\OrganisationSeeder;
use Database\Seeders\ElectionSeeder;
use Database\Seeders\DemoElectionSeeder;
use Database\Seeders\DemoCandidacySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoCandidacySeederTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Helper to seed all dependencies required by DemoCandidacySeeder
     */
    protected function seedDependencies(): void
    {
        $this->seed([
            OrganisationSeeder::class,
            ElectionSeeder::class,
            DemoElectionSeeder::class,
        ]);
    }

    /** @test */
    public function demo_candidacy_seeder_creates_candidates()
    {
        // Given: Platform, elections, and posts exist
        $this->seedDependencies();

        // When: Running DemoCandidacySeeder
        $this->seed(DemoCandidacySeeder::class);

        // Then: Candidates created (3 posts × 3 candidates = 9)
        $election = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->first();

        $totalCandidates = DemoCandidacy::where('election_id', $election->id)->count();
        $this->assertEquals(9, $totalCandidates);
    }

    /** @test */
    public function demo_candidacy_seeder_is_idempotent()
    {
        // Given: All seeders run once (fresh database)
        $this->seedDependencies();
        $this->seed(DemoCandidacySeeder::class);
        $countAfterFirst = DemoCandidacy::count();

        // When: Run DemoCandidacySeeder again (should not add duplicates)
        $this->seed(DemoCandidacySeeder::class);
        $countAfterSecond = DemoCandidacy::count();

        // Then: Count should NOT increase
        $this->assertEquals($countAfterFirst, $countAfterSecond);
    }

    /** @test */
    public function demo_candidacy_seeder_skips_posts_with_existing_candidates()
    {
        // Given: Setup all dependencies
        $this->seedDependencies();
        $this->seed(DemoCandidacySeeder::class);

        $election = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->first();

        $countBefore = DemoCandidacy::count(); // Should be 9

        // When: Add extra candidate to post, then re-run seeder
        $post = Post::where('election_id', $election->id)->first();
        DemoCandidacy::factory()->create([
            'election_id' => $election->id,
            'post_id' => $post->post_id,
        ]);

        $this->seed(DemoCandidacySeeder::class);
        $countAfter = DemoCandidacy::count();

        // Then: Should NOT add more candidates (post already has >= 3)
        $this->assertEquals($countBefore + 1, $countAfter);
    }

    /** @test */
    public function demo_candidacy_seeder_uses_correct_election_slug()
    {
        // Given: Setup platform and elections
        $this->seedDependencies();

        // When: Running DemoCandidacySeeder
        $this->seed(DemoCandidacySeeder::class);

        // Then: All candidates belong to demo-election (found by slug)
        $demoElection = Election::where('slug', 'demo-election')
            ->withoutGlobalScopes()
            ->firstOrFail();

        $candidates = DemoCandidacy::where('election_id', $demoElection->id)->get();

        // Should have candidates
        $this->assertCount(9, $candidates);

        // All should belong to demo election
        foreach ($candidates as $candidate) {
            $this->assertEquals($demoElection->id, $candidate->election_id);
        }
    }
}
