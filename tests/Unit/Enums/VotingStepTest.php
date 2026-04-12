<?php

namespace Tests\Unit\Enums;

use App\Enums\VotingStep;
use PHPUnit\Framework\TestCase;

class VotingStepTest extends TestCase
{
    /**
     * Test enum values are correct
     */
    public function test_voting_step_values(): void
    {
        $this->assertEquals(1, VotingStep::WAITING->value);
        $this->assertEquals(2, VotingStep::CODE_VERIFIED->value);
        $this->assertEquals(3, VotingStep::AGREEMENT_ACCEPTED->value);
        $this->assertEquals(4, VotingStep::VOTE_CAST->value);
        $this->assertEquals(5, VotingStep::VERIFIED->value);
    }

    /**
     * Test human-readable labels
     */
    public function test_voting_step_labels(): void
    {
        $this->assertEquals('Waiting to Vote', VotingStep::WAITING->label());
        $this->assertEquals('Code Verified', VotingStep::CODE_VERIFIED->label());
        $this->assertEquals('Agreement Accepted', VotingStep::AGREEMENT_ACCEPTED->label());
        $this->assertEquals('Vote Cast', VotingStep::VOTE_CAST->label());
        $this->assertEquals('Vote Verified', VotingStep::VERIFIED->label());
    }

    /**
     * Test timestamp column mapping
     */
    public function test_voting_step_timestamp_columns(): void
    {
        $this->assertEquals('voting_started_at', VotingStep::WAITING->timestampColumn());
        $this->assertEquals('code1_used_at', VotingStep::CODE_VERIFIED->timestampColumn());
        $this->assertEquals('has_agreed_to_vote_at', VotingStep::AGREEMENT_ACCEPTED->timestampColumn());
        $this->assertEquals('vote_submitted_at', VotingStep::VOTE_CAST->timestampColumn());
        $this->assertEquals('vote_completed_at', VotingStep::VERIFIED->timestampColumn());
    }

    /**
     * Test previous step navigation
     */
    public function test_voting_step_previous(): void
    {
        $this->assertNull(VotingStep::WAITING->previous());
        $this->assertEquals(VotingStep::WAITING, VotingStep::CODE_VERIFIED->previous());
        $this->assertEquals(VotingStep::CODE_VERIFIED, VotingStep::AGREEMENT_ACCEPTED->previous());
        $this->assertEquals(VotingStep::AGREEMENT_ACCEPTED, VotingStep::VOTE_CAST->previous());
        $this->assertEquals(VotingStep::VOTE_CAST, VotingStep::VERIFIED->previous());
    }

    /**
     * Test next step navigation
     */
    public function test_voting_step_next(): void
    {
        $this->assertEquals(VotingStep::CODE_VERIFIED, VotingStep::WAITING->next());
        $this->assertEquals(VotingStep::AGREEMENT_ACCEPTED, VotingStep::CODE_VERIFIED->next());
        $this->assertEquals(VotingStep::VOTE_CAST, VotingStep::AGREEMENT_ACCEPTED->next());
        $this->assertEquals(VotingStep::VERIFIED, VotingStep::VOTE_CAST->next());
        $this->assertNull(VotingStep::VERIFIED->next());
    }

    /**
     * Test step comparison methods
     */
    public function test_voting_step_comparisons(): void
    {
        // Test isBefore
        $this->assertTrue(VotingStep::WAITING->isBefore(VotingStep::CODE_VERIFIED));
        $this->assertTrue(VotingStep::CODE_VERIFIED->isBefore(VotingStep::VOTE_CAST));
        $this->assertFalse(VotingStep::VERIFIED->isBefore(VotingStep::WAITING));

        // Test isAfter
        $this->assertTrue(VotingStep::VERIFIED->isAfter(VotingStep::CODE_VERIFIED));
        $this->assertTrue(VotingStep::VOTE_CAST->isAfter(VotingStep::WAITING));
        $this->assertFalse(VotingStep::WAITING->isAfter(VotingStep::VERIFIED));
    }

    /**
     * Test ordered steps array
     */
    public function test_voting_step_all_ordered(): void
    {
        $allSteps = VotingStep::allOrdered();

        $this->assertCount(5, $allSteps);
        $this->assertEquals(VotingStep::WAITING, $allSteps[0]);
        $this->assertEquals(VotingStep::CODE_VERIFIED, $allSteps[1]);
        $this->assertEquals(VotingStep::AGREEMENT_ACCEPTED, $allSteps[2]);
        $this->assertEquals(VotingStep::VOTE_CAST, $allSteps[3]);
        $this->assertEquals(VotingStep::VERIFIED, $allSteps[4]);
    }

    /**
     * Test progress calculation
     */
    public function test_voting_step_progress_percentage(): void
    {
        $this->assertEquals(20, VotingStep::progressPercentage(VotingStep::WAITING));
        $this->assertEquals(40, VotingStep::progressPercentage(VotingStep::CODE_VERIFIED));
        $this->assertEquals(60, VotingStep::progressPercentage(VotingStep::AGREEMENT_ACCEPTED));
        $this->assertEquals(80, VotingStep::progressPercentage(VotingStep::VOTE_CAST));
        $this->assertEquals(100, VotingStep::progressPercentage(VotingStep::VERIFIED));
    }

    /**
     * Test through method returns correct steps
     */
    public function test_voting_step_through(): void
    {
        $throughVoteCast = VotingStep::through(VotingStep::VOTE_CAST);

        $this->assertCount(4, $throughVoteCast);
        $this->assertEquals(VotingStep::WAITING, $throughVoteCast[0]);
        $this->assertEquals(VotingStep::CODE_VERIFIED, $throughVoteCast[1]);
        $this->assertEquals(VotingStep::AGREEMENT_ACCEPTED, $throughVoteCast[2]);
        $this->assertEquals(VotingStep::VOTE_CAST, $throughVoteCast[3]);
    }
}
