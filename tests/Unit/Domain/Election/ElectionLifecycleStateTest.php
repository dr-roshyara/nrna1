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
     * Test: Enum has exactly 7 cases
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function enum_has_seven_cases(): void
    {
        $cases = ElectionLifecycleState::cases();

        $this->assertCount(7, $cases,
            'ElectionLifecycleState must have exactly 7 cases (Draft, Setup, ReadyForVoting, VotingActive, Counting, ResultsPublished, Archived)'
        );
    }

    /**
     * Test: Enum values are correct strings
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function enum_values_are_correct(): void
    {
        $this->assertEquals('draft', ElectionLifecycleState::Draft->value);
        $this->assertEquals('setup', ElectionLifecycleState::Setup->value);
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
        $this->assertEquals('Setup in Progress', ElectionLifecycleState::Setup->label());
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
}
