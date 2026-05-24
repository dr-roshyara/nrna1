<?php

namespace Tests\Support;

/**
 * Canonical mapping from old (pre-SSOT) state names to new (constitutional) state names.
 *
 * This is the translation layer for test vocabulary alignment during strangler migration.
 * Tests use these mappings to convert from old workflow language to new constitutional facts.
 */
final class ElectionTestMapping
{
    /**
     * Old state name → New ElectionLifecycleState
     *
     * These are the semantically equivalent states in the new system.
     */
    public const OLD_TO_NEW_STATE = [
        // Old workflow states → New constitutional states
        'draft' => 'draft',
        'pending_approval' => 'submitted_for_approval',
        'approved' => 'approved',
        'administration' => 'setup',
        'nomination' => 'setup',  // merged into setup phase
        'voting' => 'voting_active',
        'results_pending' => 'counting',
        'results' => 'results_published',

        // Aliases for compatibility
        'active' => 'voting_active',
        'setup' => 'setup',
        'ready_for_voting' => 'ready_for_voting',
        'counting' => 'counting',
    ];

    /**
     * Old phase names → New state names
     *
     * Used in timeline/phase tests where specific phases are being validated.
     */
    public const OLD_PHASE_TO_STATE = [
        'administration' => 'setup',
        'nomination' => 'setup',
        'voting' => 'voting_active',
        'results' => 'results_published',
    ];

    /**
     * Old action names → New constitutional actions
     *
     * Maps deprecated action names to their new constitutional equivalents.
     */
    public const OLD_TO_NEW_ACTION = [
        'configure_election' => 'begin_setup',
        'import_voters' => 'begin_setup',
        'manage_posts' => 'begin_setup',
        'open_voting' => 'open_voting',
        'close_voting' => 'close_voting',
        'publish_results' => 'publish_results',
        'submit_for_approval' => 'submit_for_approval',
        'approve' => 'approve',
        'reject' => 'reject',
        'complete_administration' => 'begin_setup',
    ];

    /**
     * Translate old state name to new state name
     */
    public static function translateState(string $oldState): string
    {
        return self::OLD_TO_NEW_STATE[$oldState] ?? $oldState;
    }

    /**
     * Translate old phase name to new state name
     */
    public static function translatePhase(string $oldPhase): string
    {
        return self::OLD_PHASE_TO_STATE[$oldPhase] ?? $oldPhase;
    }

    /**
     * Translate old action name to new action name
     */
    public static function translateAction(string $oldAction): string
    {
        return self::OLD_TO_NEW_ACTION[$oldAction] ?? $oldAction;
    }

    /**
     * Get all old state names (for search/replace verification)
     */
    public static function oldStateNames(): array
    {
        return array_keys(self::OLD_TO_NEW_STATE);
    }

    /**
     * Get all new state names
     */
    public static function newStateNames(): array
    {
        return array_values(self::OLD_TO_NEW_STATE);
    }
}
