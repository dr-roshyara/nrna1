<?php

namespace App\Domain\Election\Enum;

/**
 * ElectionLifecycleState
 *
 * THE canonical lifecycle state for an election.
 * Single source of truth, replaces scattered status + is_active fields.
 *
 * States in order:
 * 1. Draft           - Setup not started
 * 2. Setup           - Configuration in progress (administration + nomination)
 * 3. ReadyForVoting  - Setup complete, candidates approved, awaiting voting window
 * 4. VotingActive    - Voting window is open NOW
 * 5. Counting        - Voting ended, results being tallied
 * 6. ResultsPublished - Results published to members
 * 7. Archived        - Election complete and closed
 */
enum ElectionLifecycleState: string
{
    case Draft = 'draft';
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
     * Is setup active? (Draft or Setup state)
     */
    public function isInSetup(): bool
    {
        return $this === self::Draft || $this === self::Setup;
    }

    /**
     * Is voting possible? (ReadyForVoting or VotingActive)
     */
    public function isVotingPhase(): bool
    {
        return $this === self::ReadyForVoting || $this === self::VotingActive;
    }
}
