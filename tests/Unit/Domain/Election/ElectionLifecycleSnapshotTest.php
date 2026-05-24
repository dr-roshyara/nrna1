<?php

namespace Tests\Unit\Domain\Election;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use PHPUnit\Framework\TestCase;

/**
 * Phase 1: SSOT Layer
 * Test: ElectionLifecycleSnapshot DTO
 *
 * RED tests for the immutable read model returned by ElectionLifecycleEngine.
 * This DTO carries the complete truth about an election's lifecycle at one moment.
 */
class ElectionLifecycleSnapshotTest extends TestCase
{
    /**
     * Test: Snapshot properties are readonly (immutable)
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function snapshot_is_immutable(): void
    {
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: true,
            blockedReason: null,
            allowedActions: ['close_voting'],
        );

        // Attempt to modify readonly property
        $this->expectException(\Error::class);
        $snapshot->canVote = false;
    }

    /**
     * Test: isActive() returns true only for VotingActive state
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function is_active_returns_true_only_for_voting_active_state(): void
    {
        $activeSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['close_voting'],
        );

        $draftSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: 'Election setup not started',
            allowedActions: ['submit_for_approval'],
        );

        $this->assertTrue($activeSnapshot->isActive());
        $this->assertFalse($draftSnapshot->isActive());
    }

    /**
     * Test: canTransitionTo() checks allowed actions
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function can_transition_to_checks_allowed_actions(): void
    {
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['close_voting'],
        );

        $this->assertTrue($snapshot->canTransitionTo('close_voting'));
        $this->assertFalse($snapshot->canTransitionTo('open_voting'));
    }

    /**
     * Test: blockedReason is populated when action not allowed
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function blocked_reason_explains_why_action_not_allowed(): void
    {
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Draft,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: 'Election setup not started',
            allowedActions: [],
        );

        $this->assertFalse($snapshot->canVote);
        $this->assertNotNull($snapshot->blockedReason);
        $this->assertEquals('Election setup not started', $snapshot->blockedReason);
    }

    /**
     * Test: allowedActions empty array when no transitions possible
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function allowed_actions_empty_when_no_transitions_possible(): void
    {
        $snapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::Archived,
            canEdit: false,
            canVote: false,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: true,
            blockedReason: 'Election archived',
            allowedActions: [],
        );

        $this->assertEmpty($snapshot->allowedActions);
    }

    /**
     * Test: isLocked indicates if state is frozen
     */
    #[\PHPUnit\Framework\Attributes\Test]
    public function is_locked_indicates_frozen_state(): void
    {
        $lockedSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::VotingActive,
            canEdit: false,
            canVote: true,
            canManageVoters: false,
            canPublishResults: false,
            canEditTimeline: false,
            isLocked: true,
            blockedReason: null,
            allowedActions: ['close_voting'],
        );

        $unlockedSnapshot = new ElectionLifecycleSnapshot(
            state: ElectionLifecycleState::SetupAdministration,
            canEdit: true,
            canVote: false,
            canManageVoters: true,
            canPublishResults: false,
            canEditTimeline: true,
            isLocked: false,
            blockedReason: null,
            allowedActions: ['add_voters', 'add_posts'],
        );

        $this->assertTrue($lockedSnapshot->isLocked);
        $this->assertFalse($unlockedSnapshot->isLocked);
    }
}
