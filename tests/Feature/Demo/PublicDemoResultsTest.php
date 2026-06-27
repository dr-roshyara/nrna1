<?php

namespace Tests\Feature\Demo;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoVote;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\DemoElectionResolver;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Support\Str;

/**
 * Public Demo Results Page - Anonymous Aggregated Results
 *
 * Tests the public demo results endpoint that shows aggregated voting results
 * without requiring authentication.
 *
 * Route: GET /public-demo/results
 * No auth middleware - accessible to anonymous visitors
 */
class PublicDemoResultsTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $platformOrg;
    protected Election $demoElection;
    protected DemoPost $nationalPost;
    protected DemoCandidacy $candidateA;
    protected DemoCandidacy $candidateB;

    protected function setUp(): void
    {
        parent::setUp();

        // 1. Setup global platform context bypassing standard scopes
        $this->platformOrg = Organisation::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'slug' => 'demo-platform-' . Str::random(4),
            'type' => 'platform',
            'is_default' => true,
            'name' => 'Demo Platform Base',
        ]);

        // 2. Build the targeted demo election
        $this->demoElection = Election::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'type' => 'demo',
            'slug' => 'demo-election-' . $this->platformOrg->slug,
            'organisation_id' => $this->platformOrg->id,
            'name' => 'Public Digit Demo Election Results',
            'is_active' => true,
            'status' => 'active',
            'state' => 'draft',
            'start_date' => now()->subDays(1),
            'end_date' => now()->addDays(5),
            'voting_locked' => false,
        ]);

        // 3. Create a Demo Post
        $this->nationalPost = DemoPost::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'name' => 'Board Director',
            'state_name' => 'active',
            'required_number' => 1,
            'is_national_wide' => true,
            'position_order' => 1,
        ]);

        // 4. Create dummy candidate records
        $this->candidateA = DemoCandidacy::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'post_id' => $this->nationalPost->id,
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'user_name' => 'Candidate Alpha',
            'position_order' => 1,
        ]);

        $this->candidateB = DemoCandidacy::withoutGlobalScopes()->create([
            'id' => (string) Str::uuid(),
            'post_id' => $this->nationalPost->id,
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'user_name' => 'Candidate Beta',
            'position_order' => 2,
        ]);

        // Mock the resolver explicitly to return our structured database row
        // to prevent Postgres transaction query failure due to strict un-scoped environments
        $this->mock(DemoElectionResolver::class, function ($mock) {
            $mock->shouldReceive('getPublicDemoElection')->andReturn($this->demoElection);
        });
    }

    /** @test */
    public function public_demo_results_route_is_defined(): void
    {
        $this->assertTrue(route('public-demo.results') !== null);
    }

    /** @test */
    public function it_renders_the_inertia_index_with_calculated_aggregates(): void
    {
        // Simulate structural json records within the flat schema columns (e.g., candidate_01)
        // Two votes for Candidate Alpha, One vote for Candidate Beta
        DemoVote::withoutGlobalScopes()->create([
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'test1'),
            'vote_hash' => hash('sha256', 'test1-vote'),
            'candidate_01' => json_encode([
                'post_id' => $this->nationalPost->id,
                'candidates' => [['candidacy_id' => $this->candidateA->id]]
            ]),
        ]);

        DemoVote::withoutGlobalScopes()->create([
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'test2'),
            'vote_hash' => hash('sha256', 'test2-vote'),
            'candidate_01' => json_encode([
                'post_id' => $this->nationalPost->id,
                'candidates' => [['candidacy_id' => $this->candidateA->id]]
            ]),
        ]);

        DemoVote::withoutGlobalScopes()->create([
            'election_id' => $this->demoElection->id,
            'organisation_id' => $this->platformOrg->id,
            'voted_at' => now(),
            'cast_at' => now(),
            'receipt_hash' => hash('sha256', 'test3'),
            'vote_hash' => hash('sha256', 'test3-vote'),
            'candidate_01' => json_encode([
                'post_id' => $this->nationalPost->id,
                'candidates' => [['candidacy_id' => $this->candidateB->id]]
            ]),
        ]);

        $response = $this->get(route('public-demo.results'));

        $response->assertStatus(200);
        $response->assertInertia(fn (Assert $page) => $page
            ->component('Demo/Result/Index')
            ->where('mode', 'public')
            ->where('is_demo', true)
            ->has('posts', 1)
            ->has('final_result', fn (Assert $result) => $result
                ->where('total_votes', 3)
                ->has('posts.0.candidates', 2)
                // Assert Candidate Alpha wins the top spot (Sorted descending)
                ->where('posts.0.candidates.0.candidacy_id', $this->candidateA->id)
                ->where('posts.0.candidates.0.vote_count', 2)
                // Assert Candidate Beta is second
                ->where('posts.0.candidates.1.candidacy_id', $this->candidateB->id)
                ->where('posts.0.candidates.1.vote_count', 1)
            )
        );
    }

    /** @test */
    public function it_redirects_to_guide_if_election_is_inactive(): void
    {
        $this->demoElection->update(['is_active' => false]);

        $response = $this->get(route('public-demo.results'));

        $response->assertStatus(302);
        $response->assertRedirect(route('public-demo.guide'));
    }
}
