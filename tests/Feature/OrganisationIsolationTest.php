<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Models\Post;
use App\Models\Code;
use App\Models\VoterSlug;
use App\Models\Candidacy;
use App\Models\Vote;
use App\Models\Result;
use App\Models\UserOrganisationRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

/**
 * OrganisationIsolationTest
 *
 * Tests that organisation isolation is properly implemented for all P0 models.
 *
 * Test Coverage:
 * - Tests 1-3: Election isolation (requires BelongsToTenant trait)
 * - Tests 4-9: Other P0 models (already have BelongsToTenant)
 * - Tests 10-12: Election edge cases and scoping
 * - Test 13: N+1 prevention verification
 */
class OrganisationIsolationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $orgA;
    protected Organisation $orgB;
    protected User $userA;
    protected User $userB;

    protected function setUp(): void
    {
        parent::setUp();

        // Create two distinct organisations
        $this->orgA = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'org-a']);
        $this->orgB = Organisation::factory()->create(['type' => 'tenant', 'slug' => 'org-b']);

        // Create users belonging to each org
        $this->userA = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->userA->id,
            'organisation_id' => $this->orgA->id,
            'role' => 'admin',
        ]);

        $this->userB = User::factory()->create();
        UserOrganisationRole::create([
            'user_id' => $this->userB->id,
            'organisation_id' => $this->orgB->id,
            'role' => 'admin',
        ]);
    }

    // ============ ELECTION ISOLATION TESTS (1-3) ============
    // These tests will FAIL until BelongsToTenant is added to Election model

    /** @test */
    public function test_election_queries_auto_scope_to_current_organisation()
    {
        // Arrange: Set Org A context and create elections
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        // Act: Query elections in Org A context
        $elections = Election::all();

        // Assert: Should only see Org A elections
        $this->assertCount(1, $elections);
        $this->assertEquals($electionA->id, $elections->first()->id);
    }

    /** @test */
    public function test_cannot_see_other_org_election_by_direct_uuid()
    {
        // Arrange: Org A context, Org B election exists
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        // Act: Try to find Org B election by UUID
        $found = Election::find($electionB->id);

        // Assert: Should return null (global scope blocks it)
        $this->assertNull($found);
    }

    /** @test */
    public function test_election_creation_auto_sets_organisation_id_from_session()
    {
        // Arrange: Org A context
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        // Act: Create election WITHOUT explicitly setting org_id
        $election = Election::create([
            'name' => 'Test Election',
            'slug' => 'test-election-' . time(),
        ]);

        // Assert: organisation_id should be auto-filled from session
        $this->assertEquals($this->orgA->id, $election->organisation_id);
        $this->assertDatabaseHas('elections', [
            'id' => $election->id,
            'organisation_id' => $this->orgA->id,
        ]);
    }

    // ============ OTHER P0 MODELS ISOLATION TESTS (4-9) ============
    // These tests should PASS (Post, Code, VoterSlug, Candidacy, Vote, Result already have trait)

    /** @test */
    public function test_posts_are_isolated_per_organisation()
    {
        // Arrange
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        $postA = Post::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $electionA->id]);
        $postB = Post::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $electionB->id]);

        // Act: Query posts in Org A context
        $posts = Post::all();

        // Assert: Should only see Org A posts
        $this->assertCount(1, $posts);
        $this->assertEquals($postA->id, $posts->first()->id);
    }

    /** @test */
    public function test_codes_are_isolated_per_organisation()
    {
        // Arrange
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $codeA = Code::factory()->create(['organisation_id' => $this->orgA->id]);
        $codeB = Code::factory()->create(['organisation_id' => $this->orgB->id]);

        // Act: Query codes in Org A context
        $codes = Code::all();

        // Assert: Should only see Org A codes
        $this->assertCount(1, $codes);
        $this->assertEquals($codeA->id, $codes->first()->id);
    }

    /** @test */
    public function test_voter_slugs_are_isolated_per_organisation()
    {
        // Arrange
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        $slugA = VoterSlug::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $electionA->id]);
        $slugB = VoterSlug::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $electionB->id]);

        // Act: Query voter slugs in Org A context
        $slugs = VoterSlug::all();

        // Assert: Should only see Org A slugs
        $this->assertCount(1, $slugs);
        $this->assertEquals($slugA->id, $slugs->first()->id);
    }

    /** @test */
    public function test_candidacies_are_isolated_per_organisation()
    {
        // Arrange
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id]);
        $postA = Post::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $electionA->id]);

        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);
        $postB = Post::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $electionB->id]);

        $candA = Candidacy::factory()->create(['organisation_id' => $this->orgA->id, 'post_id' => $postA->id]);
        $candB = Candidacy::factory()->create(['organisation_id' => $this->orgB->id, 'post_id' => $postB->id]);

        // Act: Query candidacies in Org A context
        $candidacies = Candidacy::all();

        // Assert: Should only see Org A candidacies
        $this->assertCount(1, $candidacies);
        $this->assertEquals($candA->id, $candidacies->first()->id);
    }

    /** @test */
    public function test_votes_are_isolated_per_organisation()
    {
        // Arrange
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id, 'type' => 'real']);
        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id, 'type' => 'real']);

        $voteA = Vote::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $electionA->id]);
        $voteB = Vote::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $electionB->id]);

        // Act: Query votes in Org A context
        $votes = Vote::all();

        // Assert: Should only see Org A votes
        $this->assertCount(1, $votes);
        $this->assertEquals($voteA->id, $votes->first()->id);
    }

    /** @test */
    public function test_results_are_isolated_per_organisation()
    {
        // Arrange
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionA = Election::factory()->create(['organisation_id' => $this->orgA->id, 'type' => 'real']);
        $voteA = Vote::factory()->create(['organisation_id' => $this->orgA->id, 'election_id' => $electionA->id]);
        $resultA = Result::factory()->create(['organisation_id' => $this->orgA->id, 'vote_id' => $voteA->id]);

        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id, 'type' => 'real']);
        $voteB = Vote::factory()->create(['organisation_id' => $this->orgB->id, 'election_id' => $electionB->id]);
        $resultB = Result::factory()->create(['organisation_id' => $this->orgB->id, 'vote_id' => $voteB->id]);

        // Act: Query results in Org A context
        $results = Result::all();

        // Assert: Should only see Org A results
        $this->assertCount(1, $results);
        $this->assertEquals($resultA->id, $results->first()->id);
    }

    // ============ ELECTION EDGE CASES (10-12) ============
    // These tests will FAIL until BelongsToTenant is added to Election

    /** @test */
    public function test_without_session_context_election_returns_empty()
    {
        // Arrange: Create elections but clear session context
        Election::factory()->create(['organisation_id' => $this->orgA->id]);
        Election::factory()->create(['organisation_id' => $this->orgB->id]);
        session()->forget('current_organisation_id');

        // Act: Query elections without session context
        $elections = Election::all();

        // Assert: Should return empty (no context = no results for security)
        $this->assertCount(0, $elections);
    }

    /** @test */
    public function test_election_count_respects_organisation_scope()
    {
        // Arrange: Create multiple elections for each org
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        Election::factory()->count(3)->create(['organisation_id' => $this->orgA->id]);
        Election::factory()->count(2)->create(['organisation_id' => $this->orgB->id]);

        // Act: Count in Org A context
        $count = Election::count();

        // Assert: Should count only Org A elections
        $this->assertEquals(3, $count);
    }

    /** @test */
    public function test_election_find_returns_null_for_other_org()
    {
        // Arrange: Org A context with Org B election
        session(['current_organisation_id' => $this->orgA->id]);
        $this->actingAs($this->userA);

        $electionB = Election::factory()->create(['organisation_id' => $this->orgB->id]);

        // Act: Try find() with Org B election ID
        $found = Election::find($electionB->id);

        // Assert: Global scope should return null
        $this->assertNull($found);
    }

    // ============ N+1 PREVENTION TEST (13) ============
    // This test verifies the BelongsToTenant trait caches the platform org lookup

    /** @test */
    public function belongs_to_tenant_does_not_n_plus_one_on_platform_context()
    {
        // Arrange: Reset the static cache and enable query logging
        Election::resetPlatformOrgCache();
        DB::enableQueryLog();
        session()->forget('current_organisation_id');

        // Act - First query: Should lookup platform org and cache it
        Election::count();
        $firstQueryLog = DB::getQueryLog();

        // Count how many queries hit the organisations table (org lookup)
        // The platform org lookup queries the organisations table, others don't
        $platformOrgLookupQueries = array_filter($firstQueryLog, function ($query) {
            $queryLower = strtolower($query['query']);
            return str_contains($queryLower, 'organisations') &&
                   str_contains($queryLower, 'where') &&
                   str_contains($queryLower, 'slug');
        });

        // Act - Second query: Should use cached value, NO additional platform org lookup
        DB::flushQueryLog();
        Election::count();
        $secondQueryLog = DB::getQueryLog();

        // Filter to only platform org lookup queries (not the actual election count)
        $secondPlatformLookupQueries = array_filter($secondQueryLog, function ($query) {
            $queryLower = strtolower($query['query']);
            return str_contains($queryLower, 'organisations') &&
                   str_contains($queryLower, 'where') &&
                   str_contains($queryLower, 'slug');
        });

        // Assert: Platform org should only be looked up once (in first query), not again
        $this->assertGreaterThan(0, count($platformOrgLookupQueries), 'First query should lookup platform org (looking for organisations table query with slug WHERE clause). Queries: ' . json_encode(array_map(fn($q) => $q['query'], $firstQueryLog)));
        $this->assertEquals(0, count($secondPlatformLookupQueries), 'Second query should use cache, no additional org lookup');
    }
}
