<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Models\User;
use App\Models\Organisation;
use App\Models\Election;
use App\Models\ElectionMembership;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * ElectionMembership model test suite.
 *
 * Cache strategy: Option B (no Redis tags).
 *   Cache::remember(key, ttl, fn) + Cache::forget(key) — file-driver compatible.
 *
 * @see architecture/election/voter/20260317_2208_Voter_model.md
 */
class ElectionMembershipTest extends TestCase
{
    use RefreshDatabase;

    private Organisation $org;
    private User $member;
    private Election $election;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create(['type' => 'tenant']);

        $this->member = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($this->member->id, [
            'id'   => (string) Str::uuid(),
            'role' => 'voter',
        ]);

        $this->election = Election::factory()->create([
            'organisation_id' => $this->org->id,
        ]);
    }

    /**
     * Helper: Create a membership for testing (replaces deprecated assignVoter)
     */
    private function createMembership(User $user, Election $election): ElectionMembership
    {
        return ElectionMembership::create([
            'user_id'         => $user->id,
            'organisation_id' => $election->organisation_id,
            'election_id'     => $election->id,
            'role'            => 'voter',
            'status'          => 'active',
        ]);
    }

    // =========================================================================
    // isEligible()
    // =========================================================================

    public function test_is_eligible_returns_true_for_active_non_expired_membership(): void
    {
        $membership = $this->createMembership($this->member, $this->election);

        $this->assertTrue($membership->isEligible());
    }

    public function test_is_eligible_returns_false_when_status_is_inactive(): void
    {
        $membership = $this->createMembership($this->member, $this->election);
        $membership->update(['status' => 'inactive']);

        $this->assertFalse($membership->fresh()->isEligible());
    }

    public function test_is_eligible_returns_false_when_expires_at_is_past(): void
    {
        $membership = $this->createMembership($this->member, $this->election);
        $membership->update(['expires_at' => now()->subDay()]);

        $this->assertFalse($membership->fresh()->isEligible());
    }

    // =========================================================================
    // markAsVoted() and remove()
    // =========================================================================

    public function test_mark_as_voted_updates_last_activity_and_sets_inactive(): void
    {
        $membership = $this->createMembership($this->member, $this->election);

        $membership->markAsVoted();

        $fresh = $membership->fresh();
        $this->assertNotNull($fresh->last_activity_at);
        $this->assertEquals('inactive', $fresh->status);
    }

    public function test_remove_sets_status_to_removed_and_stores_reason_in_metadata(): void
    {
        $membership = $this->createMembership($this->member, $this->election);

        $membership->remove('Duplicate account');

        $fresh = $membership->fresh();
        $this->assertEquals('removed', $fresh->status);
        $this->assertEquals('Duplicate account', $fresh->metadata['removed_reason']);
        $this->assertArrayHasKey('removed_at', $fresh->metadata);
    }

    // =========================================================================
    // Relationships
    // =========================================================================

    public function test_membership_belongs_to_user(): void
    {
        $membership = $this->createMembership($this->member, $this->election);

        $this->assertEquals($this->member->id, $membership->user->id);
    }

    public function test_membership_belongs_to_election(): void
    {
        $membership = $this->createMembership($this->member, $this->election);

        $this->assertEquals($this->election->id, $membership->election->id);
    }

    public function test_user_has_voter_elections_relationship(): void
    {
        $election2 = Election::factory()->create(['organisation_id' => $this->org->id]);

        $this->createMembership($this->member, $this->election);
        $this->createMembership($this->member, $election2);

        $this->assertEquals(2, $this->member->voterElections()->count());
    }

    public function test_election_eligible_voters_excludes_expired_memberships(): void
    {
        // active member
        $this->createMembership($this->member, $this->election);

        // expired member
        $expiredUser = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($expiredUser->id, ['id' => (string) Str::uuid(), 'role' => 'voter']);
        $expired = $this->createMembership($expiredUser, $this->election);
        $expired->update(['expires_at' => now()->subDay()]);

        $this->assertEquals(1, $this->election->eligibleVoters()->count());
    }

    // =========================================================================
    // Scopes
    // =========================================================================

    public function test_eligible_scope_excludes_inactive_memberships(): void
    {
        $membership = $this->createMembership($this->member, $this->election);
        $membership->update(['status' => 'inactive']);

        $this->assertEquals(0, ElectionMembership::eligible()->count());
    }

    public function test_scope_voters_returns_only_voter_role(): void
    {
        $this->createMembership($this->member, $this->election);

        // Insert a candidate directly (bypassing assignVoter which defaults to 'voter')
        $candidate = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($candidate->id, ['id' => (string) Str::uuid(), 'role' => 'voter']);
        DB::table('election_memberships')->insert([
            'id'              => (string) Str::uuid(),
            'user_id'         => $candidate->id,
            'organisation_id' => $this->org->id,
            'election_id'     => $this->election->id,
            'role'            => 'candidate',
            'status'          => 'active',
            'assigned_at'     => now(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);

        $voters = ElectionMembership::voters()->get();

        $this->assertEquals(1, $voters->count());
        $this->assertEquals('voter', $voters->first()->role);
    }

    public function test_scope_for_election_isolates_memberships_per_election(): void
    {
        $election2 = Election::factory()->create(['organisation_id' => $this->org->id]);

        $member2 = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($member2->id, ['id' => (string) Str::uuid(), 'role' => 'voter']);

        $this->createMembership($this->member, $this->election);
        $this->createMembership($member2, $election2);

        $this->assertEquals(1, ElectionMembership::forElection($this->election->id)->count());
        $this->assertEquals(1, ElectionMembership::forElection($election2->id)->count());
    }

    // =========================================================================
    // Database constraint integrity (Blocker #1 from compatibility report)
    // =========================================================================

    public function test_composite_fk_rejects_membership_with_wrong_organisation(): void
    {
        // User is in $this->org. Trying to link them to a membership record that
        // claims they belong to a different organisation violates the composite FK.
        $otherOrg = Organisation::factory()->create(['type' => 'tenant']);

        $this->expectException(\Illuminate\Database\QueryException::class);

        DB::table('election_memberships')->insert([
            'id'              => (string) Str::uuid(),
            'user_id'         => $this->member->id,
            'organisation_id' => $otherOrg->id,      // mismatched — member is not in otherOrg
            'election_id'     => $this->election->id,
            'role'            => 'voter',
            'status'          => 'active',
            'assigned_at'     => now(),
            'created_at'      => now(),
            'updated_at'      => now(),
        ]);
    }

    public function test_cascade_delete_removes_memberships_when_user_leaves_organisation(): void
    {
        $this->createMembership($this->member, $this->election);

        // Removing from the pivot cascades to election_memberships via DB FK
        $this->org->users()->detach($this->member->id);

        $this->assertDatabaseMissing('election_memberships', [
            'user_id'     => $this->member->id,
            'election_id' => $this->election->id,
        ]);
    }

    // =========================================================================
    // Cache strategy: Option B — no tags, explicit key forgetting (Blocker #2)
    // =========================================================================

    public function test_voter_count_is_cached_after_first_access(): void
    {
        Config::set('cache.default', 'array'); // fast + supports has()

        $this->createMembership($this->member, $this->election);

        $cacheKey = "election.{$this->election->id}.voter_count";
        $this->assertFalse(Cache::has($cacheKey));

        // First access populates cache
        $count = $this->election->fresh()->voter_count;

        $this->assertEquals(1, $count);
        $this->assertTrue(Cache::has($cacheKey));
    }

    public function test_voter_count_cache_is_cleared_when_membership_added(): void
    {
        Config::set('cache.default', 'array');

        // Prime the cache
        $this->createMembership($this->member, $this->election);
        $this->election->fresh()->voter_count; // triggers cache write

        $cacheKey = "election.{$this->election->id}.voter_count";
        $this->assertTrue(Cache::has($cacheKey));

        // Adding a second voter should clear the cache
        $second = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($second->id, ['id' => (string) Str::uuid(), 'role' => 'voter']);
        $this->createMembership($second, $this->election);

        $this->assertFalse(Cache::has($cacheKey));
    }

    public function test_cache_works_with_file_driver_no_tags_required(): void
    {
        // Verifies Option B: Cache::remember + Cache::forget, no tags
        Config::set('cache.default', 'file');

        $key   = "election.test.voter_count";
        $value = Cache::remember($key, 60, fn () => 42);

        $this->assertEquals(42, $value);
        $this->assertTrue(Cache::has($key));

        Cache::forget($key);
        $this->assertFalse(Cache::has($key));

        // No BadMethodCallException — file driver is fully compatible
    }

    // =========================================================================
    // BUG #4 — voter_stats attribute on Election
    // =========================================================================

    public function test_voter_stats_returns_correct_structure(): void
    {
        Config::set('cache.default', 'array');

        $this->createMembership($this->member, $this->election);

        $stats = $this->election->fresh()->voter_stats;

        $this->assertIsArray($stats);
        $this->assertArrayHasKey('total_memberships',   $stats);
        $this->assertArrayHasKey('active_voters',       $stats);
        $this->assertArrayHasKey('eligible_voters',     $stats);
        $this->assertArrayHasKey('by_status',           $stats);
        $this->assertArrayHasKey('by_role',             $stats);
        $this->assertArrayHasKey('active',   $stats['by_status']);
        $this->assertArrayHasKey('inactive', $stats['by_status']);
        $this->assertArrayHasKey('invited',  $stats['by_status']);
        $this->assertArrayHasKey('removed',  $stats['by_status']);
        $this->assertArrayHasKey('voter',     $stats['by_role']);
        $this->assertArrayHasKey('candidate', $stats['by_role']);
    }

    public function test_voter_stats_counts_are_accurate(): void
    {
        Config::set('cache.default', 'array');

        $second = User::factory()->create(['email_verified_at' => now()]);
        $this->org->users()->attach($second->id, ['id' => (string) Str::uuid(), 'role' => 'voter']);

        $this->createMembership($this->member, $this->election);
        $m2 = $this->createMembership($second, $this->election);

        // Mark one as inactive
        $m2->markAsVoted();

        $stats = $this->election->fresh()->voter_stats;

        $this->assertEquals(2, $stats['total_memberships']);
        $this->assertEquals(1, $stats['active_voters']);   // only the un-voted one
        $this->assertEquals(1, $stats['eligible_voters']);
        $this->assertEquals(1, $stats['by_status']['active']);
        $this->assertEquals(1, $stats['by_status']['inactive']);
        $this->assertEquals(2, $stats['by_role']['voter']);
    }

    public function test_voter_stats_is_cached(): void
    {
        Config::set('cache.default', 'array');

        $this->createMembership($this->member, $this->election);

        $cacheKey = "election.{$this->election->id}.voter_stats";

        $this->election->fresh()->voter_stats; // prime cache
        $this->assertTrue(Cache::has($cacheKey));
    }

    public function test_voter_stats_cache_is_cleared_when_membership_changes(): void
    {
        Config::set('cache.default', 'array');

        $cacheKey = "election.{$this->election->id}.voter_stats";

        // Prime both caches
        $this->election->voter_stats;
        $this->assertTrue(Cache::has($cacheKey));

        // Adding a voter must clear the stats cache
        $this->createMembership($this->member, $this->election);

        $this->assertFalse(Cache::has($cacheKey));
    }

    // =========================================================================
    // BUG #3 — time-based expiration cache clearing command
    // =========================================================================

    public function test_flush_expired_voter_caches_command_clears_expiring_election_caches(): void
    {
        Config::set('cache.default', 'array');

        // Create a membership that expires in 30 minutes (within next hour)
        $membership = $this->createMembership($this->member, $this->election);
        $membership->update(['expires_at' => now()->addMinutes(30)]);

        // Prime both caches
        $this->election->fresh()->voter_count;
        $this->election->fresh()->voter_stats;

        $countKey = "election.{$this->election->id}.voter_count";
        $statsKey = "election.{$this->election->id}.voter_stats";

        $this->assertTrue(Cache::has($countKey));
        $this->assertTrue(Cache::has($statsKey));

        // Run the command
        $this->artisan('elections:flush-expiring-caches')->assertSuccessful();

        // Both caches should be cleared
        $this->assertFalse(Cache::has($countKey));
        $this->assertFalse(Cache::has($statsKey));
    }

    public function test_flush_expired_voter_caches_ignores_elections_with_no_expiring_voters(): void
    {
        Config::set('cache.default', 'array');

        // Membership with no expires_at — should not be touched
        $this->createMembership($this->member, $this->election);

        // Prime the cache
        $this->election->fresh()->voter_count;
        $countKey = "election.{$this->election->id}.voter_count";
        $this->assertTrue(Cache::has($countKey));

        $this->artisan('elections:flush-expiring-caches')->assertSuccessful();

        // Cache should still be there — nothing was expiring
        $this->assertTrue(Cache::has($countKey));
    }

    public function test_flush_expired_voter_caches_clears_already_expired_memberships(): void
    {
        Config::set('cache.default', 'array');

        // Membership that expired 1 hour ago
        $membership = $this->createMembership($this->member, $this->election);
        $membership->update(['expires_at' => now()->subHour()]);

        $this->election->fresh()->voter_count;
        $countKey = "election.{$this->election->id}.voter_count";
        $this->assertTrue(Cache::has($countKey));

        $this->artisan('elections:flush-expiring-caches')->assertSuccessful();

        $this->assertFalse(Cache::has($countKey));
    }

    // =========================================================================
    // has_voted / voted_at — Single Source of Truth
    // =========================================================================

    /** @test */
    public function mark_as_voted_sets_has_voted_true_and_stamps_voted_at(): void
    {
        $org = \App\Models\Organisation::factory()->create([
            'type' => 'tenant', 'slug' => 'test-' . uniqid(),
        ]);
        $user = \App\Models\User::factory()->create([
            'email_verified_at' => now(), 'onboarded_at' => now(),
        ]);
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $org->id, 'role' => 'member',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $election = \App\Models\Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id, 'type' => 'real', 'status' => 'active',
            'name' => 'Test ' . uniqid(), 'slug' => 'test-' . uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $membership = \App\Models\ElectionMembership::create([
            'user_id' => $user->id, 'organisation_id' => $org->id,
            'election_id' => $election->id, 'role' => 'voter', 'status' => 'active',
        ]);

        $this->assertFalse($membership->has_voted);
        $this->assertNull($membership->voted_at);

        $membership->markAsVoted();
        $membership->refresh();

        $this->assertTrue($membership->has_voted);
        $this->assertNotNull($membership->voted_at);
        $this->assertEquals('inactive', $membership->status);
    }

    /** @test */
    public function new_membership_defaults_has_voted_to_false(): void
    {
        $org = \App\Models\Organisation::factory()->create([
            'type' => 'tenant', 'slug' => 'test-' . uniqid(),
        ]);
        $user = \App\Models\User::factory()->create([
            'email_verified_at' => now(), 'onboarded_at' => now(),
        ]);
        \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
            'id' => \Illuminate\Support\Str::uuid(), 'user_id' => $user->id,
            'organisation_id' => $org->id, 'role' => 'member',
            'created_at' => now(), 'updated_at' => now(),
        ]);
        $election = \App\Models\Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id, 'type' => 'real', 'status' => 'active',
            'name' => 'Test ' . uniqid(), 'slug' => 'test-' . uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $membership = \App\Models\ElectionMembership::create([
            'user_id' => $user->id, 'organisation_id' => $org->id,
            'election_id' => $election->id, 'role' => 'voter', 'status' => 'active',
        ]);

        $this->assertFalse($membership->has_voted);
        $this->assertNull($membership->voted_at);
    }

    /** @test */
    public function scope_not_voted_excludes_members_who_have_voted(): void
    {
        $org = \App\Models\Organisation::factory()->create([
            'type' => 'tenant', 'slug' => 'test-' . uniqid(),
        ]);
        $userA = \App\Models\User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);
        $userB = \App\Models\User::factory()->create(['email_verified_at' => now(), 'onboarded_at' => now()]);

        foreach ([$userA, $userB] as $u) {
            \Illuminate\Support\Facades\DB::table('user_organisation_roles')->insert([
                'id' => \Illuminate\Support\Str::uuid(), 'user_id' => $u->id,
                'organisation_id' => $org->id, 'role' => 'member',
                'created_at' => now(), 'updated_at' => now(),
            ]);
        }
        $election = \App\Models\Election::withoutGlobalScopes()->create([
            'organisation_id' => $org->id, 'type' => 'real', 'status' => 'active',
            'name' => 'Test', 'slug' => 'test-' . uniqid(),
            'start_date' => now()->subDay(), 'end_date' => now()->addDay(),
        ]);
        $mA = \App\Models\ElectionMembership::create([
            'user_id' => $userA->id, 'organisation_id' => $org->id,
            'election_id' => $election->id, 'role' => 'voter', 'status' => 'active',
        ]);
        \App\Models\ElectionMembership::create([
            'user_id' => $userB->id, 'organisation_id' => $org->id,
            'election_id' => $election->id, 'role' => 'voter', 'status' => 'active',
        ]);
        $mA->markAsVoted();

        $notVotedIds = \App\Models\ElectionMembership::notVoted()
            ->where('election_id', $election->id)
            ->pluck('user_id')
            ->toArray();

        $this->assertContains($userB->id, $notVotedIds);
        $this->assertNotContains($userA->id, $notVotedIds);
    }
}
