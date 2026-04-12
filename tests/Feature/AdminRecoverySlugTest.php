<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminRecoverySlugTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function committee_member_can_generate_recovery_slug_for_voter()
    {
        // Skip middleware for testing
        $this->withoutMiddleware();

        // Create a committee member
        $admin = User::factory()->create([
            'name' => 'Election Committee Member',
            'email' => 'committee@example.com',
        ]);

        // Create a voter with an expired slug
        $voter = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => false,
            'name' => 'Test Voter',
        ]);

        // Create an expired slug
        $expiredSlug = VoterSlug::create([
            'user_id' => $voter->id,
            'slug' => 'expired-test-slug',
            'expires_at' => now()->subMinutes(10), // Expired 10 minutes ago
            'is_active' => true,
            'current_step' => 2, // Was in the middle of voting
        ]);

        // Admin generates recovery slug
        $response = $this->actingAs($admin)
            ->post("/admin/voting-security/recovery/{$voter->id}", [
                'reason' => 'Voter slug expired, requested assistance'
            ]);

        $response->assertStatus(302);

        // Debug: Check what session data we have
        if (!$response->assertSessionHas('success', false)) {
            // Check if there's an error instead
            if ($response->getSession()->has('error')) {
                $this->fail('Got error instead of success: ' . $response->getSession()->get('error'));
            } else {
                $this->fail('No session data found. Session keys: ' . implode(', ', array_keys($response->getSession()->all())));
            }
        }

        $response->assertSessionHas('recovery_url');

        // Should have created a new active slug
        $newSlug = VoterSlug::where('user_id', $voter->id)
            ->where('is_active', true)
            ->first();

        $this->assertNotNull($newSlug);
        $this->assertNotEquals($expiredSlug->slug, $newSlug->slug);
        $this->assertTrue($newSlug->expires_at > now());

        // Should have recovery metadata
        $this->assertTrue($newSlug->step_meta['recovery_slug'] ?? false);
        $this->assertTrue($newSlug->step_meta['admin_generated'] ?? false);
        $this->assertEquals('Election Committee Member', $newSlug->step_meta['admin_name'] ?? '');

        // Old slug should be deactivated
        $expiredSlug->refresh();
        $this->assertFalse($expiredSlug->is_active);
    }

    /** @test */
    public function cannot_generate_recovery_slug_for_completed_voter()
    {
        $this->withoutMiddleware();

        $admin = User::factory()->create();
        $voter = User::factory()->create([
            'is_voter' => true,
            'can_vote' => true,
            'has_voted' => true, // Already completed voting
        ]);

        $response = $this->actingAs($admin)
            ->post("/admin/voting-security/recovery/{$voter->id}", [
                'reason' => 'Test recovery attempt'
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContains('already completed voting', session('error'));

        // Should not create any new slugs
        $this->assertEquals(0, VoterSlug::where('user_id', $voter->id)->count());
    }

    /** @test */
    public function cannot_generate_recovery_slug_for_ineligible_voter()
    {
        $this->withoutMiddleware();

        $admin = User::factory()->create();
        $voter = User::factory()->create([
            'is_voter' => false, // Not a registered voter
            'can_vote' => false,
            'has_voted' => false,
        ]);

        $response = $this->actingAs($admin)
            ->post("/admin/voting-security/recovery/{$voter->id}", [
                'reason' => 'Test recovery attempt'
            ]);

        $response->assertStatus(302);
        $response->assertSessionHas('error');
        $this->assertStringContains('not eligible to vote', session('error'));

        // Should not create any new slugs
        $this->assertEquals(0, VoterSlug::where('user_id', $voter->id)->count());
    }
}