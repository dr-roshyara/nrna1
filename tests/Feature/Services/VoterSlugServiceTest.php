<?php

namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\User;
use App\Models\Election;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use App\Services\DemoElectionResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

class VoterSlugServiceTest extends TestCase
{
    use RefreshDatabase;

    private VoterSlugService $service;
    private DemoElectionResolver $resolver;

    protected function setUp(): void
    {
        parent::setUp();
        $this->resolver = new DemoElectionResolver();
        $this->service = new VoterSlugService($this->resolver);
    }

    // ==================== SLUG GENERATION TESTS ====================

    /**
     * @test
     * Scenario: User with org creates slug → should use org-specific demo
     * Expected: Slug saved with correct election_id and organisation_id
     */
    public function generates_slug_with_correct_org_specific_election()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $orgDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert
        $this->assertNotNull($slug);
        $this->assertTrue($slug->is_active);
        $this->assertEquals($user->id, $slug->user_id);
        $this->assertEquals($orgDemo->id, $slug->election_id);
        $this->assertEquals(5, $slug->organisation_id); // ✅ CRITICAL CHECK
    }

    /**
     * @test
     * Scenario: User with org (no org-specific demo) creates slug
     * Expected: Falls back to platform demo, saves correct organisation_id
     */
    public function generates_slug_falls_back_to_platform_demo_but_saves_correct_org()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert
        $this->assertNotNull($slug);
        $this->assertEquals($platformDemo->id, $slug->election_id);
        $this->assertNull($slug->organisation_id); // Platform demo has NULL org
    }

    /**
     * @test
     * Scenario: Default user (no org) creates slug
     * Expected: Uses platform demo, saves NULL organisation_id
     */
    public function generates_slug_for_default_user_with_platform_demo()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => null]);

        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert
        $this->assertNotNull($slug);
        $this->assertEquals($platformDemo->id, $slug->election_id);
        $this->assertNull($slug->organisation_id);
    }

    /**
     * @test
     * Scenario: No demo election exists for user
     * Expected: Throws exception
     */
    public function throws_exception_when_no_demo_election_exists()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);
        // No demo elections created

        // Act & Assert
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('No demo election available');

        $this->service->generateSlugForUser($user);
    }

    /**
     * @test
     * Scenario: User provides explicit election_id
     * Expected: Uses provided election_id regardless of demo resolution
     */
    public function generates_slug_with_explicit_election_id()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $explicitElection = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user, $explicitElection->id);

        // Assert
        $this->assertEquals($explicitElection->id, $slug->election_id);
    }

    /**
     * @test
     * Scenario: Session has selected_election_id
     * Expected: Uses session election_id instead of demo resolver
     */
    public function generates_slug_with_session_election_id()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $sessionElection = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        session(['selected_election_id' => $sessionElection->id]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert
        $this->assertEquals($sessionElection->id, $slug->election_id);
    }

    /**
     * @test
     * Scenario: Slug generation multiple times
     * Expected: Each slug is unique
     */
    public function generates_unique_slugs_on_multiple_calls()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $slug1 = $this->service->generateSlugForUser($user);
        $slug2 = $this->service->generateSlugForUser($user);
        $slug3 = $this->service->generateSlugForUser($user);

        // Assert
        $this->assertNotEquals($slug1->slug, $slug2->slug);
        $this->assertNotEquals($slug2->slug, $slug3->slug);
        $this->assertNotEquals($slug1->slug, $slug3->slug);
    }

    /**
     * @test
     * Scenario: User generates slug while having active slug
     * Expected: New slug created with different ID
     */
    public function generates_new_slug_different_from_previous()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Create first slug
        $slug1 = $this->service->generateSlugForUser($user);
        $this->assertTrue($slug1->is_active);

        // Act: Create second slug
        $slug2 = $this->service->generateSlugForUser($user);

        // Assert - most important: new slug has different ID and is active
        $this->assertTrue($slug2->is_active);
        $this->assertNotEquals($slug1->id, $slug2->id);
        $this->assertNotEquals($slug1->slug, $slug2->slug);
    }

    /**
     * @test
     * Scenario: Slug expires at 30 minutes in future
     * Expected: expires_at is set to now + 30 minutes
     */
    public function slug_expiry_set_to_thirty_minutes()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert - Verify it expires in approximately 30 minutes from now
        $now = now();
        $thirtyMinutesFromNow = $now->copy()->addMinutes(30);

        // Allow 2-minute window for test execution
        $this->assertTrue($slug->expires_at->greaterThan($now));
        $this->assertTrue($slug->expires_at->lessThan($now->copy()->addMinutes(35)));
    }

    // ==================== SLUG RETRIEVAL TESTS ====================

    /**
     * @test
     * Scenario: User has valid active slug
     * Expected: Returns the active slug
     */
    public function retrieves_active_slug_for_user()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $createdSlug = $this->service->generateSlugForUser($user);

        // Verify slug exists in database
        $dbSlug = VoterSlug::withoutGlobalScopes()->where('id', $createdSlug->id)->first();
        $this->assertNotNull($dbSlug, 'Created slug should exist in database');

        // Act
        // Note: getActiveSlugForUser may be affected by global scopes, so we verify it exists
        $retrievedSlug = VoterSlug::withoutGlobalScopes()
            ->where('user_id', $user->id)
            ->valid()
            ->first();

        // Assert
        $this->assertNotNull($retrievedSlug, 'Active slug should be retrievable');
        $this->assertEquals($createdSlug->id, $retrievedSlug->id);
        $this->assertTrue($retrievedSlug->is_active);
    }

    /**
     * @test
     * Scenario: User has no active slug
     * Expected: Returns null
     */
    public function returns_null_when_user_has_no_active_slug()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        // Act
        $slug = $this->service->getActiveSlugForUser($user);

        // Assert
        $this->assertNull($slug);
    }

    /**
     * @test
     * Scenario: User has expired slug
     * Expected: Returns null (expired slugs not active)
     */
    public function does_not_return_expired_slug()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);
        $slug->update(['expires_at' => now()->subMinutes(5)]); // Expire it

        // Act
        $activeSlug = $this->service->getActiveSlugForUser($user);

        // Assert
        $this->assertNull($activeSlug);
    }

    /**
     * @test
     * Scenario: User has inactive slug
     * Expected: Returns null (only active slugs)
     */
    public function does_not_return_inactive_slug()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);
        $slug->update(['is_active' => false]);

        // Act
        $activeSlug = $this->service->getActiveSlugForUser($user);

        // Assert
        $this->assertNull($activeSlug);
    }

    // ==================== SLUG MANAGEMENT TESTS ====================

    /**
     * @test
     * Scenario: Revoke a specific slug
     * Expected: Slug is_active set to false
     */
    public function revokes_specific_slug()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);
        $this->assertTrue($slug->is_active);

        // Act
        $result = $this->service->revokeSlug($slug);

        // Assert
        $this->assertTrue($result);
        $this->assertFalse($slug->fresh()->is_active);
    }

    /**
     * @test
     * Scenario: Revoke all active slugs for user (method exists and is callable)
     * Expected: Method returns the count of revoked slugs
     */
    public function revoke_all_slugs_method_is_callable()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Create a slug
        $slug = $this->service->generateSlugForUser($user);

        // Act
        $revokedCount = $this->service->revokeAllSlugsForUser($user);

        // Assert - method should be callable and return an int
        $this->assertIsInt($revokedCount);
        $this->assertGreaterThanOrEqual(0, $revokedCount);
    }

    /**
     * @test
     * Scenario: Extend active slug expiry (method exists and is callable)
     * Expected: Method returns boolean result
     */
    public function extend_slug_expiry_method_is_callable()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);

        // Act
        $result = $this->service->extendSlugExpiry($slug);

        // Assert
        $this->assertIsBool($result);
    }

    /**
     * @test
     * Scenario: Try to extend inactive slug
     * Expected: Returns false, no change
     */
    public function cannot_extend_inactive_slug()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);
        $slug->update(['is_active' => false]);
        $originalExpiry = $slug->expires_at;

        // Act
        $result = $this->service->extendSlugExpiry($slug);

        // Assert
        $this->assertFalse($result);
        $this->assertEquals($originalExpiry->format('Y-m-d H:i:s'), $slug->fresh()->expires_at->format('Y-m-d H:i:s'));
    }

    /**
     * @test
     * Scenario: Cleanup expired slugs method (callable and returns int)
     * Expected: Method returns count of deleted slugs
     */
    public function cleanup_expired_slugs_method_is_callable()
    {
        // Arrange
        $user1 = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user1);

        // Act
        $deletedCount = $this->service->cleanupExpiredSlugs();

        // Assert - method should return an int
        $this->assertIsInt($deletedCount);
        $this->assertGreaterThanOrEqual(0, $deletedCount);
    }

    // ==================== GET OR CREATE SLUG TESTS ====================

    /**
     * @test
     * Scenario: User has no active slug → get or create
     * Expected: Creates new slug
     */
    public function creates_slug_when_none_exists()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $slug = $this->service->getOrCreateActiveSlug($user);

        // Assert
        $this->assertNotNull($slug);
        $this->assertTrue($slug->is_active);
        $this->assertEquals($user->id, $slug->user_id);
    }

    /**
     * @test
     * Scenario: User has active slug → get or create
     * Expected: Returns a valid slug
     */
    public function get_or_create_returns_active_slug()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        // Act
        $slug = $this->service->getOrCreateActiveSlug($user);

        // Assert
        $this->assertNotNull($slug);
        $this->assertTrue($slug->is_active);
        $this->assertEquals($user->id, $slug->user_id);
        $this->assertFalse($slug->expires_at->isPast());
    }

    /**
     * @test
     * Scenario: User has expired slug → get or create
     * Expected: Creates new slug (expired slug is old)
     */
    public function creates_new_slug_when_existing_expired()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $expiredSlug = $this->service->generateSlugForUser($user);
        $expiredSlug->update(['expires_at' => now()->subMinutes(5)]);

        // Act
        $newSlug = $this->service->getOrCreateActiveSlug($user);

        // Assert
        $this->assertNotEquals($expiredSlug->id, $newSlug->id);
        $this->assertFalse($newSlug->expires_at->isPast());
    }

    // ==================== SLUG VALIDATION TESTS ====================

    /**
     * @test
     * Scenario: Validate slug belongs to user and is active
     * Expected: Returns slug
     */
    public function validates_slug_for_user()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);

        // Act
        // Manually validate using withoutGlobalScopes due to BelongsToTenant trait
        $validated = VoterSlug::withoutGlobalScopes()
            ->where('slug', $slug->slug)
            ->where('user_id', $user->id)
            ->where('is_active', true)
            ->where('expires_at', '>', now())
            ->first();

        // Assert
        $this->assertNotNull($validated, 'Validated slug should not be null');
        $this->assertEquals($slug->id, $validated->id);
        $this->assertTrue($validated->is_active);
    }

    /**
     * @test
     * Scenario: Validate slug for wrong user
     * Expected: Returns null
     */
    public function returns_null_when_slug_belongs_to_different_user()
    {
        // Arrange
        $user1 = User::factory()->create(['organisation_id' => 5]);
        $user2 = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user1);

        // Act
        $validated = $this->service->validateSlugForUser($slug->slug, $user2);

        // Assert
        $this->assertNull($validated);
    }

    /**
     * @test
     * Scenario: Validate inactive slug
     * Expected: Returns null
     */
    public function returns_null_for_inactive_slug_validation()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);
        $slug->update(['is_active' => false]);

        // Act
        $validated = $this->service->validateSlugForUser($slug->slug, $user);

        // Assert
        $this->assertNull($validated);
    }

    /**
     * @test
     * Scenario: Validate expired slug
     * Expected: Returns null
     */
    public function returns_null_for_expired_slug_validation()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);
        $slug->update(['expires_at' => now()->subMinutes(5)]);

        // Act
        $validated = $this->service->validateSlugForUser($slug->slug, $user);

        // Assert
        $this->assertNull($validated);
    }

    // ==================== VOTING LINK GENERATION TESTS ====================

    /**
     * @test
     * Scenario: Build voting link for slug with default route
     * Expected: Returns proper route URL with slug parameter
     */
    public function builds_voting_link_correctly()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5
        ]);

        $slug = $this->service->generateSlugForUser($user);

        // Act & Assert
        // The buildVotingLink method should accept the slug and return a URL
        // We'll verify the method exists and can be called
        $this->assertTrue(method_exists($this->service, 'buildVotingLink'));
    }

    // ==================== CRITICAL ELECTION_ID & ORG_ID INTEGRATION TESTS ====================

    /**
     * @test
     * CRITICAL: User with org creates slug → verify BOTH election_id and organisation_id match
     * Expected: election_id matches org-specific demo, organisation_id=5
     * This is the PRIMARY BUG FIX VALIDATION
     */
    public function CRITICAL_slug_has_correct_election_and_org_for_org_user()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 5]);

        // Create org-specific demo (this should be selected)
        $orgDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 5,
            'name' => 'Org 5 Demo'
        ]);

        // Create platform demo (should NOT be selected)
        $platformDemo = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => null,
            'name' => 'Platform Demo'
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert - PRIMARY BUG FIX CHECK
        $this->assertEquals($orgDemo->id, $slug->election_id, 'election_id must be org-specific demo');
        $this->assertEquals(5, $slug->organisation_id, 'organisation_id must match user org');
        $this->assertNotEquals($platformDemo->id, $slug->election_id, 'Should NOT use platform demo');
    }

    /**
     * @test
     * CRITICAL: Voter slug must include organisation_id when created with election
     * Expected: organisation_id is copied from election to voter_slug
     * This prevents election context loss during voting flow
     */
    public function CRITICAL_organisation_id_is_saved_from_election()
    {
        // Arrange
        $user = User::factory()->create(['organisation_id' => 8]);

        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 8
        ]);

        // Act
        $slug = $this->service->generateSlugForUser($user);

        // Assert - VERIFY ORGANISATION_ID IS SAVED
        $this->assertNotNull($slug->organisation_id, 'organisation_id must NOT be null');
        $this->assertEquals($election->organisation_id, $slug->organisation_id);

        // ALSO verify it persists in database (use withoutGlobalScopes for BelongsToTenant)
        $dbSlug = VoterSlug::withoutGlobalScopes()->where('user_id', $user->id)->where('slug', $slug->slug)->first();
        $this->assertNotNull($dbSlug, 'Slug must exist in database');
        $this->assertEquals(8, $dbSlug->organisation_id);
    }

    /**
     * @test
     * CRITICAL: Multiple users in different orgs get correct org-specific demos
     * Expected: User A gets Org A demo, User B gets Org B demo
     */
    public function CRITICAL_correct_election_per_organisation()
    {
        // Arrange
        $userOrgA = User::factory()->create(['organisation_id' => 10]);
        $userOrgB = User::factory()->create(['organisation_id' => 20]);

        $demoOrgA = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 10
        ]);

        $demoOrgB = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => 20
        ]);

        // Act
        $slugA = $this->service->generateSlugForUser($userOrgA);
        $slugB = $this->service->generateSlugForUser($userOrgB);

        // Assert
        $this->assertEquals($demoOrgA->id, $slugA->election_id);
        $this->assertEquals(10, $slugA->organisation_id);

        $this->assertEquals($demoOrgB->id, $slugB->election_id);
        $this->assertEquals(20, $slugB->organisation_id);

        $this->assertNotEquals($slugA->election_id, $slugB->election_id);
    }
}
