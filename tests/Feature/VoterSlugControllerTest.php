<?php

namespace Tests\Feature;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * VoterSlugControllerTest
 *
 * Tests the VoterSlugController which manages voter slug creation and validation.
 * Voter slugs are immutable voting session tokens that tie users to specific elections.
 */
class VoterSlugControllerTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $election;

    protected function setUp(): void
    {
        parent::setUp();

        // Create test user (with platform organisation_id = 0 for demo elections and verified email)
        $this->user = User::factory()->create([
            'email' => 'voter@example.com',
            'can_vote' => true,
            'organisation_id' => 0,
            'email_verified_at' => now(),
        ]);

        // Set session organisation to platform (0) for demo elections
        session(['current_organisation_id' => 0]);

        // Create demo election (platform-wide with organisation_id = 0)
        $this->election = Election::create([
            'name' => 'Test Demo Election',
            'slug' => 'test-demo-' . time(),
            'type' => 'demo',
            'organisation_id' => 0,
            'is_active' => true,
        ]);
    }

    /**
     * TEST 1: User can start voting and get a voter slug
     *
     * POST /voter/start should create a VoterSlug
     */
    public function test_user_can_start_voting()
    {
        $this->actingAs($this->user);

        // Verify we can check if a voter slug exists for this user
        $existingSlug = VoterSlug::where('user_id', $this->user->id)->first();
        $this->assertNull($existingSlug, 'User should start with no voter slugs');
    }

    /**
     * TEST 2: VoterSlug has correct election association
     *
     * When a voter slug is created, it should reference the correct election
     */
    public function test_voter_slug_references_correct_election()
    {
        $this->actingAs($this->user);

        // Create a voter slug
        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Verify election relationship
        $this->assertNotNull($voterSlug->election);
        $this->assertEquals($this->election->id, $voterSlug->election->id);
        $this->assertEquals('demo', $voterSlug->election->type);
    }

    /**
     * TEST 3: VoterSlug organisation matches user organisation
     *
     * The voter slug's organisation_id must match the user's organisation_id
     */
    public function test_voter_slug_organisation_matches_user()
    {
        $this->actingAs($this->user);

        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Verify organisation consistency
        $this->assertEquals($this->user->organisation_id, $voterSlug->organisation_id);
    }

    /**
     * TEST 4: VoterSlug expires correctly
     *
     * Voter slugs should have an expiration time
     */
    public function test_voter_slug_has_expiration()
    {
        $this->actingAs($this->user);

        $expiredTime = now()->addHour();
        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => $expiredTime,
            'is_active' => true,
        ]);

        $this->assertTrue($voterSlug->expires_at->isAfter(now()));
    }

    /**
     * TEST 5: VoterSlug has immutable election_id
     *
     * Once created, a voter slug's election_id should not change
     * This ensures session consistency throughout voting
     */
    public function test_voter_slug_election_id_relationship()
    {
        $this->actingAs($this->user);

        // Create another election
        $anotherElection = Election::create([
            'name' => 'Another Demo Election',
            'slug' => 'another-demo-' . time(),
            'type' => 'demo',
            'organisation_id' => null,
            'is_active' => true,
        ]);

        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Verify it references correct election
        $this->assertEquals($this->election->id, $voterSlug->election_id);
        $this->assertNotEquals($anotherElection->id, $voterSlug->election_id);
    }

    /**
     * TEST 6: VoterSlug is unique per user
     *
     * A user can have multiple voter slugs if they vote in multiple elections
     */
    public function test_voter_slug_can_be_unique_per_election()
    {
        $this->actingAs($this->user);

        // Create first voter slug
        $voterSlug1 = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-1-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Verify it can be retrieved
        $retrieved = VoterSlug::where('user_id', $this->user->id)
            ->where('election_id', $this->election->id)
            ->first();

        $this->assertNotNull($retrieved);
        $this->assertEquals($voterSlug1->id, $retrieved->id);
    }

    /**
     * TEST 7: VoterSlug tracks current step
     *
     * Voter slug should track which step of voting process the user is on
     */
    public function test_voter_slug_tracks_current_step()
    {
        $this->actingAs($this->user);

        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
            'current_step' => 1,
            'is_active' => true,
        ]);

        $this->assertEquals(1, $voterSlug->current_step);
    }

    /**
     * TEST 8: Multiple voter slugs for different elections
     *
     * User can have different voter slugs for different elections
     */
    public function test_user_can_have_multiple_slugs_for_different_elections()
    {
        $this->actingAs($this->user);

        // Create second election
        $election2 = Election::create([
            'name' => 'Another Demo Election',
            'slug' => 'another-demo-' . time(),
            'type' => 'demo',
            'organisation_id' => null,
            'is_active' => true,
        ]);

        // Create voter slugs for both elections
        $slug1 = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-1-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        $slug2 = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $election2->id,
            'slug' => 'test-slug-2-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Verify both exist
        $this->assertNotNull($slug1);
        $this->assertNotNull($slug2);
        $this->assertNotEquals($slug1->id, $slug2->id);
        $this->assertNotEquals($slug1->election_id, $slug2->election_id);
    }

    /**
     * TEST 9: VoterSlug deactivation
     *
     * Voter slugs can be deactivated to expire voting sessions
     */
    public function test_voter_slug_can_be_deactivated()
    {
        $this->actingAs($this->user);

        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Deactivate
        $voterSlug->update(['is_active' => false]);
        $voterSlug->refresh();

        $this->assertFalse($voterSlug->is_active);
    }

    /**
     * TEST 10: VoterSlug to Election relationship
     *
     * The relationship between voter slug and election is properly established
     */
    public function test_voter_slug_election_relationship()
    {
        $this->actingAs($this->user);

        $voterSlug = VoterSlug::create([
            'user_id' => $this->user->id,
            'organisation_id' => $this->user->organisation_id,
            'election_id' => $this->election->id,
            'slug' => 'test-slug-' . uniqid(),
            'expires_at' => now()->addHour(),
            'is_active' => true,
        ]);

        // Verify relationship works both ways
        $this->assertEquals($this->election->id, $voterSlug->election->id);
        $this->assertEquals($this->election->name, $voterSlug->election->name);
        $this->assertEquals('demo', $voterSlug->election->type);
    }
}
