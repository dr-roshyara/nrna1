<?php

namespace Tests\Architecture;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Enum\ElectionAction;
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
     * RED: lock_voting must NOT be in Constitution — fully subsumed by open_voting.
     *
     * During Phase C constitutional migration, lock_voting was an orphaned action
     * that was never defined in ElectionConstitution::RULES. All locking semantics
     * are side effects of open_voting. This test prevents re-introduction.
     */
    public function test_lock_voting_is_not_a_constitutional_action(): void
    {
        $constitutionActions = array_keys(ElectionConstitution::RULES);

        $this->assertNotContains(
            'lock_voting',
            $constitutionActions,
            "lock_voting must not be in Constitution. It was fully subsumed by open_voting "
            . "which sets voting_locked=true, voting_locked_at, voting_locked_by as side effects. "
            . "The separate action was an orphaned legacy artifact from pre-constitutional state machine."
        );
    }

    /**
     * RED: ElectionAction enum must not contain LockVoting case.
     */
    public function test_lock_voting_enum_case_is_removed(): void
    {
        $cases = array_map(fn($case) => $case->value, ElectionAction::cases());

        $this->assertNotContains(
            'lock_voting',
            $cases,
            "ElectionAction::LockVoting was removed. The lock_voting action is not a "
            . "constitutional action. All locking is a side effect of open_voting."
        );
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
        // Constitution allows 'open_voting' in ['setup_nomination', 'ready_for_voting']
        $constitutionRules = ElectionConstitution::getRulesForAction('open_voting');
        $this->assertContains('setup_nomination', $constitutionRules['allowed_states'],
            "setup_nomination missing from Constitution open_voting allowed_states");

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
            ElectionLifecycleState::SetupNomination,
            $setupElection
        );

        $this->assertContains(
            'open_voting',
            $engineActions,
            "MISSING: Engine should return 'open_voting' for Setup state. "
            . "Constitution allows it. Current actions: " . json_encode($engineActions)
        );
    }

    // ============================================================
    // Phase B: Suspend/Resume Constitutional Vocabulary Integrity
    // ============================================================

    /**
     * RED: suspend must be defined in ElectionConstitution::RULES.
     * This is a vocabulary invariant: all governance intervention actions
     * must be defined in the Constitution. Role/state semantics are tested
     * in policy and constitutional behavior tests, not here.
     */
    public function test_suspend_action_is_defined_in_constitution(): void
    {
        $this->assertArrayHasKey(
            'suspend',
            ElectionConstitution::RULES,
            'suspend must be defined in ElectionConstitution::RULES'
        );
    }

    /**
     * RED: resume must be defined in ElectionConstitution::RULES.
     * This is a vocabulary invariant only — transition semantics are
     * tested in constitutional behavior tests.
     */
    public function test_resume_action_is_defined_in_constitution(): void
    {
        $this->assertArrayHasKey(
            'resume',
            ElectionConstitution::RULES,
            'resume must be defined in ElectionConstitution::RULES'
        );
    }

    /**
     * RED: ElectionAction::Suspend must exist.
     * Every action in ElectionConstitution::RULES needs a corresponding enum case.
     */
    public function test_suspend_enum_case_exists(): void
    {
        $cases = array_map(fn($case) => $case->value, ElectionAction::cases());

        $this->assertContains(
            'suspend',
            $cases,
            'ElectionAction must have a Suspend case. '
            . 'Current cases: ' . json_encode($cases)
        );
    }

    /**
     * RED: ElectionAction::Resume must exist.
     */
    public function test_resume_enum_case_exists(): void
    {
        $cases = array_map(fn($case) => $case->value, ElectionAction::cases());

        $this->assertContains(
            'resume',
            $cases,
            'ElectionAction must have a Resume case. '
            . 'Current cases: ' . json_encode($cases)
        );
    }

    /**
     * RED: Frontend ElectionActions.ts must contain all PHP ElectionAction enum values.
     *
     * This prevents vocabulary drift between backend and frontend.
     * Every action in the PHP enum must have a matching entry in the frontend constants.
     */
    public function test_frontend_contains_all_php_enum_actions(): void
    {
        $phpValues = array_map(fn($case) => $case->value, ElectionAction::cases());

        // Read the frontend ElectionActions.ts file
        $tsPath = __DIR__ . '/../../resources/js/Constants/ElectionActions.ts';
        $this->assertFileExists($tsPath, 'ElectionActions.ts must exist');

        $tsContent = file_get_contents($tsPath);

        // For each PHP enum value, verify the frontend has it
        foreach ($phpValues as $value) {
            // Frontend uses format: SUSPEND: 'suspend'
            // Search for the value pattern in the TS file
            $this->assertStringContainsString(
                "'{$value}'",
                $tsContent,
                "Frontend ElectionActions.ts must contain action '{$value}' "
                . "which exists in PHP ElectionAction enum"
            );
        }

        // Specifically verify suspend and resume exist in frontend
        $this->assertStringContainsString(
            "'suspend'",
            $tsContent,
            'Frontend ElectionActions.ts must contain SUSPEND action'
        );
        $this->assertStringContainsString(
            "'resume'",
            $tsContent,
            'Frontend ElectionActions.ts must contain RESUME action'
        );
    }
}
