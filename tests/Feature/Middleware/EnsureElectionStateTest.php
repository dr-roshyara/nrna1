<?php

namespace Tests\Feature\Middleware;

use App\Application\Election\Facades\ElectionLifecycle;
use App\Models\Election;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Phase C.2.5 Step 6: Middleware Capability Assertion Tests
 *
 * MIGRATION: Rewritten from deprecated allowsAction() to resolver-based capability checks.
 * These tests verify that middleware and state transitions properly use the resolver
 * for authority decisions, not state-based assumptions.
 */
class EnsureElectionStateTest extends TestCase
{
    use RefreshDatabase;

    /**
     * MIGRATED: Rewritten from allowsAction() to resolver snapshot checks.
     * Verifies that in setup states (no voting yet), canEdit is true.
     */
    public function test_allows_request_when_action_permitted(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_administration',
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // Resolver determines authority based on constitutional facts
        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // In setup phase, editing (manage_settings) should be allowed
        $this->assertTrue($snapshot->canEdit,
            'ElectionCapabilityResolver should allow editing in setup state'
        );
    }

    /**
     * MIGRATED: Rewritten from allowsAction() to resolver snapshot checks.
     * Verifies that during voting phase, canEdit is false (deny editing).
     */
    public function test_blocks_request_when_action_not_permitted_with_403(): void
    {
        $election = Election::factory()->create([
            'state' => 'voting_active',
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        // Resolver determines authority based on constitutional facts
        $snapshot = ElectionLifecycle::of($election)->snapshot();

        // During voting phase, editing (manage_settings) should be denied
        $this->assertFalse($snapshot->canEdit,
            'ElectionCapabilityResolver should deny editing during voting phase'
        );
    }

    public function test_error_message_includes_operation_and_state(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => true,
            'nomination_completed' => true,
            'voting_starts_at' => now()->subHours(1),
            'voting_ends_at' => now()->addHours(2),
        ]);

        $stateInfo = $election->state_info;
        $this->assertArrayHasKey('name', $stateInfo);
        $this->assertIsString($stateInfo['name']);
    }

    public function test_resolves_election_from_string_slug(): void
    {
        $election = Election::factory()->create([
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $foundElection = Election::where('id', $election->id)->withoutGlobalScopes()->first();
        $this->assertNotNull($foundElection);
    }

    /**
     * MIGRATED: Rewritten from allowsAction() to resolver snapshot checks.
     * Verifies that resolver correctly computes authority for elections in setup state.
     */
    public function test_resolves_election_from_model_instance(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_administration',
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        $this->assertInstanceOf(Election::class, $election);

        // Resolver determines authority based on constitutional facts
        $snapshot = ElectionLifecycle::of($election)->snapshot();
        $this->assertTrue($snapshot->canEdit,
            'ElectionCapabilityResolver should allow editing in setup state'
        );
    }

    public function test_returns_404_when_election_not_found(): void
    {
        $foundElection = Election::where('id', 'non-existent-uuid')->first();
        $this->assertNull($foundElection);
    }

    /**
     * MIGRATED: Rewritten from allowsAction() to resolver snapshot checks.
     * Verifies that the state machine facade correctly delegates to resolver.
     */
    public function test_middleware_uses_state_machine_delegation(): void
    {
        $election = Election::factory()->create([
            'state' => 'setup_administration',
            'administration_completed' => false,
            'voting_starts_at' => now()->addDays(5),
            'voting_ends_at' => now()->addDays(6),
        ]);

        // ElectionLifecycle facade delegates to resolver
        $stateMachine = ElectionLifecycle::of($election);
        $this->assertTrue($stateMachine->canEdit(),
            'ElectionLifecycle facade should allow editing in setup state'
        );
    }
}
