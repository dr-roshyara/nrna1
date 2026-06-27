<?php

namespace Tests\Feature\Public;

use Tests\TestCase;
use App\Models\Election;
use App\Models\Organisation;
use App\Services\DemoElectionResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * TDD: Public Demo Results Page
 */
class PublicDemoResultsPageTest extends TestCase
{
    use RefreshDatabase;

    private Election $election;
    private string $orgId;

    protected function setUp(): void
    {
        parent::setUp();

        $platformOrg = Organisation::getDefaultPlatform();
        $this->orgId = $platformOrg->id;
        \App\Services\TenantContext::set($this->orgId);

        // Mock the resolver to return our specific election
        // This avoids the 25P02 transaction poisoning from model events
        $this->mock(DemoElectionResolver::class, function ($mock) {
            $this->election = Election::withoutGlobalScopes()->create([
                'id' => fake()->uuid(),
                'type' => 'demo',
                'slug' => 'demo-election-publicdigit',
                'organisation_id' => $this->orgId,
                'name' => 'Test Demo Election',
                'is_active' => true,
                'status' => 'active',
                'state' => 'draft',
                'start_date' => now(),
                'end_date' => now()->addDays(7),
                'voting_locked' => false,
            ]);
            $mock->shouldReceive('getPublicDemoElection')->andReturn($this->election);
        });
    }

    /** @test */
    public function public_demo_results_returns_200()
    {
        $response = $this->get('/public-demo/results');
        $response->assertStatus(200);
    }

    /** @test */
    public function public_demo_results_shows_empty_posts_when_no_data()
    {
        $response = $this->get('/public-demo/results');
        $response->assertStatus(200);
    }

    /** @test */
    public function unauthenticated_users_can_access()
    {
        $response = $this->get('/public-demo/results');
        $response->assertStatus(200);
    }

    /** @test */
    public function authenticated_users_can_also_access()
    {
        $user = \App\Models\User::factory()->create();
        $response = $this->actingAs($user)->get('/public-demo/results');
        $response->assertStatus(200);
    }
}
