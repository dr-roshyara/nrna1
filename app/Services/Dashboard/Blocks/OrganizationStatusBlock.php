<?php

namespace App\Services\Dashboard\Blocks;

use App\DataTransferObjects\UserStateData;
use App\Models\User;
use App\Services\Dashboard\OnboardingTracker;

/**
 * Content block for organization setup progress
 * Shows only if user is admin with organization
 * Uses OnboardingTracker to determine progress and next steps
 */
class OrganizationStatusBlock extends BaseContentBlock
{
    public function __construct(
        private ?OnboardingTracker $onboardingTracker = null,
    ) {
        if (!$this->onboardingTracker) {
            $this->onboardingTracker = app(OnboardingTracker::class);
        }
    }

    public function id(): string
    {
        return 'organization_status';
    }

    public function name(): string
    {
        return 'Organization Status';
    }

    public function priority(): int
    {
        return 20; // Second priority - after action cards
    }

    /**
     * Show if user has admin role AND is past new user stage
     * (i.e., has started but not completed organization setup)
     */
    public function shouldRender(UserStateData $userState): bool
    {
        // Show if admin and organization setup is in progress (steps 2-4)
        return in_array('admin', $userState->roles)
            && $userState->onboarding_step >= 2
            && $userState->onboarding_step < 5;
    }

    /**
     * Render organization status with progress
     */
    public function render(UserStateData $userState): array
    {
        $step = $userState->onboarding_step;
        $stepDetails = $this->onboardingTracker->getStepDetails($step);

        return [
            'type' => 'organization_status',
            'card_title' => 'Organisationseinrichtung',
            'step' => $step,
            'step_title' => $stepDetails['title'],
            'step_description' => $stepDetails['description'],
            'progress_percentage' => $stepDetails['progress'],
            'setup_status' => $this->getSetupStatus($step),
            'primary_action' => $stepDetails['primary_action'],
            'checklist' => $this->getSetupChecklist($step),
            'cta_button' => [
                'label' => 'Zur Organisation',
                'action' => 'view_organization',
            ],
        ];
    }

    /**
     * Get setup status description
     */
    private function getSetupStatus(int $step): string
    {
        return match ($step) {
            2 => 'setup_incomplete',
            3 => 'members_needed',
            4 => 'election_needed',
            default => 'unknown',
        };
    }

    /**
     * Get setup checklist items based on current step
     */
    private function getSetupChecklist(int $step): array
    {
        return match ($step) {
            2 => [
                ['title' => 'Organisation erstellt', 'completed' => true],
                ['title' => 'Erste Mitglieder einladen', 'completed' => false],
                ['title' => 'Wahl erstellen', 'completed' => false],
                ['title' => 'Wähler einladen', 'completed' => false],
            ],
            3 => [
                ['title' => 'Organisation erstellt', 'completed' => true],
                ['title' => 'Mitglieder hinzugefügt', 'completed' => true],
                ['title' => 'Erste Wahl erstellen', 'completed' => false],
                ['title' => 'Wähler einladen', 'completed' => false],
            ],
            4 => [
                ['title' => 'Organisation erstellt', 'completed' => true],
                ['title' => 'Mitglieder hinzugefügt', 'completed' => true],
                ['title' => 'Wahl erstellt', 'completed' => true],
                ['title' => 'Wähler einladen', 'completed' => false],
            ],
            default => [],
        };
    }
}
