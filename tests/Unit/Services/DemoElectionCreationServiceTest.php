<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Models\Organisation;
use App\Models\Election;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Services\DemoElectionCreationService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoElectionCreationServiceTest extends TestCase
{
    use RefreshDatabase;

    private DemoElectionCreationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = app(DemoElectionCreationService::class);
    }

    // ==================== SERVICE UNIT TESTS ====================

    /**
     * @test
     * Scenario: Create demo election for organisation
     * Expected: Election created with correct organisation_id and slug
     */
    public function test_creates_election_with_correct_organisation_id()
    {
        // Arrange
        $org = Organisation::factory()->create(['name' => 'Test Org']);

        // Act
        $election = $this->service->createOrganisationDemoElection($org->id, $org);

        // Assert
        $this->assertNotNull($election);
        $this->assertEquals($org->id, $election->organisation_id);
        $this->assertEquals('demo-election-org-' . $org->id, $election->slug);
        $this->assertEquals('demo', $election->type);
        $this->assertTrue($election->is_active);
    }

    /**
     * @test
     * Scenario: Create demo election
     * Expected: 2 national posts created (President, Vice President)
     */
    public function test_creates_national_posts_with_candidates()
    {
        // Arrange
        $org = Organisation::factory()->create();

        // Act
        $election = $this->service->createOrganisationDemoElection($org->id, $org);

        // Assert: National posts created (use withoutGlobalScopes due to BelongsToTenant)
        $nationalPosts = DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('is_national_wide', 1)
            ->get();

        $this->assertEquals(2, $nationalPosts->count());

        // Verify post names
        $postNames = $nationalPosts->pluck('name')->toArray();
        $this->assertStringContainsString('President', $postNames[0]);
        $this->assertStringContainsString('President', $postNames[1]);

        // Verify each post has candidates (use withoutGlobalScopes)
        foreach ($nationalPosts as $post) {
            $candidates = DemoCandidacy::withoutGlobalScopes()
                ->where('post_id', $post->post_id)
                ->count();
            $this->assertEquals(3, $candidates);
        }
    }

    /**
     * @test
     * Scenario: Create demo election for organisation
     * Expected: Regional post created for Europe region
     */
    public function test_creates_regional_posts_for_europe()
    {
        // Arrange
        $org = Organisation::factory()->create();

        // Act
        $election = $this->service->createOrganisationDemoElection($org->id, $org);

        // Assert: Regional post for Europe created (use withoutGlobalScopes)
        $regionalPost = DemoPost::withoutGlobalScopes()
            ->where('election_id', $election->id)
            ->where('is_national_wide', 0)
            ->where('state_name', 'Europe')
            ->first();

        $this->assertNotNull($regionalPost);
        $this->assertEquals(0, $regionalPost->is_national_wide);
        $this->assertEquals('Europe', $regionalPost->state_name);

        // Verify post has candidates (use withoutGlobalScopes)
        $candidates = DemoCandidacy::withoutGlobalScopes()
            ->where('post_id', $regionalPost->post_id)
            ->count();
        $this->assertGreaterThan(0, $candidates);
    }
}
