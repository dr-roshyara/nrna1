<?php

namespace App\Domain\Election\ValueObjects;

use App\Domain\Election\Enum\ElectionLifecycleState;

final class ElectionLifecycleSnapshot
{
    public function __construct(
        public readonly ElectionLifecycleState $state,
        public readonly bool $canEdit,
        public readonly bool $canVote,
        public readonly bool $canManageVoters,
        public readonly bool $canPublishResults,
        public readonly bool $canEditTimeline,
        public readonly bool $isLocked,
        public readonly ?string $blockedReason,
        public readonly array $allowedActions,
    ) {}

    public function isActive(): bool
    {
        return $this->state === ElectionLifecycleState::VotingActive;
    }

    public function isActionAllowed(string $action): bool
    {
        return in_array($action, $this->allowedActions, true);
    }

    /**
     * Answers: "Is the voter-source strategy constitutionally frozen for this election?"
     *
     * Freeze begins when voter import can start (SetupAdministration and beyond).
     * Overlay states (Suspended) preserve the freeze — they do not reset constitutional authority.
     * Archived elections are unlocked to allow org policy changes for future elections.
     * Unknown future states default to frozen (safe governance default).
     */
    public function isParticipationLocked(): bool
    {
        return match($this->state) {
            // Pre-setup: voter identity is not yet constitutionally relevant
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::SubmittedForApproval,
            ElectionLifecycleState::Approved,
            ElectionLifecycleState::Rejected => false,

            // Archival: election complete; org policy may change for future elections
            ElectionLifecycleState::Archived => false,

            // Active lifecycle (SetupAdministration → ResultsPublished): frozen
            // Overlay states (Suspended, future): preserve freeze
            //   — overlays are orthogonal to constitutional authority, not a reset
            // Unknown future states: frozen by default (safe governance default)
            default => true,
        };
    }
}
