<?php

namespace App\Http\Middleware;

use App\Domain\Election\Enum\ElectionLifecycleState;
use App\Domain\Election\ValueObjects\ElectionLifecycleSnapshot;

/**
 * OperationCapabilityMapper — Transitional mapping from middleware operations to capability checks
 *
 * PHASE C.2.5 STRATEGIC INTERMEDIATE:
 * Maps the deprecated "operation name" pattern to capability snapshot checks.
 * This mapper is TEMPORARY — it bridges the gap between middleware-level operation guards
 * and the resolver-based capability model.
 *
 * As the system evolves:
 * - Step 2 (NOW): Middleware uses mapper to ask resolver instead of legacy allowsAction()
 * - Step 3: Deprecation telemetry added to mapper
 * - Step 7: Mapper removed when middleware asks resolver directly
 *
 * CRITICAL: This mapper is READ-ONLY. It does NOT make authorization decisions — it queries
 * the capability snapshot. Authority decisions are made by the RESOLVER ONLY.
 */
final class OperationCapabilityMapper
{
    /**
     * Check if an operation is allowed based on election lifecycle snapshot
     *
     * @param string $operation Operation name (e.g., 'manage_posts', 'cast_vote')
     * @param ElectionLifecycleSnapshot $snapshot Pre-computed capability snapshot from resolver
     * @return bool True if operation is authorized by capability snapshot
     */
    public static function isOperationAllowed(string $operation, ElectionLifecycleSnapshot $snapshot): bool
    {
        return match ($operation) {
            // ── Election Setup & Configuration ──
            'manage_posts' => $snapshot->canEdit,
            'import_voters' => $snapshot->canManageVoters,
            'manage_committee' => $snapshot->canEdit,
            'configure_election' => $snapshot->canEdit,
            'manage_settings' => $snapshot->canEdit,

            // ── Voting Operations ──
            'cast_vote' => $snapshot->canVote,
            'verify_vote' => $snapshot->canVote || !$snapshot->isLocked,

            // ── Candidacy Management ──
            // DESIGN DECISION: apply_candidacy requires explicit SetupNomination state
            // because the form itself is only valid during nomination window.
            // This is NOT a permission override — it enforces the constitutional timeline.
            'apply_candidacy' => $snapshot->state === ElectionLifecycleState::SetupNomination,
            'approve_candidacy' => $snapshot->canEdit,
            'view_candidates' => true, // Always readable — no security gate

            // ── Results & Publication ──
            'view_results' => !$snapshot->isLocked,
            'download_receipt' => !$snapshot->isLocked,

            // ── Default: Undefined operations forbidden ──
            default => false,
        };
    }

    /**
     * Get human-readable denial reason for an operation
     *
     * PHASE C.2.5: This is a TEMPORARY method. As the system evolves, denial reasons
     * will come directly from the capability resolver, not from this mapper.
     *
     * @param string $operation Operation name
     * @param ElectionLifecycleSnapshot $snapshot Pre-computed capability snapshot
     * @return string|null Human-readable denial reason if operation is denied
     */
    public static function denialReasonForOperation(string $operation, ElectionLifecycleSnapshot $snapshot): ?string
    {
        if (self::isOperationAllowed($operation, $snapshot)) {
            return null;
        }

        return match ($operation) {
            'manage_posts',
            'import_voters',
            'manage_committee',
            'configure_election',
            'manage_settings' => $snapshot->denialReason ?? 'Election must be in setup phase',

            'cast_vote',
            'verify_vote' => $snapshot->denialReason ?? 'Voting is not currently active',

            'apply_candidacy' => 'Candidacy applications are not currently open',
            'approve_candidacy' => $snapshot->denialReason ?? 'Election must be in nomination phase',
            'view_candidates' => null,

            'view_results',
            'download_receipt' => 'Results are not yet available',

            default => 'Operation not permitted in current election state',
        };
    }

    /**
     * Get state-info display name for error messages
     *
     * @param ElectionLifecycleSnapshot $snapshot
     * @return array ['name' => string, 'description' => string]
     */
    public static function getStateInfo(ElectionLifecycleSnapshot $snapshot): array
    {
        $stateNames = [
            'draft' => 'Draft',
            'submitted_for_approval' => 'Pending Approval',
            'approved' => 'Approved',
            'setup_administration' => 'Administration',
            'setup_nomination' => 'Nomination',
            'ready_for_voting' => 'Ready for Voting',
            'voting_active' => 'Voting Active',
            'counting' => 'Counting',
            'results_published' => 'Results Published',
            'archived' => 'Archived',
            'rejected' => 'Rejected',
            'suspended' => 'Suspended',
        ];

        $stateName = $snapshot->state->value;
        return [
            'name' => $stateNames[$stateName] ?? $stateName,
            'description' => $snapshot->denialDetail ?? '',
        ];
    }
}
