<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\StateMachine\TransitionMatrix;
use Tests\TestCase;

class TransitionMatrixTest extends TestCase
{
    // ── Action-based tests ────────────────────────────────────────────────────

    /** @test */
    public function can_perform_submit_for_approval_from_draft(): void
    {
        $this->assertTrue(TransitionMatrix::canPerformAction('draft', 'submit_for_approval'));
        $this->assertFalse(TransitionMatrix::canPerformAction('draft', 'approve'));
        $this->assertFalse(TransitionMatrix::canPerformAction('draft', 'reject'));
    }

    /** @test */
    public function cannot_perform_approve_from_draft(): void
    {
        $this->assertFalse(TransitionMatrix::canPerformAction('draft', 'approve'));
    }

    /** @test */
    public function can_perform_approve_from_pending_approval(): void
    {
        $this->assertTrue(TransitionMatrix::canPerformAction('pending_approval', 'approve'));
        $this->assertTrue(TransitionMatrix::canPerformAction('pending_approval', 'reject'));
        $this->assertFalse(TransitionMatrix::canPerformAction('pending_approval', 'submit_for_approval'));
    }

    /** @test */
    public function can_perform_reject_from_pending_approval(): void
    {
        $this->assertTrue(TransitionMatrix::canPerformAction('pending_approval', 'reject'));
    }

    /** @test */
    public function open_voting_is_only_allowed_from_nomination(): void
    {
        $this->assertTrue(TransitionMatrix::canPerformAction('nomination', 'open_voting'));
        $this->assertFalse(TransitionMatrix::canPerformAction('administration', 'open_voting'));
        $this->assertFalse(TransitionMatrix::canPerformAction('voting', 'open_voting'));
    }

    /** @test */
    public function close_voting_is_only_allowed_from_voting(): void
    {
        $this->assertTrue(TransitionMatrix::canPerformAction('voting', 'close_voting'));
        $this->assertFalse(TransitionMatrix::canPerformAction('nomination', 'close_voting'));
        $this->assertFalse(TransitionMatrix::canPerformAction('results_pending', 'close_voting'));
    }

    // ── Resulting state tests ─────────────────────────────────────────────────

    /** @test */
    public function get_resulting_state_returns_pending_approval_for_submit_for_approval(): void
    {
        $this->assertEquals('pending_approval', TransitionMatrix::getResultingState('submit_for_approval'));
    }

    /** @test */
    public function get_resulting_state_returns_administration_for_approve(): void
    {
        $this->assertEquals('administration', TransitionMatrix::getResultingState('approve'));
    }

    /** @test */
    public function get_resulting_state_returns_draft_for_reject(): void
    {
        $this->assertEquals('draft', TransitionMatrix::getResultingState('reject'));
    }

    /** @test */
    public function get_resulting_state_returns_voting_for_open_voting(): void
    {
        $this->assertEquals('voting', TransitionMatrix::getResultingState('open_voting'));
    }

    /** @test */
    public function get_resulting_state_returns_results_pending_for_close_voting(): void
    {
        $this->assertEquals('results_pending', TransitionMatrix::getResultingState('close_voting'));
    }

    /** @test */
    public function get_resulting_state_throws_on_unknown_action(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        TransitionMatrix::getResultingState('unknown_action');
    }

    // ── State and action validation tests ──────────────────────────────────────

