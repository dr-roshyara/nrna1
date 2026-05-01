<?php

namespace Tests\Feature\Voting;

use App\Models\Election;
use App\Models\User;
use App\Models\VoterSlug;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class VotingConcurrencyProtectionTest extends TestCase
{
    use RefreshDatabase;

    private User $voter;
    private Election $election;
    private Organisation $org;

    protected function setUp(): void
    {
        parent::setUp();

        $this->org = Organisation::factory()->create();
        $this->voter = User::factory()->create(['organisation_id' => $this->org->id]);
        $this->election = Election::factory()->inVotingState()->create([
            'organisation_id' => $this->org->id,
            'type' => 'real',
        ]);
    }

    /** @test */
    public function cache_lock_prevents_concurrent_step_progression(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step' => 3,
            'status' => 'active',
        ]);

        $lockKey = "voter_slug_transition:{$slug->id}";

        // Simulate first request acquiring the lock
        $lock1 = Cache::lock($lockKey, 10);
        $acquired1 = $lock1->get();

        $this->assertTrue($acquired1, 'First request should acquire the lock');

        // Simulate second request trying to acquire the same lock
        $lock2 = Cache::lock($lockKey, 10);
        $acquired2 = $lock2->get();

        $this->assertFalse($acquired2, 'Second request should be blocked by the lock');

        // Release first lock
        $lock1->release();

        // Second request should now acquire the lock
        $acquired3 = $lock2->get();
        $this->assertTrue($acquired3, 'Second request should acquire the lock after first releases');
        $lock2->release();
    }

    /** @test */
    public function concurrent_has_voted_update_only_succeeds_once(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step' => 4,
            'status' => 'active',
        ]);

        $lockKey = "voter_slug_transition:{$slug->id}";

        $updateCount = 0;

        // First call — should succeed
        $lock1 = Cache::lock($lockKey, 10);
        if ($lock1->get()) {
            try {
                if (!$slug->fresh()->hasVoted()) {
                    $slug->markAsVoted();
                    $updateCount++;
                }
            } finally {
                $lock1->release();
            }
        }

        // Second call — should be blocked by lock and status already voted
        $lock2 = Cache::lock($lockKey, 10);
        if ($lock2->get()) {
            try {
                if (!$slug->fresh()->hasVoted()) {
                    $slug->markAsVoted();
                    $updateCount++;
                }
            } finally {
                $lock2->release();
            }
        }

        $this->assertEquals(1, $updateCount, 'Only one update should succeed');
        $this->assertTrue($slug->fresh()->hasVoted(), 'Slug should be marked as voted');
    }

    /** @test */
    public function cache_lock_is_released_on_exception(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step' => 1,
            'status' => 'active',
        ]);

        $lockKey = "voter_slug_transition:{$slug->id}";
        $lock = Cache::lock($lockKey, 10);

        try {
            $lock->block(5, function () {
                throw new \RuntimeException('Simulated failure during transition');
            });
        } catch (\RuntimeException $e) {
            // Expected
        }

        // After exception, lock should be released
        $newLock = Cache::lock($lockKey, 10);
        $canAcquire = $newLock->get();
        $this->assertTrue($canAcquire, 'Lock should be released after exception');
        $newLock->release();
    }

    /** @test */
    public function voter_slug_status_cannot_transition_to_voted_twice(): void
    {
        $slug = VoterSlug::factory()->create([
            'user_id' => $this->voter->id,
            'election_id' => $this->election->id,
            'organisation_id' => $this->org->id,
            'current_step' => 4,
            'status' => 'active',
        ]);

        // Simulate two concurrent transitions
        $lockKey = "voter_slug_transition:{$slug->id}";

        $transitionCount = 0;

        // First transition
        $lock1 = Cache::lock($lockKey, 10);
        if ($lock1->get()) {
            try {
                $freshSlug = $slug->fresh();
                if ($freshSlug->status === 'active') {
                    $freshSlug->update(['status' => 'voted', 'current_step' => 5]);
                    $transitionCount++;
                }
            } finally {
                $lock1->release();
            }
        }

        // Second transition (should not update again)
        $lock2 = Cache::lock($lockKey, 10);
        if ($lock2->get()) {
            try {
                $freshSlug = $slug->fresh();
                if ($freshSlug->status === 'active') {
                    $freshSlug->update(['status' => 'voted', 'current_step' => 5]);
                    $transitionCount++;
                }
            } finally {
                $lock2->release();
            }
        }

        $this->assertEquals(1, $transitionCount, 'Only one status transition should occur');
        $this->assertEquals('voted', $slug->fresh()->status, 'Final status should be voted');
        $this->assertEquals(5, $slug->fresh()->current_step, 'Final step should be 5');
    }
}
