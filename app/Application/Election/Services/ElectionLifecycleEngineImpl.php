<?php

namespace App\Application\Election\Services;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\Exception\InvalidElectionStateException;
use App\Domain\Election\Policies\NominationWindowPolicy;
use App\Domain\Election\Services\ElectionLifecycleEngine;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Models\Election;
use App\Services\ElectionClockService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

/**
 * Implementation of single source of truth for election lifecycle.
 *
 * CRITICAL: Derives state ONLY from business facts.
 * State column is compatibility cache, not truth.
 *
 * 12-Step derivation order (constitutional priority):
 * 1. Suspended (operational override)
 * 2. Archived (terminal state)
 * 3. ResultsPublished (terminal)
 * 4. Counting (with setup verification)
 * 5. VotingActive (temporal window)
 * 6. ReadyForVoting (setup complete, awaiting voting)
 * 7. SetupNomination (nomination process)
 * 8. SetupAdministration (governance infrastructure)
 * 9. Approved (approved, setup not started)
 * 10. SubmittedForApproval (under platform review)
 * 11. Rejected (approval denied)
 * 12. Draft (fallback)
 */
final class ElectionLifecycleEngineImpl implements ElectionLifecycleEngine
{
    private NominationWindowPolicy $nominationPolicy;

    public function __construct()
    {
        $this->nominationPolicy = app(NominationWindowPolicy::class);
    }
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
        $now = now();

        // 1. Suspended override — governance operational pause (checked FIRST)
        if ($election->suspended_at !== null) {
            return ElectionLifecycleState::Suspended;
        }

        // 2. Archived — election complete and closed
        if ($election->archived_at !== null) {
            return ElectionLifecycleState::Archived;
        }

        // 3. Results published — votes tallied and published
        if ($election->results_published_at !== null) {
            return ElectionLifecycleState::ResultsPublished;
        }

        // 4. Counting — voting ended, tallying in progress
        // CRITICAL: Requires legitimate setup completion
        if ($election->voting_ends_at !== null && $now->gte($election->voting_ends_at)) {
            // Verify setup was completed legitimately
            if ($election->approved_at !== null && $election->administration_completed && $election->nomination_completed) {
                return ElectionLifecycleState::Counting;
            }
            // Setup incomplete but voting closed → constitutional limbo (soft enforcement)
            Log::warning('Constitutional anomaly: voting ended without setup completion', [
                'election_id' => $election->id,
                'approved_at' => $election->approved_at?->toIso8601String(),
                'administration_completed' => $election->administration_completed,
                'nomination_completed' => $election->nomination_completed,
            ]);
        }

        // 5. Voting active — voting window is open NOW
        // EM-VOT-002 (Election Manifesto §4a): voting requires at least one
        // approved candidate on EVERY path into voting_active — including this
        // computed one, which no command guard can reach (PBDIGIT-64). When
        // unmet, no substitute state is chosen here: derivation falls through
        // to the existing rules below (fallback semantics are an open PO decision).
        if ($this->isVotingWindowOpenNow($election) && $this->hasCandidatesApproved($election)) {
            return ElectionLifecycleState::VotingActive;
        }

        // 6. Ready for voting — setup complete, awaiting voting window
        if ($election->administration_completed && $election->nomination_completed) {
            // Check if voting window hasn't opened yet
            if ($election->voting_starts_at !== null && $now->lt($election->voting_starts_at)) {
                return ElectionLifecycleState::ReadyForVoting;
            }
            // If no voting times set, cannot be ready (incomplete setup)
            if ($election->voting_starts_at === null || $election->voting_ends_at === null) {
                return ElectionLifecycleState::SetupNomination;
            }
        }

        // 7. Setup nomination — democratic candidacy process
        if ($election->administration_completed && !$election->nomination_completed) {
            // If nomination dates not set, stay in nomination until they are
            if (!$this->nominationPolicy->hasNominationWindow($election)) {
                return ElectionLifecycleState::SetupNomination;
            }

            // Nomination window pending (not yet open)
            if ($this->nominationPolicy->isPending($election, $now)) {
                return ElectionLifecycleState::SetupAdministration;
            }

            // Nomination window open or expired → stay in nomination
            return ElectionLifecycleState::SetupNomination;
        }

