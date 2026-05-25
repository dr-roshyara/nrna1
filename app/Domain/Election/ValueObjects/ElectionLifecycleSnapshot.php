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

    public function canTransitionTo(string $action): bool
    {
        return in_array($action, $this->allowedActions, true);
    }

    public function isParticipationLocked(): bool
    {
        return match($this->state) {
            ElectionLifecycleState::Draft,
            ElectionLifecycleState::SubmittedForApproval,
            ElectionLifecycleState::Approved,
            ElectionLifecycleState::Rejected => false,
            default => true,
        };
    }
}
