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

            // ── Identity verification (PBDIGIT-213 D1) ──
            // Officer identity evidence may be recorded from administration through
            // active voting (late admission / re-admission is legitimate) — never in
            // Draft, approval, Suspended or any post-voting state. Explicit allow-list.
            'verify_voter_identity' => in_array($snapshot->state, [
                ElectionLifecycleState::SetupAdministration,
                ElectionLifecycleState::SetupNomination,
                ElectionLifecycleState::ReadyForVoting,
                ElectionLifecycleState::VotingActive,
            ], true),

            // ── Capture IP Address (PBDIGIT-222 evidence; window PBDIGIT-247 option (a), PO 2026-10-06) ──
            // Its own operation, deliberately not verify_voter_identity: IP evidence ≠ identity evidence.
            // Same span — officers capture / re-capture during the pre-vote check, also while voting is active.
            'capture_voter_ip' => in_array($snapshot->state, [
                ElectionLifecycleState::SetupAdministration,
                ElectionLifecycleState::SetupNomination,
                ElectionLifecycleState::ReadyForVoting,
                ElectionLifecycleState::VotingActive,
            ], true),

            // ── Candidacy Management ──
            // DESIGN DECISION: apply_candidacy requires explicit SetupNomination state
            // because the form itself is only valid during nomination window.
            // This is NOT a permission override — it enforces the constitutional timeline.
            'apply_candidacy' => $snapshot->state === ElectionLifecycleState::SetupNomination,
            'approve_candidacy' => $snapshot->canEdit,
            'view_candidates' => true, // Always readable — no security gate

            // ── Results & Publication ──
            // NOTE: results are viewable once the election has reached ResultsPublished
            // (or Archived) — NOT "!isLocked", which is an edit-lock concept that is
            // (correctly) true throughout ResultsPublished, making results unreachable
            // exactly when they should be viewable.
            'view_results' => in_array($snapshot->state, [
                ElectionLifecycleState::ResultsPublished,
                ElectionLifecycleState::Archived,
            ], true),
            'download_receipt' => in_array($snapshot->state, [
                ElectionLifecycleState::ResultsPublished,
                ElectionLifecycleState::Archived,
            ], true),

            // ── Ballot Preview ──
            // Read-only, interactive-but-non-submittable ballot view — reachable
            // from ReadyForVoting through VotingActive (isVotingPhase()).
            'preview_ballot' => $snapshot->state->isVotingPhase(),

            // ── Candidate Photo Editing ──
            // Committee-only (see ElectionPolicy::managePosts()) photo crop/replace,
            // allowed only before voting starts — denied from VotingActive onward
            // (and, since Suspended is absent from this allow-list, while suspended).
            'edit_candidate_photo' => in_array($snapshot->state, [
                ElectionLifecycleState::Draft,
                ElectionLifecycleState::SubmittedForApproval,
                ElectionLifecycleState::Approved,
                ElectionLifecycleState::SetupAdministration,
                ElectionLifecycleState::SetupNomination,
                ElectionLifecycleState::ReadyForVoting,
            ], true),

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

            'cast_vote' => $snapshot->denialReason ?? 'Voting is not currently active',

            'verify_voter_identity' => 'Voter identity can only be verified from administration until voting closes.',

            'capture_voter_ip' => 'A voter\'s IP address can only be captured from administration until voting closes.',

            'apply_candidacy' => 'Candidacy applications are not currently open',
            'approve_candidacy' => $snapshot->denialReason ?? 'Election must be in nomination phase',
            'view_candidates' => null,

            'view_results',
            'download_receipt' => 'Results are not yet available',

            // Deliberately generic — the preview link's audience is any org
            // member/officer, not just this election's registered voters, so
            // we don't leak lifecycle phase to them.
            'preview_ballot' => "This preview isn't available right now.",

            'edit_candidate_photo' => 'Candidate photos can only be edited before voting starts.',

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
