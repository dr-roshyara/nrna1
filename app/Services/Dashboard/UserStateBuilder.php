<?php

namespace App\Services\Dashboard;

use App\DataTransferObjects\UserStateData;
use App\Models\User;

/**
 * Factory for building UserStateData
 * Orchestrates all services and optimizes database queries
 */
class UserStateBuilder
{
    public function __construct(
        private RoleDetectionService $roleDetection,
        private ConfidenceCalculator $confidenceCalculator,
        private OnboardingTracker $onboardingTracker,
        private ActionService $actionService,
    ) {
    }

    /**
     * Build user state data with optimized eager loading
     *
     * @param User $user
     * @return UserStateData
     */
    public function build(User $user): UserStateData
    {
        $startTime = microtime(true);
        \Log::info('=== UserStateBuilder::build START ===');

        // Eager load all relationships at once to prevent N+1 queries
        $start = microtime(true);
        $user = $this->eagerLoadUserData($user);
        \Log::info('1. eagerLoadUserData: ' . round((microtime(true) - $start) * 1000, 2) . 'ms');

        // Build state using injected services
        $start = microtime(true);
        $roles = $this->roleDetection->getDashboardRoles($user);
        \Log::info('2. getDashboardRoles: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Roles: ' . json_encode($roles));

        $start = microtime(true);
        $primaryRole = $this->roleDetection->getPrimaryRole($user);
        \Log::info('3. getPrimaryRole: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | PrimaryRole: ' . $primaryRole);

        $start = microtime(true);
        $compositeState = $this->roleDetection->detectCompositeState($user);
        \Log::info('4. detectCompositeState: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | State: ' . $compositeState);

        $start = microtime(true);
        $confidenceScore = $this->confidenceCalculator->calculate($user);
        \Log::info('5. confidenceCalculator->calculate: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Score: ' . $confidenceScore);

        $start = microtime(true);
        $onboardingStep = $this->onboardingTracker->getNextStep($user);
        \Log::info('6. onboardingTracker->getNextStep: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Step: ' . $onboardingStep);

        $start = microtime(true);
        $availableActions = $this->actionService->getAvailableActions($user, $compositeState);
        \Log::info('7. actionService->getAvailableActions: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Actions: ' . count($availableActions));

        $start = microtime(true);
        $primaryAction = $this->actionService->getPrimaryAction($compositeState);
        \Log::info('8. actionService->getPrimaryAction: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Action: ' . $primaryAction);

        $start = microtime(true);
        $uiMode = $this->confidenceCalculator->getUIMode($confidenceScore);
        \Log::info('9. confidenceCalculator->getUIMode: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Mode: ' . $uiMode);

        // Get pending actions
        $start = microtime(true);
        $pendingActions = $this->getPendingActions($user, $onboardingStep);
        \Log::info('10. getPendingActions: ' . round((microtime(true) - $start) * 1000, 2) . 'ms | Pending: ' . count($pendingActions));

        $start = microtime(true);
        $rolesArray = $roles->toArray();
        \Log::info('11. roles->toArray(): ' . round((microtime(true) - $start) * 1000, 2) . 'ms');

        $start = microtime(true);
        $userStateData = new UserStateData(
            composite_state: $compositeState,
            roles: $rolesArray,
            primary_role: $primaryRole,
            confidence_score: $confidenceScore,
            onboarding_step: $onboardingStep,
            available_actions: $availableActions,
            pending_actions: $pendingActions,
            primary_action: $primaryAction,
            ui_mode: $uiMode,
        );
        \Log::info('12. new UserStateData(): ' . round((microtime(true) - $start) * 1000, 2) . 'ms');

        $totalTime = round((microtime(true) - $startTime) * 1000, 2);
        \Log::info('=== UserStateBuilder::build COMPLETE in ' . $totalTime . 'ms ===');

        return $userStateData;
    }

    /**
     * Eager load all user relationships at once
     * This prevents N+1 query problems
     *
     * KISS Principle: Only load relationships that exist
     * Relationships available on User model:
     * - organizationRoles() - User's organizations with role pivot data
     * - roles() - User's assigned roles
     *
     * @param User $user
     * @return User
     */
    private function eagerLoadUserData(User $user): User
    {
        // Start with a basic query - only load relationships that ACTUALLY exist
        // This prevents RelationNotFoundException and infinite loops
        $query = User::query();

        // Build relationships array with existence checks
        $relationships = [];

        // Check and add relationships one by one
        if (method_exists(User::class, 'organizationRoles')) {
            $relationships[] = 'organizationRoles';
        }

        if (method_exists(User::class, 'organizations')) {
            $relationships[] = 'organizations';
        }

        if (method_exists(User::class, 'commissions')) {
            $relationships[] = 'commissions';
        }

        if (method_exists(User::class, 'roles')) {
            $relationships[] = 'roles:id,name';
        }

        // Load relationships if any exist
        if (!empty($relationships)) {
            $query->with($relationships);
        }

        // Get the user with safe eager loading
        return $query->find($user->id);
    }

    /**
     * Get pending actions for user
     *
     * @param User $user
     * @param int $onboardingStep
     * @return array
     */
    private function getPendingActions(User $user, int $onboardingStep): array
    {
        $actions = [];

        // 1. Onboarding actions (highest priority)
        if ($onboardingStep < 5) {
            $onboardingDetails = $this->onboardingTracker->getStepDetails($onboardingStep);
            $actions[] = [
                'type' => 'onboarding_step',
                'step' => $onboardingStep,
                'title' => $onboardingDetails['title'],
                'action' => $onboardingDetails['primary_action'],
                'priority' => 1,
            ];
        }

        // 2. Pending votes (for voters)
        if (isset($user->organizations)) {
            // User is an admin - no pending votes
        } else {
            // Check for pending votes (voter role)
            $pendingVoteCount = 0;
            // This would be counted from the database, simplified here
            if ($pendingVoteCount > 0) {
                $actions[] = [
                    'type' => 'pending_votes',
                    'count' => $pendingVoteCount,
                    'priority' => 2,
                ];
            }
        }

        // 3. GDPR consent (if needed)
        if (!$user->gdpr_consent_accepted_at) {
            $actions[] = [
                'type' => 'gdpr_consent',
                'priority' => 3,
            ];
        }

        // 4. Email verification (if needed)
        if (!$user->email_verified_at) {
            $actions[] = [
                'type' => 'email_verification',
                'priority' => 4,
            ];
        }

        // Sort by priority
        usort($actions, fn($a, $b) => $a['priority'] <=> $b['priority']);

        return $actions;
    }
}
