<?php

namespace App\Application\Election\Services;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Services\ElectionLifecycleEngine;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;
use Carbon\Carbon;

/**
 * Implementation of single source of truth for election lifecycle.
 *
 * State derivation order (priority):
 * 1. Terminal: Results published? → ResultsPublished
 * 2. Time window: Is voting window open NOW? → VotingActive
 * 3. Counting: Voting ended? → Counting
 * 4. Ready: Setup complete, candidates approved? → ReadyForVoting
 * 5. Setup: Administration completed? → Setup
 * 6. Default: → Draft
 */
final class ElectionLifecycleEngineImpl implements ElectionLifecycleEngine
{
    public function compute(Election $election): ElectionLifecycleSnapshot
    {
        $state = $this->getState($election);
        $permissions = $this->derivePermissions($state, $election);
        $isLocked = $this->isLocked($state);
        $blockedReason = $this->getBlockedReason($state);
        $allowedActions = $this->getAllowedActions($state, $election);

        return new ElectionLifecycleSnapshot(
            state: $state,
            canEdit: $permissions['canEdit'],
            canVote: $permissions['canVote'],
            canManageVoters: $permissions['canManageVoters'],
            canPublishResults: $permissions['canPublishResults'],
            canEditTimeline: $permissions['canEditTimeline'],
            isLocked: $isLocked,
            blockedReason: $blockedReason,
            allowedActions: $allowedActions,
        );
    }

    public function getState(Election $election): ElectionLifecycleState
    {
        // 1. Terminal: Results published?
        if ($election->results_published_at !== null) {
            return ElectionLifecycleState::ResultsPublished;
        }

        // 2. Approval workflow states (before setup begins)
        // Rejected (final state for failed approvals)
        if ($election->rejected_at !== null) {
            return ElectionLifecycleState::Rejected;
        }

        // Submitted for approval (awaiting platform review)
        if ($election->submitted_for_approval_at !== null && $election->approved_at === null) {
            return ElectionLifecycleState::SubmittedForApproval;
        }

        // Approved but setup not started (administration_completed = false)
        if ($election->approved_at !== null && !$election->administration_completed) {
            return ElectionLifecycleState::Approved;
        }

        // 3. Time window: Is voting window open NOW?
        if ($this->isVotingWindowOpenNow($election)) {
            return ElectionLifecycleState::VotingActive;
        }

        // 4. Counting: Voting ended?
        if ($this->hasVotingEnded($election)) {
            return ElectionLifecycleState::Counting;
        }

        // 5. Ready: Setup complete, candidates approved?
        if ($this->isSetupComplete($election) && $this->hasCandidatesApproved($election)) {
            return ElectionLifecycleState::ReadyForVoting;
        }

        // 6. Setup: Administration started (administration_completed = true)
        if ($election->administration_completed) {
            return ElectionLifecycleState::Setup;
        }

        // 7. Default to Draft
        return ElectionLifecycleState::Draft;
    }

    private function isVotingWindowOpenNow(Election $election): bool
    {
        $now = Carbon::now();
        $votingStartsAt = $election->voting_starts_at;
        $votingEndsAt = $election->voting_ends_at;

        if ($votingStartsAt === null || $votingEndsAt === null) {
            return false;
        }

        return $now->greaterThanOrEqualTo($votingStartsAt) && $now->lessThan($votingEndsAt);
    }

    private function hasVotingEnded(Election $election): bool
    {
        if ($election->voting_ends_at === null) {
            return false;
        }

        return Carbon::now()->greaterThanOrEqualTo($election->voting_ends_at);
    }

    private function isSetupComplete(Election $election): bool
    {
        return $election->administration_completed && $election->nomination_completed;
    }

    private function hasCandidatesApproved(Election $election): bool
    {
        // At least one approved candidate must exist
        return $election->candidacies()
            ->where('approval_status', 'approved')
            ->exists();
    }

