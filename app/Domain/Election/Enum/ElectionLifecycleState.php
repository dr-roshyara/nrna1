<?php

namespace App\Domain\Election\Enum;

/**
 * ElectionLifecycleState
 *
 * THE canonical lifecycle state for an election.
 * Single source of truth, replaces scattered status + is_active fields.
 *
 * States in order:
 * 1. Draft                  - New election, not yet submitted for approval
 * 2. SubmittedForApproval   - Submitted for platform approval (voter capacity/payment)
 * 3. Approved               - Platform approved; committee hasn't started setup yet
 * 4. Rejected               - Approval rejected; can revise and resubmit
 * 5. SetupAdministration    - Governance infrastructure: posts, voters, chief (setup_started_at set)
 * 6. SetupNomination        - Democratic candidacy process: candidates apply & approve (time-based)
 * 7. ReadyForVoting         - Setup complete, candidates approved, awaiting voting window
 * 8. VotingActive           - Voting window is open NOW
 * 9. Counting               - Voting ended, results being tallied
 * 10. ResultsPublished      - Results published to members
 * 11. Archived              - Election complete and closed
 * 12. Suspended             - Operational override: election paused (fraud, legal hold, emergency)
 *
 * KEY FACTS:
 * - Engine derives state from facts: approved_at, setup_started_at, administration_completed,
 *   nomination_completed, nomination_suggested_start/end, voting times, results_published_at
 * - State column is compatibility cache only; engine is sovereign
 * - SetupNomination is time-based: engine derives it when nomination window opens
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
    case SetupAdministration = 'setup_administration';
    case SetupNomination = 'setup_nomination';
    case ReadyForVoting = 'ready_for_voting';
    case VotingActive = 'voting_active';
    case Counting = 'counting';
    case ResultsPublished = 'results_published';
    case Archived = 'archived';

    /**
     * ARCHITECTURAL INVARIANT: Suspended is an operational governance overlay.
     * It is NOT part of constitutional lifecycle progression.
     *
     * Do NOT:
     * - Include in timeline ordering or phase sequences
     * - Include in progress percentage calculations
     * - Treat as next/previous phase in stepper/wizard UI
     * - Use to derive business progression
     * - Check election->suspended_at directly in controllers or Vue components
     *
     * Suspension freezes capabilities only. All suspension impact
     * must flow through ElectionLifecycleSnapshot capabilities.
     *
     * When an election is suspended and time passes (windows expire, votes accumulate),
     * resume must NOT restore to the suspended state. Instead, resume clears suspension
     * flags only and the engine re-derives lifecycle state from current facts.
     *
     * Example:
     *   VotingActive (voting ends tomorrow) → Suspend → Resume (3 days later) → Counting
     *   (NOT: VotingActive again, even though that's the suspended_lifecycle_context)
     *
     * Long-term migration target: dual-axis model (LifecycleState + OperationalStatus)
     */
    case Suspended = 'suspended';

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
            self::SetupAdministration => 'Administration Setup',
            self::SetupNomination => 'Nomination Setup',
            self::ReadyForVoting => 'Ready for Voting',
            self::VotingActive => 'Voting Active',
            self::Counting => 'Counting Votes',
            self::ResultsPublished => 'Results Published',
            self::Archived => 'Archived',
            self::Suspended => 'Election Suspended',
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
     * Is setup active? (Draft, Approved, SetupAdministration, or SetupNomination)
     */
    public function isInSetup(): bool
    {
        return $this === self::Draft
            || $this === self::Approved
            || $this === self::SetupAdministration
            || $this === self::SetupNomination;
    }

    /**
     * Is suspended? (operational override, not progression)
     */
    public function isSuspended(): bool
    {
        return $this === self::Suspended;
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