    /** @test */
    public function all_defined_states_are_valid(): void
    {
        $validStates = ['draft', 'pending_approval', 'administration', 'nomination', 'voting', 'results_pending', 'results'];

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
    public function transition_matrix_defines_all_states(): void
    {
        $states = TransitionMatrix::getAllStates();

        $this->assertContains('draft', $states);
        $this->assertContains('pending_approval', $states);
        $this->assertContains('administration', $states);
        $this->assertContains('nomination', $states);
        $this->assertContains('voting', $states);
        $this->assertContains('results_pending', $states);
        $this->assertContains('results', $states);
        $this->assertCount(7, $states);
    }

    /** @test */
    public function get_allowed_actions_returns_correct_actions(): void
    {
        $draftActions = TransitionMatrix::getAllowedActions('draft');
        $this->assertContains('submit_for_approval', $draftActions);
        $this->assertCount(1, $draftActions);

        $pendingApprovalActions = TransitionMatrix::getAllowedActions('pending_approval');
        $this->assertContains('approve', $pendingApprovalActions);
        $this->assertContains('reject', $pendingApprovalActions);

        $nominationActions = TransitionMatrix::getAllowedActions('nomination');
        $this->assertContains('open_voting', $nominationActions);

        $votingActions = TransitionMatrix::getAllowedActions('voting');
        $this->assertContains('close_voting', $votingActions);
    }

    /** @test */
    public function results_state_is_terminal(): void
    {
        $actions = TransitionMatrix::getAllowedActions('results');
        $this->assertEmpty($actions);
    }

    // ── Backward compatibility tests ──────────────────────────────────────────

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
    public function get_allowed_transitions_returns_correct_states(): void
    {
        $draftTransitions = TransitionMatrix::getAllowedTransitions('draft');
        $this->assertContains('pending_approval', $draftTransitions);

        $adminTransitions = TransitionMatrix::getAllowedTransitions('administration');
        $this->assertContains('nomination', $adminTransitions);

        $nominationTransitions = TransitionMatrix::getAllowedTransitions('nomination');
        $this->assertContains('voting', $nominationTransitions);
    }

    /** @test */
    public function unknown_state_has_no_transitions(): void
    {
        $this->assertFalse(TransitionMatrix::canTransition('unknown', 'administration'));
        $this->assertEquals([], TransitionMatrix::getAllowedTransitions('unknown'));
    }

    // ── Permission tests ──────────────────────────────────────────────────────

    /** @test */
    public function get_allowed_roles_for_open_voting_returns_chief_and_deputy(): void
    {
        $roles = TransitionMatrix::getAllowedRoles('open_voting');

        $this->assertContains('chief', $roles);
        $this->assertContains('deputy', $roles);
        $this->assertCount(2, $roles);
    }

    /** @test */
    public function get_allowed_roles_for_approve_returns_admin_only(): void
    {
        $roles = TransitionMatrix::getAllowedRoles('approve');

        $this->assertContains('admin', $roles);
        $this->assertCount(1, $roles);
    }

    /** @test */
    public function get_allowed_roles_for_submit_for_approval(): void
    {
        $roles = TransitionMatrix::getAllowedRoles('submit_for_approval');

        $this->assertContains('owner', $roles);
        $this->assertContains('admin', $roles);
        $this->assertContains('chief', $roles);
        $this->assertCount(3, $roles);
    }

    /** @test */
    public function system_actor_always_passes_action_requires_role(): void
    {
        // System role always bypasses permission checks
        $this->assertTrue(TransitionMatrix::actionRequiresRole('approve', 'system'));
        $this->assertTrue(TransitionMatrix::actionRequiresRole('open_voting', 'system'));
        $this->assertTrue(TransitionMatrix::actionRequiresRole('close_voting', 'system'));
        $this->assertTrue(TransitionMatrix::actionRequiresRole('publish_results', 'system'));
    }

    /** @test */
    public function observer_role_cannot_perform_open_voting(): void
    {
        $this->assertFalse(TransitionMatrix::actionRequiresRole('open_voting', 'observer'));
    }

    /** @test */
    public function chief_can_perform_open_voting(): void
    {
        $this->assertTrue(TransitionMatrix::actionRequiresRole('open_voting', 'chief'));
    }

    /** @test */
    public function deputy_can_perform_close_voting(): void
    {
        $this->assertTrue(TransitionMatrix::actionRequiresRole('close_voting', 'deputy'));
    }

    /** @test */
    public function admin_cannot_perform_open_voting(): void
    {
        // Admin can only approve/reject, not manage elections
        $this->assertFalse(TransitionMatrix::actionRequiresRole('open_voting', 'admin'));
    }

    /** @test */
    public function chief_cannot_perform_approve(): void
    {
        // Only admin can approve
        $this->assertFalse(TransitionMatrix::actionRequiresRole('approve', 'chief'));
    }
}
