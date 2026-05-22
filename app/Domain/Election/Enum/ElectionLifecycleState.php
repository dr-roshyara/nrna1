<?php

namespace App\Domain\Election\Enum;

/**
 * ElectionLifecycleState
 *
 * THE canonical lifecycle state for an election.
 * Single source of truth, replaces scattered status + is_active fields.
 *
 * States in order:
 * 1. Draft                  - New election, setup not started
 * 2. SubmittedForApproval   - Submitted for platform approval (voter capacity/payment)
 * 3. Approved               - Approved by platform (eligible for setup)
 * 4. Rejected               - Approval rejected (return to Draft with rejection reason)
 * 5. Setup                  - Configuration in progress (administration + nomination)
 * 6. ReadyForVoting         - Setup complete, candidates approved, awaiting voting window
 * 7. VotingActive           - Voting window is open NOW
 * 8. Counting               - Voting ended, results being tallied
 * 9. ResultsPublished       - Results published to members
 * 10. Archived              - Election complete and closed
 *
 * CAPACITY-BASED APPROVAL:
 * - Voter count ≤ 40 (free plan)   → auto-approve on submit_for_approval
 * - Voter count > 40 (paid plan)   → requires manual approval by platform admin
 *
 * TIMEZONE REQUIREMENT:
 * - Must be set before transitioning to ReadyForVoting
 */
enum ElectionLifecycleState: string
{
    case Draft = 'draft';
    case SubmittedForApproval = 'submitted_for_approval';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Setup = 'setup';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';

    /**
     * Human-readable label for UI display
     */
    public function label(): string
    {
        return match($this) {
            self::Draft => 'Draft Setup',
            self::SubmittedForApproval => 'Awaiting Approval',
            self::Approved => 'Approved - Ready for Setup',
            self::Rejected => 'Approval Rejected',
            self::Setup => 'Setup in Progress',
            self::ReadyForVoting => 'Ready for Voting',
            self::VotingActive => 'Voting Active',
            self::Counting => 'Counting Votes',
            self::ResultsPublished => 'Results Published',
            self::Archived => 'Archived',
        };
    }

    /**
     * Is this a terminal state? (no further transitions possible)
     */
    public function isTerminal(): bool
    {
        return $this === self::Archived;
    }

    /**
     * Is approval in progress? (awaiting platform review)
     */
    public function isAwaitingApproval(): bool
    {
        return $this === self::SubmittedForApproval;
    }

    /**
     * Is setup active? (Draft, Approved, or Setup state)
     */
    public function isInSetup(): bool
    {
        return $this === self::Draft
            || $this === self::Approved
            || $this === self::Setup;
    }

    /**
     * Is voting possible? (ReadyForVoting or VotingActive)
     */
    public function isVotingPhase(): bool
    {
        return $this === self::ReadyForVoting || $this === self::VotingActive;
    }

    /**
     * Is voting currently active? (votes being accepted now)
     */
    public function isActive(): bool
    {
        return $this === self::VotingActive;
    }
}