        // 8. Setup administration — governance infrastructure readiness
        if ($election->setup_started_at !== null && !$election->administration_completed) {
            return ElectionLifecycleState::SetupAdministration;
        }

        // 9. Approved — platform approved, committee hasn't started setup
        // CRITICAL: setup_started_at must be null to distinguish from SetupAdministration
        if ($election->approved_at !== null && $election->setup_started_at === null) {
            return ElectionLifecycleState::Approved;
        }

        // 10. Submitted for approval — platform review in progress
        if ($election->submitted_for_approval_at !== null && $election->approved_at === null && $election->rejected_at === null) {
            return ElectionLifecycleState::SubmittedForApproval;
        }

        // 11. Rejected — platform rejected, can revise and resubmit
        if ($election->rejected_at !== null && $election->approved_at === null) {
            return ElectionLifecycleState::Rejected;
        }

        // 12. Draft — fallback for newly created elections
        if ($election->approved_at === null && $election->submitted_for_approval_at === null && $election->rejected_at === null) {
            return ElectionLifecycleState::Draft;
        }

        // Constitutional invariant violated
        throw new InvalidElectionStateException(
            "Election {$election->id} in invalid constitutional state. " .
            "Check: approved_at={$election->approved_at}, setup_started_at={$election->setup_started_at}, " .
            "administration_completed={$election->administration_completed}"
        );
    }

    private function isVotingWindowOpenNow(Election $election): bool
    {
        return ElectionClockService::isVotingOpen($election);
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
            ->where('status', 'approved')
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
            ElectionLifecycleState::SetupAdministration => [
                'canEdit' => true,
                'canVote' => false,
                'canManageVoters' => true,
                'canPublishResults' => false,
                'canEditTimeline' => true,  // Configure governance infrastructure
            ],
            ElectionLifecycleState::SetupNomination => [
                'canEdit' => true,
                'canVote' => false,
                'canManageVoters' => false,  // Voters locked
                'canPublishResults' => false,
                'canEditTimeline' => true,  // Can adjust nomination window
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
            ElectionLifecycleState::Suspended => [
                'canEdit' => false,
                'canVote' => false,
                'canManageVoters' => false,
                'canPublishResults' => false,
                'canEditTimeline' => false,  // All operations locked during suspension
            ],
        };
    }

    private function isLocked(ElectionLifecycleState $state): bool
    {
        return match ($state) {
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::SetupAdministration,
            ElectionLifecycleState::SetupNomination => false,
            ElectionLifecycleState::Suspended => true,
            default => true,
        };
    }

    private function getBlockedReason(ElectionLifecycleState $state): ?string
    {
        return match ($state) {
            ElectionLifecycleState::Draft => 'Election setup not started',
            ElectionLifecycleState::SubmittedForApproval => 'Awaiting platform approval',
            ElectionLifecycleState::Approved => 'Ready to begin setup - click "Begin Setup"',
            ElectionLifecycleState::Rejected => 'Approval rejected - address feedback and resubmit',
            ElectionLifecycleState::SetupAdministration => 'Configuring governance infrastructure',
            ElectionLifecycleState::SetupNomination => 'Democratic candidacy process in progress',
            ElectionLifecycleState::ReadyForVoting => 'Awaiting voting window',
            ElectionLifecycleState::Counting => 'Voting period has ended',
            ElectionLifecycleState::ResultsPublished => 'Results already published',
            ElectionLifecycleState::Archived => 'Election archived',
            ElectionLifecycleState::Suspended => 'Election is suspended - resume to continue',
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
            ElectionLifecycleState::SetupAdministration => ['complete_administration'],
            ElectionLifecycleState::SetupNomination => ['complete_nomination', 'open_voting'],
            ElectionLifecycleState::ReadyForVoting => ['open_voting'],
            ElectionLifecycleState::VotingActive => ['close_voting'],
            ElectionLifecycleState::Counting => ['publish_results'],
            ElectionLifecycleState::ResultsPublished => ['archive'],
            ElectionLifecycleState::Archived => [],
            ElectionLifecycleState::Suspended => ['resume'],
        };
    }
}
