<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\VoterSlug;
use App\Services\VoterSlugService;
use App\Services\VoterRecoveryService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterRecoveryTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function election_committee_can_allow_voter_to_revote_after_problems()
    {
        // 1. INITIAL SITUATION: Voter had problems during voting
        $voter = User::factory()->create(['is_voter' => true, 'can_vote' => true, 'has_voted' => false]);

        // Create an expired slug that shows they were in the middle of voting
        $expiredSlug = VoterSlug::create([
            'user_id' => $voter->id,
            'slug' => 'expired-problematic-slug',
            'expires_at' => now()->subMinutes(60), // Expired 1 hour ago
            'is_active' => false, // Deactivated due to problems
            'current_step' => 3, // Was at vote creation step
            'step_meta' => [
                'problem' => 'network_timeout',
                'error_time' => now()->subMinutes(60)->toISOString(),
                'progress' => 'partial_vote_data_lost'
            ]
        ]);

        // 2. ELECTION COMMITTEE DECISION: Allow re-voting
        $recoveryService = new VoterRecoveryService(new VoterSlugService());
        $adminDetails = [
            'admin_name' => 'Election Officer Smith',
            'admin_id' => 'EO001',
            'decision_time' => now()->toISOString()
        ];

        $newSlug = $recoveryService->allowRevote(
            $voter,
            'Network issues during step 3 - committee approved re-vote',
            $adminDetails
        );

        // 3. VERIFY RECOVERY PROCESS WORKED

        // New slug should be valid and active
        $this->assertTrue($newSlug->isValid());
        $this->assertTrue($newSlug->is_active);
        $this->assertEquals(1, $newSlug->current_step); // Fresh start
        $this->assertGreaterThan(now()->addMinutes(25), $newSlug->expires_at); // 30 min expiry

        // Recovery metadata should be recorded
        $this->assertEquals('Network issues during step 3 - committee approved re-vote',
                          $newSlug->step_meta['recovery_reason']);
        $this->assertEquals('Election Officer Smith',
                          $newSlug->step_meta['admin_approved_by']);
        $this->assertTrue($newSlug->step_meta['is_recovery_slug']);

        // Old slug should still exist for audit trail but be inactive
        $expiredSlug->refresh();
        $this->assertFalse($expiredSlug->is_active);
        $this->assertTrue($expiredSlug->isExpired());

        // User should have 2 slugs total: old expired + new active
        $this->assertEquals(2, VoterSlug::where('user_id', $voter->id)->count());

        // Only 1 should be active
        $this->assertEquals(1, VoterSlug::where('user_id', $voter->id)->where('is_active', true)->count());
    }

    /** @test */
    public function recovery_service_maintains_full_audit_trail()
    {
        $voter = User::factory()->create(['is_voter' => true, 'can_vote' => true]);

        // Simulate multiple voting attempts with problems
        $attempt1 = VoterSlug::create([
            'user_id' => $voter->id,
            'slug' => 'first-attempt-failed',
            'expires_at' => now()->subHours(2),
            'is_active' => false,
            'current_step' => 2,
            'step_meta' => ['problem' => 'browser_crash']
        ]);

        $attempt2 = VoterSlug::create([
            'user_id' => $voter->id,
            'slug' => 'second-attempt-network-issue',
            'expires_at' => now()->subMinutes(90),
            'is_active' => false,
            'current_step' => 3,
            'step_meta' => ['problem' => 'network_timeout']
        ]);

        // Committee allows third attempt
        $recoveryService = new VoterRecoveryService(new VoterSlugService());
        $newSlug = $recoveryService->allowRevote($voter, 'Third attempt approved - persistent technical issues');

        // Get full recovery history
        $history = $recoveryService->getRecoveryHistory($voter);

        // Should have all 3 attempts in chronological order (newest first)
        $this->assertCount(3, $history);

        // Newest (recovery) slug first
        $this->assertTrue($history[0]['is_recovery']);
        $this->assertEquals('Third attempt approved - persistent technical issues', $history[0]['recovery_reason']);

        // Previous attempts recorded
        $this->assertEquals('second-attempt-network-issue', $history[1]['slug']);
        $this->assertEquals('first-attempt-failed', $history[2]['slug']);

        // All historical data preserved
        $this->assertEquals('browser_crash', $history[2]['step_meta']['problem']);
        $this->assertEquals('network_timeout', $history[1]['step_meta']['problem']);
    }

    /** @test */
    public function recovery_service_prevents_abuse_with_rate_limiting()
    {
        $voter = User::factory()->create(['is_voter' => true, 'can_vote' => true]);
        $recoveryService = new VoterRecoveryService(new VoterSlugService());

        // Create 3 recent recovery attempts (within 2 hours)
        for ($i = 0; $i < 3; $i++) {
            VoterSlug::create([
                'user_id' => $voter->id,
                'slug' => "recovery-attempt-{$i}",
                'expires_at' => now()->addMinutes(30),
                'is_active' => false,
                'current_step' => 1,
                'step_meta' => ['is_recovery_slug' => true, 'recovery_reason' => "Attempt {$i}"]
            ]);
        }

        // 4th recovery attempt should be blocked
        $this->assertFalse($recoveryService->canUserRecover($voter));
    }

    /** @test */
    public function committee_can_identify_users_needing_recovery()
    {
        // Create various voter scenarios
        $voterNeedsRecovery = User::factory()->create(['is_voter' => true, 'can_vote' => true, 'has_voted' => false]);
        $voterCompletedVoting = User::factory()->create(['is_voter' => true, 'can_vote' => true, 'has_voted' => true]);
        $voterNeverStarted = User::factory()->create(['is_voter' => true, 'can_vote' => true, 'has_voted' => false]);

        // Voter who needs recovery: started voting but slug expired
        VoterSlug::create([
            'user_id' => $voterNeedsRecovery->id,
            'slug' => 'needs-recovery-slug',
            'expires_at' => now()->subMinutes(30), // Expired
            'is_active' => false,
            'current_step' => 3, // Was in middle of voting
            'step_meta' => ['problem' => 'session_expired']
        ]);

        // Voter who completed voting successfully
        VoterSlug::create([
            'user_id' => $voterCompletedVoting->id,
            'slug' => 'completed-successfully',
            'expires_at' => now()->subMinutes(10),
            'is_active' => false,
            'current_step' => 5, // Finished
        ]);

        // Voter who never started (no slugs)

        $recoveryService = new VoterRecoveryService(new VoterSlugService());
        $usersNeedingRecovery = $recoveryService->getUsersNeedingRecovery();

        // Should only identify the voter who needs recovery
        $this->assertCount(1, $usersNeedingRecovery);
        $this->assertEquals($voterNeedsRecovery->id, $usersNeedingRecovery[0]['user_id']);
        $this->assertEquals(3, $usersNeedingRecovery[0]['was_at_step']);
        $this->assertTrue($usersNeedingRecovery[0]['needs_recovery']);
    }
}