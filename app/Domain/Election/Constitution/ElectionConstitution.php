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
            'target_state' => 'submitted_for_approval',
            'description' => 'Submit election for platform approval (free plan auto-approves)',
        ],
        'approve' => [
            'allowed_states' => ['submitted_for_approval'],
            'allowed_roles' => ['platform_admin'],
            'preconditions' => ['capacity_eligibility'],
            'target_state' => 'approved',
            'description' => 'Platform admin approves election (auto if free plan)',
        ],
        'reject' => [
            'allowed_states' => ['submitted_for_approval'],
            'allowed_roles' => ['platform_admin'],
            'preconditions' => [],
            'target_state' => 'rejected',
            'description' => 'Platform admin rejects election approval with feedback',
        ],
        'auto_submit' => [
            'allowed_states' => ['draft'],
            'allowed_roles' => ['system'],
            'preconditions' => ['capacity_eligibility'],
            'target_state' => 'approved',
            'description' => 'System auto-approves election (free plan ≤40 voters, skips manual review)',
        ],
        'begin_setup' => [
            'allowed_states' => ['approved'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'target_state' => 'setup_administration',
            'description' => 'Begin election setup (create posts, import voters)',
        ],
        'revise_and_resubmit' => [
            'allowed_states' => ['rejected'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'target_state' => 'submitted_for_approval',
            'description' => 'Revise rejection feedback and resubmit for approval',
        ],

        // ──── SETUP WORKFLOW (Committee) ────
        'complete_administration' => [
            'allowed_states' => ['setup_administration'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['has_posts', 'has_voters', 'has_chief'],
            'target_state' => 'setup_nomination',
            'description' => 'Complete voter import and committee setup',
        ],
        'complete_nomination' => [
            'allowed_states' => ['setup_nomination'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => ['has_approved_candidates'],
            'target_state' => 'setup_nomination',
            'description' => 'Complete candidate approval process',
        ],

        // ──── CANDIDACY APPLICATIONS (Members) ────
        'apply_candidacy' => [
            'allowed_states' => ['setup_nomination'],
            'allowed_roles' => ['voter', 'member'],
            'preconditions' => [],
            'target_state' => 'setup_nomination',  // no state change; capability check only
            'description' => 'Apply for candidacy during the nomination phase',
        ],

        // ──── VOTING WORKFLOW (Chief Only) ────
        'open_voting' => [
            'allowed_states' => ['setup_nomination', 'ready_for_voting'],
            'allowed_roles' => ['chief'],
            // EM-VOT-002 (Election Manifesto §4a): open_voting must not succeed
            // with zero approved candidates (SD-14 = YES).
            'preconditions' => ['voting_window_defined', 'timezone_set', 'has_approved_candidates'],
            'target_state' => 'voting_active',
            'description' => 'Open voting period (chief only)',
        ],
        'close_voting' => [
            'allowed_states' => ['voting_active'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'target_state' => 'counting',
            'description' => 'Close voting period (begin counting)',
        ],

        // ──── RESULTS WORKFLOW (Chief Only) ────
        'publish_results' => [
            'allowed_states' => ['counting'],
            'allowed_roles' => ['chief'],
            'preconditions' => [],
            'target_state' => 'results_published',
            'description' => 'Publish election results to members',
        ],
        'archive' => [
            'allowed_states' => ['results_published'],
            'allowed_roles' => ['chief', 'deputy'],
            'preconditions' => [],
            'target_state' => 'archived',
            'description' => 'Archive election (final state)',
        ],

        // ──── OPERATIONAL GOVERNANCE OVERLAY (Platform Admin) ────
        // ARCHITECTURAL PRINCIPLE: Suspension is an operational governance overlay,
        // orthogonal to lifecycle progression. It freezes capabilities only.
        // Does NOT mutate business facts or advance the election through phases.
        'suspend' => [
            'allowed_states' => [
                'draft', 'submitted_for_approval', 'approved', 'rejected',
                'setup_administration', 'setup_nomination',
                'ready_for_voting', 'voting_active', 'counting', 'results_published',
            ],
            'allowed_roles' => ['chief', 'platform_admin'],
            'preconditions' => [],
            'target_state' => 'suspended',
            'description' => 'Suspend election for governance hold (fraud/legal/operational)',
        ],
        'resume' => [
            'allowed_states' => ['suspended'],
            'allowed_roles' => ['chief', 'platform_admin'],
            'preconditions' => [],
            'target_state' => 'suspended',  // placeholder; side effects clear flags, engine re-derives
            'description' => 'Resume suspended election — engine re-derives state from constitutional facts',
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

    /**
     * Get the resulting state after an action completes.
     * This is the SSOT for what state an action produces.
     *
     * @param string $action The action name
     * @return string The target state string (e.g., 'setup', 'voting_active')
     * @throws \InvalidArgumentException If action is not defined
     */
    public static function getTargetStateForAction(string $action): string
    {
        $rules = self::getRulesForAction($action);
        return $rules['target_state'];
    }

    /**
     * Get all valid target states from the constitution.
     * Used to identify which state column values are explicitly set by transitions.
     *
     * @return array List of all possible target state strings
     */
    public static function getValidTargetStates(): array
    {
        return array_unique(array_column(self::RULES, 'target_state'));
    }
}
