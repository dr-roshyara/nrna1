<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterSlugServiceTest extends TestCase
{
    use RefreshDatabase;

    protected VoterSlugService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new VoterSlugService();
    }

    /** @test */
    public function it_generates_slug_for_user()
    {
        $user = User::factory()->create();

        $slug = $this->service->generateSlugForUser($user);

        $this->assertInstanceOf(VoterSlug::class, $slug);
        $this->assertEquals($user->id, $slug->user_id);
        $this->assertTrue($slug->is_active);
        $this->assertNotEmpty($slug->slug);
        $this->assertTrue($slug->expires_at->isFuture());
    }

    /** @test */
    public function it_generates_unique_slugs()
    {
        $user1 = User::factory()->create();
        $user2 = User::factory()->create();

        $slug1 = $this->service->generateSlugForUser($user1);
        $slug2 = $this->service->generateSlugForUser($user2);

        $this->assertNotEquals($slug1->slug, $slug2->slug);
    }

    /** @test */
    public function it_revokes_previous_active_slugs_when_generating_new_one()
    {
        $user = User::factory()->create();

        $firstSlug = $this->service->generateSlugForUser($user);
        $secondSlug = $this->service->generateSlugForUser($user);

        $this->assertFalse($firstSlug->fresh()->is_active);
        $this->assertTrue($secondSlug->is_active);
    }

    /** @test */
    public function it_gets_active_slug_for_user()
    {
        $user = User::factory()->create();
        $generatedSlug = $this->service->generateSlugForUser($user);

        $activeSlug = $this->service->getActiveSlugForUser($user);

        $this->assertNotNull($activeSlug);
        $this->assertEquals($generatedSlug->id, $activeSlug->id);
    }

    /** @test */
    public function it_returns_null_when_no_active_slug_exists()
    {
        $user = User::factory()->create();

        $activeSlug = $this->service->getActiveSlugForUser($user);

        $this->assertNull($activeSlug);
    }

    /** @test */
    public function it_returns_null_when_only_expired_slugs_exist()
    {
        $user = User::factory()->create();

        VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'expired-slug',
            'expires_at' => now()->subHours(1),
            'is_active' => true
        ]);

        $activeSlug = $this->service->getActiveSlugForUser($user);

        $this->assertNull($activeSlug);
    }

    /** @test */
    public function it_can_revoke_specific_slug()
    {
        $user = User::factory()->create();
        $slug = $this->service->generateSlugForUser($user);

        $result = $this->service->revokeSlug($slug);

        $this->assertTrue($result);
        $this->assertFalse($slug->fresh()->is_active);
    }

    /** @test */
    public function it_can_revoke_all_slugs_for_user()
    {
        $user = User::factory()->create();

        // Create multiple active slugs manually (simulating edge case)
        $slug1 = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'slug-1',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true
        ]);

        $slug2 = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'slug-2',
            'expires_at' => now()->addMinutes(30),
            'is_active' => true
        ]);

        $revokedCount = $this->service->revokeAllSlugsForUser($user);

        $this->assertEquals(2, $revokedCount);
        $this->assertFalse($slug1->fresh()->is_active);
        $this->assertFalse($slug2->fresh()->is_active);
    }

    /** @test */
    public function it_can_extend_slug_expiry()
    {
        $user = User::factory()->create();
        $slug = $this->service->generateSlugForUser($user);
        $originalExpiry = $slug->expires_at;

        // Wait a moment to ensure timestamp difference
        sleep(1);

        $result = $this->service->extendSlugExpiry($slug);

        $this->assertTrue((bool) $result); // Cast to bool since update() can return int
        $this->assertTrue($slug->fresh()->expires_at->gt($originalExpiry));
    }

    /** @test */
    public function it_cannot_extend_inactive_slug_expiry()
    {
        $user = User::factory()->create();
        $slug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'inactive-slug',
            'expires_at' => now()->addMinutes(30),
            'is_active' => false
        ]);

        $result = $this->service->extendSlugExpiry($slug);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_can_cleanup_expired_slugs()
    {
        $user = User::factory()->create();

        // Create expired slug
        $expiredSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'expired-slug',
            'expires_at' => now()->subHours(1),
            'is_active' => true
        ]);

        // Create active slug
        $activeSlug = VoterSlug::create([
            'user_id' => $user->id,
            'slug' => 'active-slug',
            'expires_at' => now()->addHours(1),
            'is_active' => true
        ]);

        $cleanedCount = $this->service->cleanupExpiredSlugs();

        $this->assertEquals(1, $cleanedCount);
        $this->assertDatabaseMissing('voter_slugs', ['id' => $expiredSlug->id]);
        $this->assertDatabaseHas('voter_slugs', ['id' => $activeSlug->id]);
    }

    /** @test */
    public function it_builds_voting_link()
    {
        $user = User::factory()->create();
        $slug = $this->service->generateSlugForUser($user);

        $link = $this->service->buildVotingLink($slug, 'test.voter.page');

        $this->assertStringContainsString("/v/{$slug->slug}", $link);
    }
}