    private function derivePermissions(ElectionLifecycleState $state, Election $election): array
    {
        return match ($state) {
            ElectionLifecycleState::Draft => [
                'canEdit' => true,
                'canVote' => false,
                'canManageVoters' => true,
                'canPublishResults' => false,
                'canEditTimeline' => true,
            ],
            ElectionLifecycleState::SubmittedForApproval => [
                'canEdit' => false,  // Locked during review
                'canVote' => false,
                'canManageVoters' => false,
                'canPublishResults' => false,
                'canEditTimeline' => false,  // Frozen under platform review
            ],
            ElectionLifecycleState::Approved => [
                'canEdit' => true,  // Can configure after approval
                'canVote' => false,
                'canManageVoters' => true,
                'canPublishResults' => false,
                'canEditTimeline' => true,
            ],
            ElectionLifecycleState::Rejected => [
                'canEdit' => true,  // Can revise and resubmit
                'canVote' => false,
                'canManageVoters' => true,
                'canPublishResults' => false,
                'canEditTimeline' => true,  // Allow revision
            ],
            ElectionLifecycleState::Setup => [
                'canEdit' => true,
                'canVote' => false,
                'canManageVoters' => true,
                'canPublishResults' => false,
                'canEditTimeline' => true,  // Still in setup phase
            ],
            ElectionLifecycleState::ReadyForVoting => [
                'canEdit' => false,
                'canVote' => false,
                'canManageVoters' => false,
                'canPublishResults' => false,
                'canEditTimeline' => true,  // Limited: can adjust if voting not opened yet
            ],
            ElectionLifecycleState::VotingActive => [
                'canEdit' => false,
                'canVote' => true,
                'canManageVoters' => false,
                'canPublishResults' => false,
                'canEditTimeline' => false,  // Voting integrity - no changes
            ],
            ElectionLifecycleState::Counting => [
                'canEdit' => false,
                'canVote' => false,
                'canManageVoters' => false,
                'canPublishResults' => true,
                'canEditTimeline' => false,  // Audit trail immutability
            ],
            ElectionLifecycleState::ResultsPublished => [
                'canEdit' => false,
                'canVote' => false,
                'canManageVoters' => false,
                'canPublishResults' => false,
                'canEditTimeline' => false,  // Historical record immutability
            ],
            ElectionLifecycleState::Archived => [
                'canEdit' => false,
                'canVote' => false,
                'canManageVoters' => false,
                'canPublishResults' => false,
                'canEditTimeline' => false,  // Terminal state
            ],
        };
    }

    private function isLocked(ElectionLifecycleState $state): bool
    {
        return match ($state) {
            ElectionLifecycleState::Draft, ElectionLifecycleState::Setup => false,
            default => true,
        };
    }

    private function getBlockedReason(ElectionLifecycleState $state): ?string
    {
        return match ($state) {
            ElectionLifecycleState::Draft => 'Election setup not started',
            ElectionLifecycleState::SubmittedForApproval => 'Awaiting platform approval',
            ElectionLifecycleState::Rejected => 'Approval rejected - address feedback and resubmit',
            ElectionLifecycleState::ReadyForVoting => 'Awaiting voting window',
            ElectionLifecycleState::Counting => 'Voting period has ended',
            ElectionLifecycleState::ResultsPublished => 'Results already published',
            ElectionLifecycleState::Archived => 'Election archived',
            default => null,
        };
    }

    private function getAllowedActions(ElectionLifecycleState $state, Election $election): array
    {
        return match ($state) {
            ElectionLifecycleState::Draft => ['submit_for_approval'],
            ElectionLifecycleState::SubmittedForApproval => [],  // Awaiting admin approval
            ElectionLifecycleState::Approved => ['begin_setup'],
            ElectionLifecycleState::Rejected => ['revise_and_resubmit'],
            ElectionLifecycleState::Setup => ['complete_administration', 'complete_nomination'],
            ElectionLifecycleState::ReadyForVoting => ['open_voting'],
            ElectionLifecycleState::VotingActive => ['close_voting', 'pause_voting'],
            ElectionLifecycleState::Counting => ['publish_results'],
            ElectionLifecycleState::ResultsPublished => ['archive'],
            ElectionLifecycleState::Archived => [],
        };
    }
}
