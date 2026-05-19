<?php

namespace App\Domain\Election\Constitution;

use App\Domain\Election\Enum\ElectionLifecycleState;

/**
 * ElectionConstitution: Centralized Rules Registry
 *
 * Defines all allowed state transitions, required roles, and preconditions.
 * THE SINGLE SOURCE OF TRUTH for what actions are constitutionally allowed.
 *
 * **CRITICAL RULES:**
 * 1. All transitions defined here and NOWHERE ELSE
 * 2. Only committees (chief, deputy) can administer elections
 * 3. Only chief can open voting or publish results
 * 4. Each action has preconditions that must be verified
 * 5. Approval workflow: draft → submitted → approved/rejected → setup
 * 6. Capacity-based approval: free plan (≤40 voters) auto-approves, paid plan requires manual review
 */
final class ElectionConstitution
{
    public const RULES = [
        // ──── APPROVAL WORKFLOW (Platform + Committee) ────
        'submit_for_approval' => [
            'allowed_states' => ['draft'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['timezone_set'],
            'description' => 'Submit election for platform approval (free plan auto-approves)',
        ],
        'approve' => [
            'allowed_states' => ['submitted_for_approval'],
            'allowed_roles' => ['platform_admin'],
            'preconditions' => ['capacity_eligibility'],
            'description' => 'Platform admin approves election (auto if free plan)',
        ],
        'reject' => [
            'allowed_states' => ['submitted_for_approval'],
            'allowed_roles' => ['platform_admin'],
            'preconditions' => [],
            'description' => 'Platform admin rejects election approval with feedback',
        ],
        'begin_setup' => [
            'allowed_states' => ['approved'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'description' => 'Begin election setup (create posts, import voters)',
        ],
        'revise_and_resubmit' => [
            'allowed_states' => ['rejected'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'description' => 'Revise rejection feedback and resubmit for approval',
        ],

        // ──── SETUP WORKFLOW (Committee) ────
        'complete_administration' => [
            'allowed_states' => ['setup'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['has_posts', 'has_voters', 'has_committee_members'],
            'description' => 'Complete voter import and committee setup',
        ],
        'complete_nomination' => [
            'allowed_states' => ['setup'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['has_approved_candidates'],
            'description' => 'Complete candidate approval process',
        ],

        // ──── VOTING WORKFLOW (Chief Only) ────
        'open_voting' => [
            'allowed_states' => ['ready_for_voting'],
            'allowed_roles' => ['chief'],
            'preconditions' => ['voting_window_defined', 'timezone_set'],
            'description' => 'Open voting period (chief only)',
        ],
        'close_voting' => [
            'allowed_states' => ['voting_active'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'description' => 'Close voting period (begin counting)',
        ],

        // ──── RESULTS WORKFLOW (Chief Only) ────
        'publish_results' => [
            'allowed_states' => ['counting'],
            'allowed_roles' => ['chief'],
            'preconditions' => [],
            'description' => 'Publish election results to members',
        ],
        'archive' => [
            'allowed_states' => ['results_published'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'description' => 'Archive election (final state)',
        ],
    ];

    /**
     * Get the complete rule set for an action.
     *
     * @param string $action The action name (e.g., 'open_voting')
     * @return array The rule structure with allowed_states, allowed_roles, preconditions
     * @throws \InvalidArgumentException If action is not defined
     */
    public static function getRulesForAction(string $action): array
    {
        if (!isset(self::RULES[$action])) {
            throw new \InvalidArgumentException("Action '{$action}' is not defined in ElectionConstitution");
        }

        return self::RULES[$action];
    }

    /**
     * Check if an action is allowed in a given state.
     *
     * @param string $action The action name
     * @param ElectionLifecycleState $state The current election state
     * @return bool True if action is allowed in this state
     */
    public static function isActionAllowedInState(string $action, ElectionLifecycleState $state): bool
    {
        try {
            $rules = self::getRulesForAction($action);
        } catch (\InvalidArgumentException) {
            return false;
        }

        $allowedStates = $rules['allowed_states'];

        // Convert ElectionLifecycleState enum value to string
        return in_array($state->value, $allowedStates, true);
    }

    /**
     * Get the committee roles allowed to perform an action.
     *
     * @param string $action The action name
     * @return array List of allowed roles (e.g., ['chief', 'deputy'])
     */
    public static function getAllowedRolesForAction(string $action): array
    {
        $rules = self::getRulesForAction($action);

        return $rules['allowed_roles'];
    }

    /**
     * Get the preconditions that must be verified before allowing an action.
     *
     * @param string $action The action name
     * @return array List of precondition names (e.g., ['has_posts', 'has_voters'])
     */
    public static function getPreconditionsForAction(string $action): array
    {
        $rules = self::getRulesForAction($action);

        return $rules['preconditions'];
    }
}
