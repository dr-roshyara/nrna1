<?php

namespace App\Domain\Election\StateMachine;

class TransitionMatrix
{
    // Single source of truth: all transitions, their target states, and required roles
    public const TRANSITIONS = [
        'draft' => [
            'submit_for_approval' => ['to' => 'pending_approval', 'roles' => ['chief']],
            'auto_submit'         => ['to' => 'administration',   'roles' => ['system']],
        ],
        'pending_approval' => [
            'approve' => ['to' => 'administration', 'roles' => ['super_admin', 'platform_admin']],
            'reject'  => ['to' => 'draft',          'roles' => ['super_admin', 'platform_admin']],
        ],
        'administration' => [
            'complete_administration' => ['to' => 'nomination', 'roles' => ['chief', 'deputy']],
        ],
        'nomination' => [
            'open_voting' => ['to' => 'voting', 'roles' => ['chief', 'deputy']],
        ],
        'voting' => [
            'close_voting' => ['to' => 'results_pending', 'roles' => ['chief', 'deputy']],
            'lock_voting'  => ['to' => 'voting',          'roles' => ['chief', 'deputy']],
        ],
        'results_pending' => [
            'publish_results' => ['to' => 'results', 'roles' => ['chief']],
        ],
        'results' => [],
    ];

    public static function canPerformAction(string $fromState, string $action): bool
    {
        return isset(self::TRANSITIONS[$fromState][$action]);
    }

    public static function getResultingState(string $action): string
    {
        foreach (self::TRANSITIONS as $actions) {
            if (isset($actions[$action])) {
                return $actions[$action]['to'];
            }
        }
        throw new \InvalidArgumentException("Unknown action: '{$action}'");
    }

    public static function getAllowedActions(string $state): array
    {
        return array_keys(self::TRANSITIONS[$state] ?? []);
    }

    public static function getAllowedRoles(string $action): array
    {
        foreach (self::TRANSITIONS as $actions) {
            if (isset($actions[$action])) {
                return $actions[$action]['roles'];
            }
        }
        return [];
    }

    public static function actionRequiresRole(string $action, string $role): bool
    {
        $allowed = self::getAllowedRoles($action);
        // 'system' bypasses — always allowed (for automated transitions)
        if ($role === 'system') {
            return true;
        }
        return in_array($role, $allowed, strict: true);
    }

    public static function isValidState(string $state): bool
    {
        return array_key_exists($state, self::TRANSITIONS);
    }

    public static function getAllStates(): array
    {
        return array_keys(self::TRANSITIONS);
    }

    // ─── BACKWARD COMPATIBILITY METHODS ───────────────────────────────────────
    // These methods provide compatibility with code that still expects the old
    // state-to-state API. They work by checking if any action from $fromState
    // results in $toState.

    public static function canTransition(string $fromState, string $toState): bool
    {
        $allowedActions = self::TRANSITIONS[$fromState] ?? [];
        foreach ($allowedActions as $config) {
            if ($config['to'] === $toState) {
                return true;
            }
        }
        return false;
    }

    public static function getAllowedTransitions(string $state): array
    {
        $allowedActions = self::TRANSITIONS[$state] ?? [];
        $transitions = [];
        foreach ($allowedActions as $config) {
            $transitions[] = $config['to'];
        }
        return array_unique($transitions);
    }

    // ─── INVARIANT VALIDATION ─────────────────────────────────────────────────
    // Called at boot time to detect configuration inconsistencies immediately

    public static function validate(): void
    {
        foreach (self::TRANSITIONS as $state => $actions) {
            if (!\App\Domain\Election\Enum\ElectionState::tryFrom($state)) {
                throw new \LogicException("Invalid state in TRANSITIONS: '{$state}'");
            }

            foreach ($actions as $action => $config) {
                if (!\App\Domain\Election\Enum\ElectionAction::tryFrom($action)) {
                    throw new \LogicException("Invalid action in TRANSITIONS: '{$action}'");
                }

                if (!isset($config['to'], $config['roles'])) {
                    throw new \LogicException("Action '{$action}' missing 'to' or 'roles'");
                }

                if (!\App\Domain\Election\Enum\ElectionState::tryFrom($config['to'])) {
                    throw new \LogicException("Invalid target state '{$config['to']}'");
                }

                foreach ($config['roles'] as $role) {
                    if (!\App\Domain\Election\Enum\ElectionRole::tryFrom($role)) {
                        throw new \LogicException("Invalid role '{$role}' in action '{$action}'");
                    }
                }
            }
        }
    }
}
