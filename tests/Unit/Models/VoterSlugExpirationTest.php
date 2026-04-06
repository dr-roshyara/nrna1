<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\VoterSlug;
use App\Models\DemoVoterSlug;
use App\Models\User;
use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * VoterSlugExpirationTest
 *
 * Business Requirement: When a voter's session expires, they should be able to start fresh.
 * Expired slugs must be marked inactive to prevent blocking new voting attempts.
 */
class VoterSlugExpirationTest extends TestCase
{
    use RefreshDatabase;

    protected Organisation $organisation;

    protected function setUp(): void
    {
        parent::setUp();

        // Create tenant organisation for all tests
        $this->organisation = Organisation::factory()->create([
            'type' => 'tenant'
        ]);

        // Set tenant context in session so BelongsToTenant scope works
        session(['current_organisation_id' => $this->organisation->id]);
    }

    /**
     * RED TEST 1: Expired voter slug is marked inactive when retrieved
     *
     * BUSINESS: A voter with an expired session should not be blocked from voting again
     */
    public function test_expired_voter_slug_is_marked_inactive_when_retrieved()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug that expired 5 minutes ago
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->subMinutes(5),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // Refresh from database - booted() should mark it expired
        // Tenant context is set, so normal find() works with global scope
        $freshSlug = VoterSlug::find($slug->id);

