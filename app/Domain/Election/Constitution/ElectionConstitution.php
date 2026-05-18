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
 * 2. Only committees (chief, deputy) can administer
 * 3. Only chief can open voting or publish results
 * 4. Each action has preconditions that must be verified
 */
final class ElectionConstitution
{
    public const RULES = [
        'submit_for_approval' => [
            'allowed_states' => ['draft'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
        ],
        'complete_administration' => [
            'allowed_states' => ['setup'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['has_posts', 'has_voters', 'has_committee_members'],
        ],
        'complete_nomination' => [
            'allowed_states' => ['setup'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['has_approved_candidates'],
        ],
        'open_voting' => [
            'allowed_states' => ['ready_for_voting'],
            'allowed_roles' => ['chief'],
            'preconditions' => ['voting_window_defined'],
        ],
        'close_voting' => [
            'allowed_states' => ['voting_active'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
        ],
        'publish_results' => [
            'allowed_states' => ['counting'],
            'allowed_roles' => ['chief'],
            'preconditions' => [],
        ],
        'archive' => [
            'allowed_states' => ['results_published'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
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
