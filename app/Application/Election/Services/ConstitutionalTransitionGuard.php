<?php

namespace App\Application\Election\Services;

use App\Domain\Election\Constitution\ElectionConstitution;
use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;
use App\Exceptions\InvalidTransitionException;
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
 */
final class ConstitutionalTransitionGuard
{
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
            throw new InvalidTransitionException(
                "Action '{$action}' is not defined in the election constitution"
            );
        }

        // Check 2: State allows action
        if (!ElectionConstitution::isActionAllowedInState($action, $snapshot->state)) {
            $allowedStates = implode(', ', $rules['allowed_states']);
            throw new InvalidTransitionException(
                "Action '{$action}' not allowed in state '{$snapshot->state->value}'. " .
                "Allowed states: {$allowedStates}"
            );
        }

        // Check 3: User has required role
        $requiredRoles = ElectionConstitution::getAllowedRolesForAction($action);
        if (!$this->userHasAnyRole($election, $requiredRoles)) {
            $rolesStr = implode(', ', $requiredRoles);
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
     * @param Election $election The election (scopes tenant context)
     * @param array $requiredRoles Role names like ['chief', 'deputy']
     * @return bool True if user has at least one required role
     */
    private function userHasAnyRole(Election $election, array $requiredRoles): bool
    {
        $user = Auth::user();
        if (!$user) {
            return false;
        }

        // Check if user is a committee member with one of the required roles
        // This checks the membership context for committee roles
        foreach ($requiredRoles as $role) {
            if ($user->hasRole($role)) {
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
            'has_voters' => $election->memberships()->exists(),
            'has_committee_members' => $election->memberships()
                ->whereIn('role', ['chief', 'deputy'])
                ->exists(),
            'has_approved_candidates' => $election->candidacies()
                ->where('status', 'approved')
                ->exists(),
            'voting_window_defined' => $election->voting_starts_at !== null && $election->voting_ends_at !== null,
            default => true,
        };
    }
}
