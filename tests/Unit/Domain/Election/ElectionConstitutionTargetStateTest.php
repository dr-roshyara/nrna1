<?php

namespace Tests\Unit\Domain\Election;

use App\Application\Election\Services\ElectionLifecycleEngineImpl;
use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Models\Election;
use PHPUnit\Framework\TestCase;
use Tests\TestCase as BaseTestCase;

class ElectionConstitutionTargetStateTest extends BaseTestCase
{
    /**
     * Test 1: All 12 actions have target_state defined
     */
    public function test_all_actions_have_target_state(): void
    {
        foreach (ElectionConstitution::RULES as $action => $rules) {
            $this->assertArrayHasKey('target_state', $rules,
                "Constitution action '$action' missing target_state"
            );
        }
    }

    /**
     * Test 2: Specific target states are correct
     */
    public function test_target_states_are_correct(): void
    {
        $this->assertEquals('setup', ElectionConstitution::getTargetStateForAction('begin_setup'));
        $this->assertEquals('submitted_for_approval', ElectionConstitution::getTargetStateForAction('submit_for_approval'));
        $this->assertEquals('approved', ElectionConstitution::getTargetStateForAction('approve'));
        $this->assertEquals('rejected', ElectionConstitution::getTargetStateForAction('reject'));
        $this->assertEquals('voting_active', ElectionConstitution::getTargetStateForAction('open_voting'));
        $this->assertEquals('counting', ElectionConstitution::getTargetStateForAction('close_voting'));
        $this->assertEquals('results_published', ElectionConstitution::getTargetStateForAction('publish_results'));
    }

    /**
     * Test 3: All target states are valid ElectionLifecycleState values
     */
    public function test_all_target_states_are_valid_enum_values(): void
    {
        $validValues = array_column(ElectionLifecycleState::cases(), 'value');
        foreach (ElectionConstitution::RULES as $action => $rules) {
            $this->assertContains($rules['target_state'], $validValues,
                "Action '$action' has invalid target_state '{$rules['target_state']}'"
            );
        }
    }

    /**
     * Test 4: target_state matches what engine would derive AFTER correct side effects run
     */
    public function test_target_state_is_what_engine_derives_after_transition(): void
    {
        $engine = app(ElectionLifecycleEngineImpl::class);

        // begin_setup sets administration_completed = true
        $election = Election::factory()->create([
            'approved_at' => now()->subDay(),
            'administration_completed' => true,   // what begin_setup will set
            'state' => 'setup',
        ]);

        $this->assertEquals(
            ElectionLifecycleState::from(ElectionConstitution::getTargetStateForAction('begin_setup')),
            $engine->getState($election)
        );
    }
}
