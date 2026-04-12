<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use App\Models\DemoPost;
use App\Models\DemoCandidacy;
use App\Models\DemoCode;
use App\Services\DemoElectionResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DemoElectionAutoCreationTest extends TestCase
{
    use RefreshDatabase;

    private DemoElectionResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new DemoElectionResolver();
    }

    // ==================== AUTO-CREATION TESTS ====================

    /**
     * @test
     * Scenario: User with organisation tries to access voting, no org-specific demo exists
     * Expected: Demo election auto-created with all demo data
     */
    public function test_auto_creates_org_specific_demo_when_user_accesses_voting()
    {
        // Arrange: User with organisation, no demo exists
        $org = Organisation::factory()->create(['name' => 'NRNA Europe']);
        $user = User::factory()->create(['organisation_id' => $org->id]);

        // Verify no demo exists
        $this->assertNull(
            Election::withoutGlobalScopes()
                ->where('type', 'demo')
                ->where('organisation_id', $org->id)
                ->first()
        );

        // Act: Get demo election for user
        $demoElection = $this->resolver->getDemoElectionForUser($user);

        // Assert: Demo was auto-created
        $this->assertNotNull($demoElection);
        $this->assertEquals('demo', $demoElection->type);
        $this->assertEquals($org->id, $demoElection->organisation_id);
        $this->assertEquals('demo-election-org-' . $org->id, $demoElection->slug);

        // Assert: Demo data was created (use withoutGlobalScopes due to BelongsToTenant)
        $posts = DemoPost::withoutGlobalScopes()->where('election_id', $demoElection->id)->count();
        $candidates = DemoCandidacy::withoutGlobalScopes()->where('election_id', $demoElection->id)->count();
        $codes = DemoCode::withoutGlobalScopes()->where('election_id', $demoElection->id)->count();

        $this->assertEquals(4, $posts);       // 2 national + 2 regional (State Rep + District Rep for Europe)
        $this->assertGreaterThan(5, $candidates);  // ~9 candidates (3+3+3+2)
        $this->assertGreaterThan(0, $codes);   // ~9 codes
    }

    /**
     * @test
     * Scenario: User with org calls resolver second time
     * Expected: Returns existing demo (no duplication)
     */
    public function test_uses_existing_org_demo_when_already_created()
    {
        // Arrange: User with org, demo already exists
        $org = Organisation::factory()->create();
        $user = User::factory()->create(['organisation_id' => $org->id]);

        $existingDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => $org->id,
        ]);

        // Act: Get demo election
        $demoElection = $this->resolver->getDemoElectionForUser($user);

        // Assert: Returns existing demo (no duplication)
        $this->assertEquals($existingDemo->id, $demoElection->id);

        // Assert: No duplicate elections created
        $demoCount = Election::withoutGlobalScopes()
            ->where('type', 'demo')
            ->where('organisation_id', $org->id)
            ->count();
        $this->assertEquals(1, $demoCount);
    }

    /**
     * @test
     * Scenario: Auto-created demo has organisation_id in all related data
     * Expected: Election, posts, candidates, codes all have correct organisation_id
     */
    public function test_organisation_id_propagated_to_all_demo_data()
    {
        // Arrange
        $org = Organisation::factory()->create();
        $user = User::factory()->create(['organisation_id' => $org->id]);

        // Act: Auto-create demo
        $demoElection = $this->resolver->getDemoElectionForUser($user);

        // Assert: All posts have organisation_id (use withoutGlobalScopes)
        $posts = DemoPost::withoutGlobalScopes()->where('election_id', $demoElection->id)->get();
        $this->assertGreaterThan(0, $posts->count());
        foreach ($posts as $post) {
            $this->assertEquals($org->id, $post->organisation_id,
                "Post {$post->id} should have organisation_id {$org->id}");
        }

        // Assert: All candidates have organisation_id (use withoutGlobalScopes)
        $candidates = DemoCandidacy::withoutGlobalScopes()->where('election_id', $demoElection->id)->get();
        $this->assertGreaterThan(0, $candidates->count());
        foreach ($candidates as $candidate) {
            $this->assertEquals($org->id, $candidate->organisation_id,
                "Candidate {$candidate->id} should have organisation_id {$org->id}");
        }

        // Assert: All codes have organisation_id (use withoutGlobalScopes)
        $codes = DemoCode::withoutGlobalScopes()->where('election_id', $demoElection->id)->get();
        $this->assertGreaterThan(0, $codes->count());
        foreach ($codes as $code) {
            $this->assertEquals($org->id, $code->organisation_id,
                "Code {$code->id} should have organisation_id {$org->id}");
        }
    }
}
