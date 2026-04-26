<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\StateMachine\TransitionMatrix;
use Tests\TestCase;

class TransitionMatrixTest extends TestCase
{
    /** @test */
    public function draft_can_transition_to_administration(): void
    {
        $this->assertTrue(TransitionMatrix::canTransition('draft', 'administration'));
        $this->assertFalse(TransitionMatrix::canTransition('draft', 'nomination'));
        $this->assertFalse(TransitionMatrix::canTransition('draft', 'voting'));
    }

    /** @test */
    public function administration_can_transition_to_nomination(): void
    {
        $this->assertTrue(TransitionMatrix::canTransition('administration', 'nomination'));
        $this->assertFalse(TransitionMatrix::canTransition('administration', 'draft'));
        $this->assertFalse(TransitionMatrix::canTransition('administration', 'voting'));
    }

    /** @test */
    public function nomination_can_transition_to_voting(): void
    {
        $this->assertTrue(TransitionMatrix::canTransition('nomination', 'voting'));
        $this->assertFalse(TransitionMatrix::canTransition('nomination', 'administration'));
        $this->assertFalse(TransitionMatrix::canTransition('nomination', 'results_pending'));
    }

    /** @test */
    public function voting_can_transition_to_results_pending(): void
    {
        $this->assertTrue(TransitionMatrix::canTransition('voting', 'results_pending'));
        $this->assertFalse(TransitionMatrix::canTransition('voting', 'nomination'));
        $this->assertFalse(TransitionMatrix::canTransition('voting', 'results'));
    }

    /** @test */
    public function results_pending_can_transition_to_results(): void
    {
        $this->assertTrue(TransitionMatrix::canTransition('results_pending', 'results'));
        $this->assertFalse(TransitionMatrix::canTransition('results_pending', 'voting'));
    }

    /** @test */
    public function results_state_is_terminal(): void
    {
        $this->assertFalse(TransitionMatrix::canTransition('results', 'results_pending'));
        $this->assertFalse(TransitionMatrix::canTransition('results', 'voting'));
        $this->assertEquals([], TransitionMatrix::getAllowedTransitions('results'));
    }

    /** @test */
    public function unknown_state_has_no_transitions(): void
    {
        $this->assertFalse(TransitionMatrix::canTransition('unknown', 'administration'));
        $this->assertEquals([], TransitionMatrix::getAllowedTransitions('unknown'));
    }

    /** @test */
    public function all_defined_states_are_valid(): void
    {
        $validStates = ['draft', 'administration', 'nomination', 'voting', 'results_pending', 'results'];

        foreach ($validStates as $state) {
            $this->assertTrue(
                TransitionMatrix::isValidState($state),
                "State '{$state}' should be valid"
            );
        }
    }

    /** @test */
    public function unknown_state_is_invalid(): void
    {
        $this->assertFalse(TransitionMatrix::isValidState('unknown'));
        $this->assertFalse(TransitionMatrix::isValidState('archived'));
    }

    /** @test */
    public function get_allowed_transitions_returns_correct_states(): void
    {
        $draftTransitions = TransitionMatrix::getAllowedTransitions('draft');
        $this->assertContains('administration', $draftTransitions);

        $adminTransitions = TransitionMatrix::getAllowedTransitions('administration');
        $this->assertContains('nomination', $adminTransitions);

        $nominationTransitions = TransitionMatrix::getAllowedTransitions('nomination');
        $this->assertContains('voting', $nominationTransitions);
    }

    /** @test */
    public function transition_matrix_defines_all_states(): void
    {
        $states = TransitionMatrix::getAllStates();

        $this->assertContains('draft', $states);
        $this->assertContains('administration', $states);
        $this->assertContains('nomination', $states);
        $this->assertContains('voting', $states);
        $this->assertContains('results_pending', $states);
        $this->assertContains('results', $states);
        $this->assertCount(6, $states);
    }

    /** @test */
    public function each_state_has_zero_or_more_transitions(): void
    {
        foreach (TransitionMatrix::getAllStates() as $state) {
            $transitions = TransitionMatrix::getAllowedTransitions($state);
            $this->assertIsArray($transitions);
        }
    }

    /** @test */
    public function all_transitions_lead_to_valid_states(): void
    {
        foreach (TransitionMatrix::getAllStates() as $fromState) {
            foreach (TransitionMatrix::getAllowedTransitions($fromState) as $toState) {
                $this->assertTrue(
                    TransitionMatrix::isValidState($toState),
                    "Transition from '{$fromState}' to '{$toState}' leads to invalid state"
                );
            }
        }
    }
}
