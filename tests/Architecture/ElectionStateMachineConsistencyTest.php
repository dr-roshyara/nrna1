<?php

namespace Tests\Architecture;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use App\Services\ElectionClockService;
use Tests\TestCase;

/**
 * Phase 3.1.F: State Machine Consistency Test
 *
 * This test enforces that all actions referenced in code are defined in Constitution.
 *
 * RED TEST: Catches actions that don't have constitutional rules.
 */
class ElectionStateMachineConsistencyTest extends TestCase
{
    /**
     * RED: All actions in Constitution must be well-formed
     * GREEN: Verify pause_voting was removed (no longer an orphaned action)
     */
    public function test_voting_active_pause_voting_has_been_removed(): void
    {
        $constitutionActions = array_keys(ElectionConstitution::RULES);

        // pause_voting should NOT be in Constitution (feature was removed as unimplemented)
        // If this test fails, pause_voting was re-added to Constitution
        // The corresponding Engine code should also return pause_voting for consistency
        $this->assertNotContains(
            'pause_voting',
            $constitutionActions,
            "pause_voting action should not be in Constitution unless fully implemented. "
            . "Feature was removed due to lack of implementation (no handlers, controllers, guards)."
        );
    }

    /**
     * Verify Constitution has at least the core actions
     */
    public function test_constitution_defines_core_actions(): void
    {
        $constitutionActions = array_keys(ElectionConstitution::RULES);

        $coreActions = [
            'submit_for_approval',
            'approve',
            'reject',
            'auto_submit',
            'begin_setup',
            'open_voting',
            'close_voting',
            'publish_results',
        ];

        foreach ($coreActions as $action) {
            $this->assertContains(
                $action,
                $constitutionActions,
                "Missing core action '{$action}' in ElectionConstitution::RULES"
            );
        }
    }

    /**
     * Verify all Constitution actions have required fields
     */
    public function test_all_constitution_actions_have_required_fields(): void
    {
        $rules = ElectionConstitution::RULES;
        $requiredFields = ['allowed_states', 'allowed_roles', 'preconditions', 'description'];

        foreach ($rules as $action => $rule) {
            foreach ($requiredFields as $field) {
                $this->assertArrayHasKey(
                    $field,
                    $rule,
                    "Action '{$action}' in Constitution missing required field '{$field}'"
                );
            }

            // allowed_states and allowed_roles must be non-empty arrays
            $this->assertIsArray(
                $rule['allowed_states'],
                "Action '{$action}' allowed_states must be array"
            );
            $this->assertNotEmpty(
                $rule['allowed_states'],
                "Action '{$action}' allowed_states cannot be empty"
            );
            $this->assertIsArray(
                $rule['allowed_roles'],
                "Action '{$action}' allowed_roles must be array"
            );
            $this->assertNotEmpty(
                $rule['allowed_roles'],
                "Action '{$action}' allowed_roles cannot be empty"
            );
        }
    }

    /**
     * RED: Engine must use ElectionClockService for voting window checks
     *
     * Raw Carbon::now() bypasses timezone-aware logic.
     * Engine should delegate to ElectionClockService::isVotingOpen()
     */
    public function test_engine_uses_election_clock_service_for_voting_window(): void
    {
        $org = \App\Models\Organisation::firstOrCreate(
            ['name' => 'Clock Test Org'],
            ['slug' => 'clock-test-org', 'id' => 9998]
        );

        // Election with voting window OPEN right now
        $votingOpenElection = Election::factory()
            ->forOrganisation($org)
            ->create([
                'voting_starts_at' => now()->subHour(),
                'voting_ends_at' => now()->addHour(),
                'administration_completed' => true,
                'nomination_completed' => true,
            ]);

        // Verify clock service says voting is open
        $this->assertTrue(
            ElectionClockService::isVotingOpen($votingOpenElection),
            "Clock service should confirm voting is open"
        );

        // Engine should derive VotingActive state
        $engine = new ElectionLifecycleEngineImpl();
        $reflection = new \ReflectionMethod($engine, 'getState');
        $reflection->setAccessible(true);
        $state = $reflection->invoke($engine, $votingOpenElection);

        $this->assertEquals(
            ElectionLifecycleState::VotingActive,
            $state,
            "Engine should use ElectionClockService logic and derive VotingActive state"
        );
    }

    /**
     * RED: Engine must return all actions allowed in each state per Constitution
     *
     * If Constitution allows 'open_voting' in Setup state,
     * Engine::getAllowedActions(Setup) must return it.
     */
    public function test_engine_returns_all_constitution_allowed_actions(): void
    {
        // Constitution allows 'open_voting' in ['setup', 'ready_for_voting']
        $constitutionRules = ElectionConstitution::getRulesForAction('open_voting');
        $this->assertContains('setup', $constitutionRules['allowed_states'],
            "Setup missing from Constitution open_voting allowed_states");

        // Engine should return 'open_voting' for Setup state
        $engine = new ElectionLifecycleEngineImpl();
        $reflection = new \ReflectionMethod($engine, 'getAllowedActions');
        $reflection->setAccessible(true);

        $org = \App\Models\Organisation::firstOrCreate(
            ['name' => 'Test Org'],
            ['slug' => 'test-org', 'id' => 9999]
        );

        $setupElection = Election::factory()
            ->forOrganisation($org)
            ->create(['administration_completed' => true]);

        $engineActions = $reflection->invoke(
            $engine,
            ElectionLifecycleState::Setup,
            $setupElection
        );

        $this->assertContains(
            'open_voting',
            $engineActions,
            "MISSING: Engine should return 'open_voting' for Setup state. "
            . "Constitution allows it. Current actions: " . json_encode($engineActions)
        );
    }
}
