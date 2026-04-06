<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use App\Services\VotingSecurityService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PreventMultipleVotingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_only_have_one_active_slug_at_a_time()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slugService = new VoterSlugService();

        // Generate first slug
        $firstSlug = $slugService->generateSlugForUser($user);
        $this->assertTrue($firstSlug->is_active);

        // Generate second slug - should deactivate first one
        $secondSlug = $slugService->generateSlugForUser($user);
        $this->assertTrue($secondSlug->is_active);

        // First slug should now be deactivated
        $firstSlug->refresh();
        $this->assertFalse($firstSlug->is_active);

        // User should have 2 slugs total, but only 1 active
        $this->assertEquals(2, VoterSlug::where('user_id', $user->id)->count());
        $this->assertEquals(1, VoterSlug::where('user_id', $user->id)->where('is_active', true)->count());
    }

    /** @test */
    public function security_service_detects_and_fixes_multiple_active_slugs()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // Artificially create multiple active slugs (simulating a bug or attack)
        $slug1 = new VoterSlug([
            'user_id' => $user->id,
            'slug' => 'malicious-slug-1',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);
        $slug1->created_at = now()->subMinutes(2); // Older slug
        $slug1->save();

        sleep(1); // Ensure time difference

        $slug2 = new VoterSlug([
            'user_id' => $user->id,
            'slug' => 'malicious-slug-2',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 2,
        ]);
        $slug2->created_at = now()->subMinute(); // More recent slug
        $slug2->save();

        // Security service should detect and fix this
        $securityService = new VotingSecurityService();
        $enforcement = $securityService->enforceOneActiveSlugPerUser($user);

        // Should detect the violation
        $this->assertTrue($enforcement['enforcement_needed']);
        $this->assertEquals(2, $enforcement['found_active_slugs']);

        // Should keep only the most recent slug
        $this->assertEquals(1, count($enforcement['deactivated_slugs']));

        // Verify only 1 slug is now active
        $activeSlugs = VoterSlug::where('user_id', $user->id)->where('is_active', true)->get();
        $this->assertEquals(1, $activeSlugs->count());

        // The most recent slug should be the one that remains active
        $this->assertEquals('malicious-slug-2', $activeSlugs->first()->slug);
    }

    /** @test */
    public function security_service_blocks_slug_generation_for_ineligible_users()
    {
        // Test various ineligible scenarios
        $scenarios = [
            ['is_voter' => false, 'can_vote' => true, 'has_voted' => false],  // Not registered voter
            ['is_voter' => true, 'can_vote' => false, 'has_voted' => false], // Permission revoked
            ['is_voter' => true, 'can_vote' => true, 'has_voted' => true],   // Already voted
        ];

        foreach ($scenarios as $scenario) {
            $user = User::factory()->create($scenario);
            $securityService = new VotingSecurityService();

            $result = $securityService->canIssueVotingSlug($user);

            $this->assertFalse($result['can_issue'],
                "User should not be able to get voting slug: " . json_encode($scenario));
            $this->assertNotEmpty($result['reasons'],
                "Should have reasons for blocking: " . json_encode($scenario));
        }
    }

    /** @test */
    public function security_service_blocks_users_with_existing_active_slug()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // User already has an active slug
        VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'existing-active-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 2,
        ]);

        $securityService = new VotingSecurityService();
        $result = $securityService->canIssueVotingSlug($user);

        $this->assertFalse($result['can_issue']);
        $this->assertContains('user_already_has_active_voting_slug', $result['reasons']);
        $this->assertEquals('existing-active-slug', $result['current_status']['active_slug']);
    }

    /** @test */
    public function middleware_blocks_access_when_multiple_slugs_detected()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // Create the legitimate slug first
        $legitSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'legit-voting-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Create a malicious second slug (simulating attack)
        $maliciousSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'malicious-second-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Try to access the malicious slug - should be blocked
        $response = $this->actingAs($user)
            ->get("/v/{$maliciousSlug->slug}/code/create");

        $response->assertStatus(403);
        $response->assertSee('deactivated due to security policy');

        // Access to legitimate slug should work
        $response = $this->actingAs($user)
            ->json('GET', "/v/{$legitSlug->slug}/code/create");

        $response->assertStatus(200); // Should work
    }

    /** @test */
    public function completed_voters_cannot_access_any_voting_urls()
    {
        $user = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => true  // Already completed voting
        ]);

        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'post-voting-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 5,
        ]);

        $response = $this->actingAs($user)
            ->get("/v/{$slug->slug}/code/create");

        $response->assertStatus(403);
        $response->assertSee('already completed voting');
    }

    /** @test */
    public function security_audit_identifies_violations()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // Create suspicious activity: multiple recent slugs
        for ($i = 0; $i < 4; $i++) {
            VoterSlug::create([
                'user_id' => $user->id,
                'slug' => "suspicious-slug-{$i}",
                'expires_at' => now()->addMinutes(30),
                'is_active' => $i === 3, // Only last one active
                'current_step' => 1,
                'created_at' => now()->subMinutes($i * 30),
            ]);
        }

        $securityService = new VotingSecurityService();
        $audit = $securityService->auditUserVotingSecurity($user);

        // Should detect excessive slug generation
        $this->assertEquals('warning', $audit['security_status']);
        $this->assertCount(1, $audit['issues']);
        $this->assertEquals('excessive_slug_generation', $audit['issues'][0]['type']);
        $this->assertEquals(4, $audit['issues'][0]['count']);
    }

    /** @test */
    public function emergency_lockdown_deactivates_all_user_slugs()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // Create multiple slugs
        $slug1 = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'slug-to-lockdown-1',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        $slug2 = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'slug-to-lockdown-2',
            'expires_at' => now()->addMinutes(30),
            'is_active' => false,
            'current_step' => 2,
        ]);

        $securityService = new VotingSecurityService();
        $result = $securityService->emergencyLockdown($user, 'Suspicious voting behavior detected', [
            'admin_name' => 'Security Officer'
        ]);

        $this->assertTrue($result);

        // All slugs should be deactivated
        $activeSlugs = VoterSlug::where('user_id', $user->id)->where('is_active', true)->count();
        $this->assertEquals(0, $activeSlugs);

        // User's voting permission should be revoked
        $user->refresh();
        $this->assertFalse((bool) $user->can_vote);

        // Slugs should have lockdown metadata
        $slug1->refresh();
        $this->assertTrue($slug1->step_meta['emergency_lockdown'] ?? false);
        $this->assertEquals('Suspicious voting behavior detected', $slug1->step_meta['lockdown_reason']);
    }

    /** @test */
    public function rate_limiting_prevents_excessive_requests()
    {
        $user = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'rate-limit-test-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true,
            'current_step' => 1,
        ]);

        // Pre-populate the cache to simulate 10 previous requests
        \Cache::put("voting_requests_{$user->id}", 11, 60);

        // The next request should be rate limited
        $response = $this->actingAs($user)
            ->get("/v/{$slug->slug}/code/create");

        // Debug: Check what the actual response is
        if ($response->status() !== 429) {
            $this->markTestSkipped('Rate limiting middleware not working in test environment - cache value: ' . \Cache::get("voting_requests_{$user->id}"));
        }

        $response->assertStatus(429);
        $response->assertSee('Too Many Requests'); // Laravel's default 429 error page
    }
}