<?php

namespace Tests\Unit\Domain\Election\Projection;

use App\Domain\Election\Projection\ElectionLifecycleProjection;
use PHPUnit\Framework\TestCase;

class ElectionLifecycleProjectionTest extends TestCase
{
    public function test_draft_has_no_completed_states(): void
    {
        $this->assertSame([], ElectionLifecycleProjection::completedStatesFor('draft'));
        $this->assertTrue(ElectionLifecycleProjection::isProjectionAvailable('draft'));
    }

    public function test_setup_nomination_has_four_prior_states_completed(): void
    {
        $completed = ElectionLifecycleProjection::completedStatesFor('setup_nomination');
        $this->assertContains('draft', $completed);
        $this->assertContains('submitted_for_approval', $completed);
        $this->assertContains('approved', $completed);
        $this->assertContains('setup_administration', $completed);
        $this->assertNotContains('setup_nomination', $completed);
        $this->assertCount(4, $completed);
        $this->assertTrue(ElectionLifecycleProjection::isProjectionAvailable('setup_nomination'));
    }

    public function test_archived_has_all_nine_prior_states_completed(): void
    {
        $completed = ElectionLifecycleProjection::completedStatesFor('archived');
        $this->assertCount(9, $completed);
        $this->assertNotContains('archived', $completed);
        $this->assertTrue(ElectionLifecycleProjection::isProjectionAvailable('archived'));
    }

    public function test_rejected_is_not_in_linear_progression(): void
    {
        $this->assertSame([], ElectionLifecycleProjection::completedStatesFor('rejected'));
        $this->assertFalse(ElectionLifecycleProjection::isProjectionAvailable('rejected'));
    }

    public function test_suspended_overlay_projection_unavailable(): void
    {
        $this->assertSame([], ElectionLifecycleProjection::completedStatesFor('suspended'));
        $this->assertFalse(ElectionLifecycleProjection::isProjectionAvailable('suspended'));
    }

    public function test_voting_active_has_prior_states_completed(): void
    {
        $completed = ElectionLifecycleProjection::completedStatesFor('voting_active');
        $this->assertContains('ready_for_voting', $completed);
        $this->assertContains('setup_nomination', $completed);
        $this->assertNotContains('voting_active', $completed);
        $this->assertTrue(ElectionLifecycleProjection::isProjectionAvailable('voting_active'));
    }

    public function test_unknown_state_projection_unavailable(): void
    {
        $this->assertSame([], ElectionLifecycleProjection::completedStatesFor('completely_unknown'));
        $this->assertFalse(ElectionLifecycleProjection::isProjectionAvailable('completely_unknown'));
    }
}
