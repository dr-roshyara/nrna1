<?php

namespace App\Domain\Election\StateMachine;

class TransitionMatrix
{
    // State → allowed actions (what can be triggered from each state)
    public const ALLOWED_ACTIONS = [
        'draft'            => ['submit_for_approval', 'auto_submit'],
        'pending_approval' => ['approve', 'reject'],
        'administration'   => ['complete_administration'],
        'nomination'       => ['open_voting'],
        'voting'           => ['close_voting', 'lock_voting'],
        'results_pending'  => ['publish_results'],
        'results'          => [],
    ];

    // Action → resulting state (pure lookup, no logic)
    public const ACTION_RESULTS = [
        'submit_for_approval'     => 'pending_approval',
        'auto_submit'             => 'administration',
        'approve'                 => 'administration',
        'reject'                  => 'draft',
        'complete_administration' => 'nomination',
        'open_voting'             => 'voting',
        'lock_voting'             => 'voting',
        'close_voting'            => 'results_pending',
        'publish_results'         => 'results',
    ];

    // Action → allowed roles (which roles can perform each action)
    public const ACTION_PERMISSIONS = [
        'submit_for_approval'     => ['chief'],
        'auto_submit'             => ['system'],
        'approve'                 => ['super_admin', 'platform_admin'],
        'reject'                  => ['super_admin', 'platform_admin'],
        'complete_administration' => ['chief', 'deputy'],
        'open_voting'             => ['chief', 'deputy'],
        'lock_voting'             => ['chief', 'deputy'],
        'close_voting'            => ['chief', 'deputy'],
        'publish_results'         => ['chief'],
    ];

    public static function canPerformAction(string $fromState, string $action): bool
    {
        return in_array($action, self::ALLOWED_ACTIONS[$fromState] ?? []);
    }

    public static function getResultingState(string $action): string
    {
        if (!array_key_exists($action, self::ACTION_RESULTS)) {
            throw new \InvalidArgumentException("Unknown action: '{$action}'");
        }
        return self::ACTION_RESULTS[$action];
    }

    public static function getAllowedActions(string $state): array
    {
        return self::ALLOWED_ACTIONS[$state] ?? [];
    }

    public static function getAllowedRoles(string $action): array
    {
        return self::ACTION_PERMISSIONS[$action] ?? [];
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
        return array_key_exists($state, self::ALLOWED_ACTIONS);
    }

    public static function getAllStates(): array
    {
        return array_keys(self::ALLOWED_ACTIONS);
    }

    // ─── BACKWARD COMPATIBILITY METHODS ───────────────────────────────────────
    // These methods provide compatibility with code that still expects the old
    // state-to-state API. They work by checking if any action from $fromState
    // results in $toState.

    public static function canTransition(string $fromState, string $toState): bool
    {
        $allowedActions = self::ALLOWED_ACTIONS[$fromState] ?? [];
        foreach ($allowedActions as $action) {
            if (self::ACTION_RESULTS[$action] === $toState) {
                return true;
            }
        }
        return false;
    }

    public static function getAllowedTransitions(string $state): array
    {
        $allowedActions = self::ALLOWED_ACTIONS[$state] ?? [];
        $transitions = [];
        foreach ($allowedActions as $action) {
            $transitions[] = self::ACTION_RESULTS[$action] ?? null;
        }
        return array_filter($transitions);
    }
}
