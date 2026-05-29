import { computed, type ComputedRef } from 'vue'
import { ElectionActions, type ElectionAction } from '@/Constants/ElectionActions'

/**
 * Single capability entry returned by the resolver
 * Structure: { allowed: boolean, denial_reason: string | null, denial_detail: string | null }
 */
export interface CapabilityEntry {
  allowed: boolean
  denial_reason: string | null
  denial_detail: string | null
}

/**
 * The full stateMachine shape passed from the server via Inertia props
 * Includes both the new capabilities object and backward-compat allowedActions array
 */
export interface StateMachineWithCapabilities {
  currentState: string
  /** @deprecated Use capabilities[action].allowed instead */
  allowedActions: string[]
  completedStates?: string[]
  capabilities?: Record<string, CapabilityEntry>
  capabilities_metadata?: {
    resolver_version: string
    generated_at: string
    constitution_hash: string
  }
  capabilities_trace?: unknown[] | null
}

/**
 * useElectionCapabilities - Frontend anti-corruption layer for constitutional authority
 *
 * This composable is the ONLY access point for capability checks in Vue components.
 * It ensures:
 * - Frontend never reads raw capabilities structure
 * - Governance semantics are interpreted once (here)
 * - Constitutional vocabulary stays centralized
 * - Runtime truth ownership remains in the backend
 *
 * INVARIANT: This composable reads only. It never infers, augments, or interprets
 * capability decisions. It is a pure adapter from the backend capability snapshot
 * to frontend permission predicates.
 *
 * Usage:
 *   const capabilities = useElectionCapabilities(computed(() => props.stateMachine))
 *   if (capabilities.canDo(ElectionActions.OPEN_VOTING)) { ... }
 */
export function useElectionCapabilities(
  stateMachine: ComputedRef<StateMachineWithCapabilities | null | undefined>
) {
  /**
   * Check if the given action is allowed
   * Returns false if:
   * - stateMachine is null/undefined
   * - capability entry doesn't exist
   * - allowed field is false
   */
  const canDo = (action: ElectionAction): boolean => {
    return stateMachine.value?.capabilities?.[action]?.allowed ?? false
  }

  /**
   * Get the denial reason for a capability
   * Returns null if:
   * - stateMachine is null/undefined
   * - capability entry doesn't exist
   * - the action is allowed (reason is null)
   */
  const denialReason = (action: ElectionAction): string | null => {
    return stateMachine.value?.capabilities?.[action]?.denial_reason ?? null
  }

  const DENIAL_LABELS: Record<string, string> = {
    suspended: 'Election is currently suspended',
    missing_role: 'Insufficient privileges for this action',
    invalid_lifecycle: 'Not available in the current election state',
    unmet_precondition: 'Prerequisites have not been met',
    trust_denied: 'Identity verification required',
    constitutional_review_pending: 'Constitutional review is pending',
    trust_evaluation_inconclusive: 'Trust cannot be established',
    unauthenticated: 'Authentication required',
  }

  /**
   * Get a human-readable label for a capability denial reason.
   * Maps CapabilityDenialReason enum values to UI-safe labels.
   * Returns null if the action is allowed or no reason is available.
   */
  const denialLabel = (action: ElectionAction): string | null => {
    const reason = denialReason(action)
    if (!reason) return null
    return DENIAL_LABELS[reason] ?? reason
  }

  /**
   * Check if the given action is denied (opposite of canDo)
   * More semantic for conditional rendering:
   *   v-if="isDenied(action)" vs v-if="!canDo(action)"
   */
  const isDenied = (action: ElectionAction): boolean => {
    return !canDo(action)
  }

  /**
   * Emit deprecation warning if old allowedActions is present
   * (This runs once when composable is created)
   */
  if (process.env.NODE_ENV === 'development' && stateMachine.value?.allowedActions?.length) {
    console.warn(
      '[DEPRECATED] stateMachine.allowedActions is deprecated. Use capabilities[action].allowed instead.'
    )
  }

  return {
    canDo,
    denialReason,
    denialLabel,
    isDenied,
    // Named computed refs for convenience during component migration
    // These wrap canDo() to maintain the vocabulary centralization principle
    canSubmitForApproval: computed(() => canDo(ElectionActions.SUBMIT_FOR_APPROVAL)),
    canApprove: computed(() => canDo(ElectionActions.APPROVE)),
    canReject: computed(() => canDo(ElectionActions.REJECT)),
    canAutoSubmit: computed(() => canDo(ElectionActions.AUTO_SUBMIT)),
    canBeginSetup: computed(() => canDo(ElectionActions.BEGIN_SETUP)),
    canReviseAndResubmit: computed(() => canDo(ElectionActions.REVISE_AND_RESUBMIT)),
    canCompleteAdministration: computed(() => canDo(ElectionActions.COMPLETE_ADMINISTRATION)),
    canCompleteNomination: computed(() => canDo(ElectionActions.COMPLETE_NOMINATION)),
    canOpenVoting: computed(() => canDo(ElectionActions.OPEN_VOTING)),
    canCloseVoting: computed(() => canDo(ElectionActions.CLOSE_VOTING)),
    canPublishResults: computed(() => canDo(ElectionActions.PUBLISH_RESULTS)),
    canArchive: computed(() => canDo(ElectionActions.ARCHIVE)),
    canSuspend: computed(() => canDo(ElectionActions.SUSPEND)),
    canResume: computed(() => canDo(ElectionActions.RESUME)),
    // Denial reason helpers for UI tooltips and error messages
    openVotingBlockedReason: computed(() => denialReason(ElectionActions.OPEN_VOTING)),
    isOpenVotingDisabled: computed(() => isDenied(ElectionActions.OPEN_VOTING)),
  }
}