        // BUSINESS ASSERTION: Expired slug must be inactive to prevent blocking
        $this->assertFalse($freshSlug->is_active, 'Expired slug must be marked inactive');
        $this->assertEquals('expired', $freshSlug->status);
        $this->assertFalse($freshSlug->can_vote_now, 'Expired slug cannot open voting window');
    }

    /**
     * RED TEST 2: Active, non-expired voter slug remains active
     *
     * BUSINESS: A voter with an active session should continue voting without interruption
     */
    public function test_active_non_expired_voter_slug_remains_active()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug that expires in 30 minutes
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // Refresh from database - active slugs should not be modified
        $freshSlug = VoterSlug::find($slug->id);

        // BUSINESS ASSERTION: Active session continues
        $this->assertTrue($freshSlug->is_active, 'Active slug must remain active');
        $this->assertEquals('active', $freshSlug->status);
    }

    /**
     * RED TEST 3: Demo elections always get new voter slugs (forceNew behavior)
     *
     * BUSINESS: Demo voting (testing) should always start fresh without persistence
     */
    public function test_demo_election_slug_is_always_new_on_retrieval()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'demo',
            'organisation_id' => $this->organisation->id,
        ]);

        // Create old demo slug from previous session
        $oldSlug = DemoVoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // For demo elections, even active slugs should be replaced on retrieval
        $service = app(\App\Services\VoterSlugService::class);
        $newSlug = $service->getOrCreateSlug($user, $election, forceNew: true);

        // BUSINESS ASSERTION: Demo gets fresh slug, not cached
        $this->assertNotEquals($oldSlug->slug, $newSlug->slug, 'Demo must always get fresh slug');
        $this->assertTrue($newSlug->is_active);
        $this->assertEquals('active', $newSlug->status);
    }

    /**
     * RED TEST 4: Real election reuses active, non-expired slug
     *
     * BUSINESS: Voters in real elections should not be forced to restart unnecessarily
     */
    public function test_real_election_reuses_active_non_expired_slug()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create active slug
        $existingSlug = VoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 2,
        ]);

        // Request same slug again (not forced)
        $service = app(\App\Services\VoterSlugService::class);
        $retrievedSlug = $service->getOrCreateSlug($user, $election, forceNew: false);

        // BUSINESS ASSERTION: Real election reuses active session
        $this->assertEquals($existingSlug->id, $retrievedSlug->id, 'Active session should be reused');
        $this->assertEquals($existingSlug->slug, $retrievedSlug->slug);
    }

    /**
     * RED TEST 5: Real election creates new slug when existing one is expired
     *
     * BUSINESS: Expired session → voter gets fresh start, not blocked
     */
    public function test_real_election_creates_new_slug_when_existing_is_expired()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create expired slug from previous session
        $expiredSlug = VoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->subMinutes(5),
            'is_active' => true,  // Expired but still marked active (bug we're fixing)
            'status' => 'active',
            'current_step' => 1,
        ]);

        // Request slug - should get new one
        $service = app(\App\Services\VoterSlugService::class);
        $newSlug = $service->getOrCreateSlug($user, $election, forceNew: false);

        // BUSINESS ASSERTION: Expired session replaced with fresh one
        $this->assertNotEquals($expiredSlug->id, $newSlug->id, 'Expired session must not be reused');
        $this->assertNotEquals($expiredSlug->slug, $newSlug->slug);
        $this->assertTrue($newSlug->is_active);
        $this->assertEquals('active', $newSlug->status);

        // Verify old slug was cleaned up (hard deleted to prevent constraint violations)
        $refreshedOldSlug = VoterSlug::withoutGlobalScopes()->find($expiredSlug->id);
        $this->assertNull($refreshedOldSlug, 'Old expired slug must be removed to allow fresh creation');
    }

    /**
     * RED TEST 6: Slug automatically sets expiration on creation
     *
     * BUSINESS: All voting sessions must have a time limit (30 minutes by default)
     */
    public function test_voter_slug_automatically_sets_expiration_on_creation()
    {
        $user = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug without explicit expires_at
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
            // Note: no expires_at provided
        ]);

        // BUSINESS ASSERTION: Expiration must be auto-set
        $this->assertNotNull($slug->expires_at, 'Slug must have expiration time');
        $this->assertTrue($slug->expires_at->isFuture(), 'Expiration must be in future');

        // Should default to 30 minutes
        $expectedExpiry = Carbon::now()->addMinutes(30);
        $this->assertTrue($slug->expires_at->diffInSeconds($expectedExpiry) < 5,
            'Expiration should be ~30 minutes from now');
    }

    /**
     * RED TEST 7: Voter slug must belong to correct election
     *
     * BUSINESS: A slug from Election A cannot be used for Election B
     * SECURITY: Prevents cross-election voting attempts
     */
    public function test_voter_slug_must_belong_to_correct_election()
    {
        $user = User::factory()->create();
        $election1 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);
        $election2 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug for election1
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'election_id' => $election1->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // Try to get slug for election2 with same user
        $service = app(\App\Services\VoterSlugService::class);
        $newSlug = $service->getOrCreateSlug($user, $election2);

        // BUSINESS ASSERTION: Should get NEW slug for election2, not reuse election1's slug
        $this->assertNotEquals($slug->id, $newSlug->id, 'Cannot reuse slug across elections');
        $this->assertEquals($election2->id, $newSlug->election_id);
    }

    /**
     * RED TEST 8: Voter slug must belong to correct user
     *
     * BUSINESS: User A's slug cannot be used by User B
     * SECURITY: Prevents vote theft attempts
     */
    public function test_voter_slug_must_belong_to_correct_user()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug for userA
        $slug = VoterSlug::create([
            'user_id' => $userA->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // Try to get slug for userB in same election
        $service = app(\App\Services\VoterSlugService::class);
        $newSlug = $service->getOrCreateSlug($userB, $election);

        // BUSINESS ASSERTION: Should get NEW slug for userB, not reuse userA's slug
        $this->assertNotEquals($slug->id, $newSlug->id, 'Cannot reuse slug across users');
        $this->assertEquals($userB->id, $newSlug->user_id);
    }

    /**
     * RED TEST 9: Service rejects slug from wrong user/election combination
     *
     * BUSINESS: Cannot use slug from User A in Election B
     * SECURITY: Complete ownership validation
     */
    public function test_service_rejects_slug_from_wrong_user_election_combination()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $election1 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);
        $election2 = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug for userA in election1
        $slug = VoterSlug::create([
            'user_id' => $userA->id,
            'election_id' => $election1->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // For userB in election2, service should create new slug
        $service = app(\App\Services\VoterSlugService::class);
        $newSlug = $service->getOrCreateSlug($userB, $election2);

        // BUSINESS ASSERTION: Complete isolation
        $this->assertNotEquals($slug->id, $newSlug->id);
        $this->assertEquals($userB->id, $newSlug->user_id);
        $this->assertEquals($election2->id, $newSlug->election_id);
    }

    /**
     * RED TEST 10: Service validates slug ownership on retrieval
     *
     * BUSINESS: Slug lookup must verify the slug belongs to requested user/election
     * SECURITY: Prevents unauthorized slug access
     */
    public function test_service_validates_slug_ownership_on_retrieval()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();
        $election = Election::factory()->create([
            'type' => 'real',
            'organisation_id' => $this->organisation->id
        ]);

        // Create slug for userA
        $slug = VoterSlug::create([
            'user_id' => $userA->id,
            'election_id' => $election->id,
            'organisation_id' => $this->organisation->id,
            'slug' => 'tbj' . Str::random(30),
            'expires_at' => Carbon::now()->addMinutes(30),
            'is_active' => true,
            'status' => 'active',
            'current_step' => 1,
        ]);

        // Try to retrieve userA's slug as userB
        $service = app(\App\Services\VoterSlugService::class);

        // BUSINESS ASSERTION: Should throw access denied
        $this->expectException(\Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException::class);
        $service->getValidatedSlug($slug->slug, $userB, $election);
    }
}
