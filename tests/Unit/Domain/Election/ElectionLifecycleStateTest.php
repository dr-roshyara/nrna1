<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\Enum\ElectionLifecycleState;
use PHPUnit\Framework\TestCase;

/**
 * Phase 1: SSOT Layer
 * Test: ElectionLifecycleState Enum
 *
 * RED tests for the canonical lifecycle state enum.
 * This enum replaces the scattered status + is_active fields.
 */
class ElectionLifecycleStateTest extends TestCase
{
    /**
     * Test: Enum has exactly 11 cases (after setup split + approved + suspended)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function enum_has_correct_case_count(): void
    {
        $cases = ElectionLifecycleState::cases();

        $this->assertCount(12, $cases,
            'ElectionLifecycleState must have 12 cases: Draft, SubmittedForApproval, Approved, Rejected, SetupAdministration, SetupNomination, ReadyForVoting, VotingActive, Counting, ResultsPublished, Archived, Suspended'
        );
    }

    /**
     * Test: Enum values are correct strings
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function enum_values_are_correct(): void
    {
        $this->assertEquals('draft', ElectionLifecycleState::Draft->value);
        $this->assertEquals('submitted_for_approval', ElectionLifecycleState::SubmittedForApproval->value);
        $this->assertEquals('approved', ElectionLifecycleState::Approved->value);
        $this->assertEquals('rejected', ElectionLifecycleState::Rejected->value);
        $this->assertEquals('setup_administration', ElectionLifecycleState::SetupAdministration->value);
        $this->assertEquals('setup_nomination', ElectionLifecycleState::SetupNomination->value);
        $this->assertEquals('ready_for_voting', ElectionLifecycleState::ReadyForVoting->value);
        $this->assertEquals('voting_active', ElectionLifecycleState::VotingActive->value);
        $this->assertEquals('counting', ElectionLifecycleState::Counting->value);
        $this->assertEquals('results_published', ElectionLifecycleState::ResultsPublished->value);
        $this->assertEquals('archived', ElectionLifecycleState::Archived->value);
    }

    /**
     * Test: label() returns human-readable string for each state
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function label_returns_human_readable_string(): void
    {
        $this->assertEquals('Draft Setup', ElectionLifecycleState::Draft->label());
        $this->assertEquals('Awaiting Approval', ElectionLifecycleState::SubmittedForApproval->label());
        $this->assertEquals('Approved - Ready for Setup', ElectionLifecycleState::Approved->label());
        $this->assertEquals('Approval Rejected', ElectionLifecycleState::Rejected->label());
        $this->assertEquals('Administration Setup', ElectionLifecycleState::SetupAdministration->label());
        $this->assertEquals('Nomination Setup', ElectionLifecycleState::SetupNomination->label());
        $this->assertEquals('Ready for Voting', ElectionLifecycleState::ReadyForVoting->label());
        $this->assertEquals('Voting Active', ElectionLifecycleState::VotingActive->label());
        $this->assertEquals('Counting Votes', ElectionLifecycleState::Counting->label());
        $this->assertEquals('Results Published', ElectionLifecycleState::ResultsPublished->label());
        $this->assertEquals('Archived', ElectionLifecycleState::Archived->label());
    }

    /**
     * Test: Can create from string value
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function can_create_from_string_value(): void
    {
        $state = ElectionLifecycleState::from('voting_active');

        $this->assertEquals(ElectionLifecycleState::VotingActive, $state);
    }

    /**
     * Test: Throws on invalid string value
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function throws_on_invalid_value(): void
    {
        $this->expectException(\ValueError::class);

        ElectionLifecycleState::from('invalid_state');
    }

    /**
     * Test: SetupAdministration state exists
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_setup_administration_state_exists(): void
    {
        $state = ElectionLifecycleState::SetupAdministration;
        $this->assertEquals('setup_administration', $state->value);
    }

    /**
     * Test: SetupNomination state exists
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_setup_nomination_state_exists(): void
    {
        $state = ElectionLifecycleState::SetupNomination;
        $this->assertEquals('setup_nomination', $state->value);
    }

    /**
     * Test: Setup state is removed (cannot be created from string)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_setup_state_removed(): void
    {
        $this->expectException(\ValueError::class);
        ElectionLifecycleState::from('setup');
    }

    /**
     * PHASE B: OPERATIONAL SUSPENSION OVERLAY
     * RED test: Suspended value is correct
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_suspended_value_is_correct(): void
    {
        $this->assertEquals('suspended', ElectionLifecycleState::Suspended->value);
    }

    /**
     * RED test: Suspended label is human-readable
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_suspended_label_is_election_suspended(): void
    {
        $this->assertEquals('Election Suspended', ElectionLifecycleState::Suspended->label());
    }

    /**
     * RED test: isSuspended() returns true for Suspended state
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_is_suspended_returns_true_for_suspended(): void
    {
        $this->assertTrue(ElectionLifecycleState::Suspended->isSuspended());
    }

    /**
     * RED test: isSuspended() returns false for non-suspended states
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function test_is_suspended_returns_false_for_non_suspended_states(): void
    {
        $this->assertFalse(ElectionLifecycleState::Draft->isSuspended());
        $this->assertFalse(ElectionLifecycleState::VotingActive->isSuspended());
        $this->assertFalse(ElectionLifecycleState::Archived->isSuspended());
    }
}
