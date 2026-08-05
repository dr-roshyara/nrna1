<?php

namespace Tests\Architecture\Election;

use App\Models\Election;
use App\Models\Organisation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Architectural Invariant Test
 *
 * Validates that the ElectionLifecycleEngine correctly computes state
 * based on business facts, not just the state column.
 *
 * Key Discovery: Election state is a computed invariant, not a simple database column.
 * The ElectionLifecycleEngine recomputes state from business facts like:
 * - voting_starts_at / voting_ends_at (determines if voting is active)
 * - administration_completed, nomination_completed (determines setup phase)
 * - approved_at (foundation for all subsequent states)
 *
 * This test MUST remain in the codebase to prevent future developers from
 * mistakenly treating election state as a mutable attribute.
 */
class ElectionLifecycleStateConsistencyTest extends TestCase
{
    use RefreshDatabase;

    public function test_lifecycle_engine_computes_counting_state_when_voting_window_has_ended(): void
    {
        $organisation = Organisation::factory()->create();
        $now = now();

        $election = Election::factory()
            ->for($organisation)
            ->create([
                'type' => 'real',
                'approved_at' => $now->clone()->subHours(6),
                'approved_by' => null,
                'administration_completed' => true,
                'administration_completed_at' => $now->clone()->subHours(6),
                'nomination_completed' => true,
                'nomination_completed_at' => $now->clone()->subHours(6),
                'voting_starts_at' => $now->clone()->subHours(5),
                'voting_ends_at' => $now->clone()->subHours(2), // Past = not voting active
                'voting_locked' => true,
                'voting_locked_at' => $now->clone()->subHours(2),
                'timezone' => 'UTC',
                'expected_voter_count' => 10,
            ]);

        // The engine is sovereign; the state column is a compatibility cache
        // (see ElectionLifecycleState docblock). Assert the engine's derivation.
        $election->refresh();
        $this->assertEquals('counting', $election->currentState()->value,
            'Lifecycle engine must compute counting state when voting window has ended');
    }

    public function test_lifecycle_engine_computes_voting_active_state_when_voting_window_is_open(): void
    {
        $organisation = Organisation::factory()->create();
        $now = now();

        $election = Election::factory()
            ->for($organisation)
            ->create([
                'type' => 'real',
                'approved_at' => $now->clone()->subHours(6),
                'approved_by' => null,
                'administration_completed' => true,
                'administration_completed_at' => $now->clone()->subHours(6),
                'nomination_completed' => true,
                'nomination_completed_at' => $now->clone()->subHours(6),
                'voting_starts_at' => $now->clone()->subHours(1), // Past = voting started
                'voting_ends_at' => $now->clone()->addHours(3), // Future = still voting
                'voting_locked' => true,
                'timezone' => 'UTC',
                'expected_voter_count' => 10,
            ]);

        // Lifecycle engine computes state from business facts, not column
        $election->refresh();
        $this->assertEquals('voting_active', $election->currentState()->value,
            'Lifecycle engine computes voting_active when voting window is currently open');
    }
}
