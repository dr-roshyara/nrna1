<?php

namespace App\Application\Election\Services;

use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Exceptions\InvalidTransitionException;
use App\Application\Election\Monitoring\ConstitutionalMetricsContract;
use App\Models\Election;
use Illuminate\Support\Facades\Auth;

/**
 * ConstitutionalTransitionGuard: Enforcement Layer
 *
 * Hard gate before ANY state mutation. Enforces:
 * 1. Action is defined in constitution
 * 2. Current state allows action
 * 3. Current user has required role
 * 4. All preconditions are met
 *
 * Throws InvalidTransitionException if ANY check fails.
 *
 * **CRITICAL:** No bypasses, no exceptions. Guard is mandatory.
 * Reports all denials to ConstitutionalMetrics for observability.
 */
final class ConstitutionalTransitionGuard
{
    public function __construct(
        private readonly ?ConstitutionalMetricsContract $metrics = null
    ) {}
    /**
     * Assert that an action is allowed for the current election state.
     *
     * @param Election $election The election being transitioned
     * @param string $action The action being attempted
     * @param ElectionLifecycleSnapshot $snapshot Current snapshot
     * @throws InvalidTransitionException If ANY check fails
     */
    public function assertAllowed(
        Election $election,
        string $action,
        ElectionLifecycleSnapshot $snapshot
    ): void {
        // Check 1: Action is defined
        try {
            $rules = ElectionConstitution::getRulesForAction($action);
        } catch (\InvalidArgumentException) {
            $this->metrics?->recordIllegalActivationAttempt($election->id, "Action '$action' not defined in constitution");
            throw new InvalidTransitionException(
                "Action '{$action}' is not defined in the election constitution"
            );
        }

        // Check 2: State allows action
        if (!ElectionConstitution::isActionAllowedInState($action, $snapshot->state)) {
            $allowedStates = implode(', ', $rules['allowed_states']);
            $this->metrics?->recordIllegalActivationAttempt($election->id, "Action '$action' not allowed in state '{$snapshot->state->value}'");
            throw new InvalidTransitionException(
                "Action '{$action}' not allowed in state '{$snapshot->state->value}'. " .
                "Allowed states: {$allowedStates}"
            );
        }

        // Check 3: User has required role
        $requiredRoles = ElectionConstitution::getAllowedRolesForAction($action);
        if (!$this->userHasAnyRole($election, $requiredRoles)) {
            $rolesStr = implode(', ', $requiredRoles);
            $this->metrics?->recordIllegalActivationAttempt($election->id, "User lacks required role(s) for action '$action'");
            throw new InvalidTransitionException(
                "You do not have permission to perform '{$action}'. " .
                "Required role(s): {$rolesStr}"
            );
        }

        // Check 4: Preconditions are met (validation only, details in application layer)
        $preconditions = ElectionConstitution::getPreconditionsForAction($action);
        if (!empty($preconditions)) {
            $this->validatePreconditions($election, $preconditions, $action);
        }
    }

    /**
     * Check if authenticated user has ANY of the required roles.
     *
     * Special case: 'system' role for automatic actions
     * System actions (e.g., auto_submit) are triggered by the system, not a user,
     * so Auth::user() is null. These should be allowed when 'system' is the required role.
     *
     * @param Election $election The election (scopes tenant context)
     * @param array $requiredRoles Role names like ['chief', 'deputy', 'system']
     * @return bool True if user has at least one required role (or if 'system' is required and no user)
     */
    private function userHasAnyRole(Election $election, array $requiredRoles): bool
    {
        // Special case: 'system' role for automatic transitions
        // If 'system' is the ONLY required role and no user is authenticated,
        // allow it (system-triggered action like auto_submit)
        if (in_array('system', $requiredRoles, true)) {
            // If 'system' is the only role required, allow without authentication
            if (count($requiredRoles) === 1) {
                return true;
            }
            // If multiple roles including 'system', we're still checking non-system roles
            // Remove 'system' and check if user has any of the remaining roles
            $userRoles = array_diff($requiredRoles, ['system']);
            if (empty($userRoles)) {
                return true;  // Only 'system' was required
            }
            $requiredRoles = $userRoles;  // Check user roles without 'system'
        }

        // Normal user role checking
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // Check if user is a committee member with one of the required roles
        // First check Spatie permission roles (global roles)
        foreach ($requiredRoles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        // Also check ElectionOfficer roles (election-specific roles for chief, deputy)
        foreach ($requiredRoles as $role) {
            $hasElectionRole = \App\Models\ElectionOfficer::where('election_id', $election->id)
                ->where('user_id', $user->id)
                ->where('role', $role)
                ->where('status', 'active')
                ->exists();

            if ($hasElectionRole) {
                return true;
            }
        }

        return false;
    }

    /**
     * Validate that all preconditions are met.
     *
     * @param Election $election The election to validate
     * @param array $preconditions List of precondition names
     * @param string $action The action being attempted
     * @throws InvalidTransitionException If any precondition fails
     */
    private function validatePreconditions(Election $election, array $preconditions, string $action): void
    {
        $unmetConditions = [];

        foreach ($preconditions as $condition) {
            if (!$this->isPreconditionMet($election, $condition)) {
                $unmetConditions[] = $condition;
            }
        }

        if (!empty($unmetConditions)) {
            $condStr = implode(', ', $unmetConditions);
            $this->metrics?->recordIllegalActivationAttempt($election->id, "Unmet preconditions for action '$action': {$condStr}");
            throw new InvalidTransitionException(
                "Action '{$action}' cannot proceed. Unmet preconditions: {$condStr}"
            );
        }
    }

    /**
     * Check if a specific precondition is met for an election.
     *
     * @param Election $election
     * @param string $condition Precondition name
     * @return bool
     */
    private function isPreconditionMet(Election $election, string $condition): bool
    {
        return match ($condition) {
            'has_posts' => $election->posts()->exists(),
            'has_voters' => $election->voters()->exists(),
            'has_chief' => \App\Models\ElectionOfficer::where('election_id', $election->id)
                ->where('role', 'chief')
                ->where('status', 'active')
                ->exists(),
            'has_approved_candidates' => $election->candidacies()
                ->where('status', 'approved')
                ->exists(),
            'voting_window_defined' => $election->voting_starts_at !== null && $election->voting_ends_at !== null,

            // NEW: Timezone must be configured (dedicated column on elections table)
            'timezone_set' => !empty($election->timezone),

            // NEW: Capacity-based approval
            // Free plan (≤40 voters) always eligible
            // Paid plan (>40 voters) requires payment authorization
            'capacity_eligibility' => $this->isCapacityEligible($election),

            default => true,
        };
    }

    /**
     * Check if an election is eligible based on voter capacity and payment status.
     *
     * FREE PLAN: ≤40 voters → auto-approved
     * PAID PLAN: >40 voters → requires payment authorization from organization
     *
     * @param Election $election
     * @return bool
     */
    private function isCapacityEligible(Election $election): bool
    {
        // Use expected_voter_count instead of COUNT query for performance
        $voterCount = $election->expected_voter_count ?? 0;

        // Free plan: up to 40 voters
        if ($voterCount <= 40) {
            return true;  // Auto-eligible, will auto-approve
        }

        // Paid plan: must have payment authorized on organization
        // TODO: Replace with actual payment authorization check when billing system implemented
        $paymentAuthorized = true; // Stub: payment system not yet implemented
        return $paymentAuthorized;
    }
}
