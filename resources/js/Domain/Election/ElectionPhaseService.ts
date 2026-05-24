/**
 * Election Phase Domain Service
 *
 * Pure business logic for election phase state, validation, and rules.
 * No Vue/framework dependencies. Fully testable.
 *
 * CRITICAL RULES:
 * 1. All domain methods accept Clock, NEVER call clock.now() outside rules
 * 2. No side effects (no logging, no mutations in read models)
 * 3. Domain returns raw data (numbers, codes), not formatted strings
 * 4. Dates are Date objects, not ISO strings
 */

import { ElectionLifecycleStates } from '@/Constants/ElectionLifecycleStates'

export type ElectionPhase = 'administration' | 'nomination' | 'voting' | 'results_pending' | 'results'

/**
 * Election Phase Projection (CQRS Read Model)
 * Maps constitutional lifecycle state to UI phase, indicating overlay states
 */
export interface ElectionPhaseProjection {
  phase: ElectionPhase | null
  lifecycleState: string
  isOverlay: boolean
}

/**
 * Clock Abstraction (Dependency Injection)
 * Enables deterministic time-based logic and testability.
 * NEVER call new Date() in domain - always use clock.
 */
export interface Clock {
  now(): Date
}

export class SystemClock implements Clock {
  now(): Date {
    return new Date()
  }
}

/**
 * Election Domain Object
 * Does NOT include 'state' — state lives ONLY in StateMachine (single source of truth)
 * Uses Date objects, not ISO strings (domain language, not API format)
 */
export interface Election {
  // Phase completion flags
  administration_completed: boolean
  nomination_completed: boolean
  voting_locked: boolean

  // Voting window (MUST be Date, not string)
  voting_starts_at: Date | null
  voting_ends_at: Date | null

  // Results
  results_published_at: Date | null
  results_published: boolean
}

/**
 * Capability Entry (Constitutional Authority)
 * Returned by the capability resolver for each constitutional action
 */
export interface CapabilityEntry {
  allowed: boolean
  denial_reason: string | null
  denial_detail: string | null
}

/**
 * State Machine (Single Source of Truth for current state)
 * This is the ONLY place that knows what state we're in
 */
export interface StateMachine {
  currentState: ElectionPhase
  /** @deprecated Use capabilities[action].allowed instead */
  allowedActions: string[]
  completedStates?: ElectionPhase[]
  capabilities?: Record<string, CapabilityEntry>
  capabilities_metadata?: {
    resolver_version: string
    generated_at: string
    constitution_hash: string
  }
}

/**
 * Domain Invariants (Business Rules)
 * Guards against invalid election states
 */
export class ElectionInvariants {
  static validate(election: Election, stateMachine: StateMachine): void {
    // Voting window must be valid
    if (
      election.voting_starts_at &&
      election.voting_ends_at &&
      election.voting_starts_at >= election.voting_ends_at
    ) {
      throw new Error('Invalid voting window: start must be before end')
    }

    // Cannot transition to voting if dates not set
    if (stateMachine.currentState === 'voting' && !election.voting_starts_at) {
      throw new Error('Cannot enter voting phase without start date')
    }

    // Results cannot be published before voting ends
    if (
      election.results_published &&
      election.voting_ends_at &&
      election.results_published_at &&
      election.results_published_at < election.voting_ends_at
    ) {
      throw new Error('Cannot publish results before voting ends')
    }
  }
}

/**
 * @deprecated C.2.8 — Phase completion semantics are now projected by the backend
 * via stateMachine.completedStates. The voting case here uses client clock
 * (clock.now() > voting_ends_at) in violation of constitutional runtime principles.
 * Do not use for lifecycle interpretation, rendering, or governance decisions.
 * Delete aggressively once callers are confirmed absent.
 * @see ADR-005 Projection Sovereignty
 *
 * Phase Completion Rules (DDD Aggregate)
 * Encapsulates what makes a phase "complete" based on business rules.
 */
export class PhaseCompletionRules {
  static isCompleted(phase: ElectionPhase, election: Election, clock: Clock): boolean {
    const now = clock.now()

    switch (phase) {
      case 'administration':
        return election.administration_completed === true

      case 'nomination':
        return election.nomination_completed === true

      case 'voting':
        // Voting is complete when the voting period has ended
        return election.voting_ends_at ? now > election.voting_ends_at : false

      case 'results_pending':
        // Results pending is complete when results ARE published AND voting has ended
        return (
          election.voting_ends_at &&
          now > election.voting_ends_at &&
          election.results_published === true
        )

      case 'results':
        // Results phase is complete when results are published
        return election.results_published === true

      default:
        return false
    }
  }
}

/**
 * Phase Lock Reason Codes (Domain Enums, not UI strings)
 * These are returned to the UI, which maps them to translations
 */
export enum LockReasonCode {
  ADMIN_LOCKED = 'ADMIN_LOCKED',
  NOMINATION_LOCKED = 'NOMINATION_LOCKED',
  VOTING_CLOSED = 'VOTING_CLOSED',
  VOTING_IN_PROGRESS = 'VOTING_IN_PROGRESS',
  RESULTS_PENDING = 'RESULTS_PENDING',
  RESULTS_PUBLISHED = 'RESULTS_PUBLISHED',
  PHASE_LOCKED = 'PHASE_LOCKED',
}

/**
 * Phase Lock Rules (DDD Specification)
 * Encapsulates when a phase is locked from editing.
 */
export class PhaseLockRules {
  static isLockedFromEdit(phase: ElectionPhase, election: Election, clock: Clock): boolean {
    const now = clock.now()

    switch (phase) {
      case 'administration':
        return election.administration_completed === true
      case 'nomination':
        return election.nomination_completed === true
      case 'voting':
        return (
          election.voting_locked === true ||
          (election.voting_starts_at && now >= election.voting_starts_at)
        )
      case 'results_pending':
      case 'results':
        return true
      default:
        return false
    }
  }

  static getLockReason(phase: ElectionPhase, election: Election, clock: Clock): LockReasonCode {
    const now = clock.now()

    switch (phase) {
      case 'administration':
        return LockReasonCode.ADMIN_LOCKED
      case 'nomination':
        return LockReasonCode.NOMINATION_LOCKED
      case 'voting':
        if (election.voting_locked) return LockReasonCode.VOTING_CLOSED
        if (election.voting_starts_at && now >= election.voting_starts_at)
          return LockReasonCode.VOTING_IN_PROGRESS
        return LockReasonCode.PHASE_LOCKED
      case 'results_pending':
        return LockReasonCode.RESULTS_PENDING
      case 'results':
        return LockReasonCode.RESULTS_PUBLISHED
      default:
        return LockReasonCode.PHASE_LOCKED
    }
  }
}

/**
 * Action Authorization Rules (DDD Policy)
 * Determines which actions are allowed based on state and permissions.
 */
export class ActionAuthorizationRules {
  static canLockVoting(phase: ElectionPhase, stateMachine: StateMachine, election: Election): boolean {
    return (
      phase === 'voting' &&
      stateMachine.currentState === 'voting' &&
      !election.voting_locked
    )
  }

  static canComplete(
    phase: ElectionPhase,
    stateMachine: StateMachine,
    election: Election
  ): boolean {
    if (phase === 'administration') {
      return stateMachine.capabilities?.['complete_administration']?.allowed ?? false
    }
    if (phase === 'nomination') {
      return stateMachine.capabilities?.['complete_nomination']?.allowed ?? false
    }
    return false
  }

  static canUpdateDates(phase: ElectionPhase, election: Election, clock: Clock): boolean {
    const now = clock.now()

    switch (phase) {
      case 'administration':
        return !election.administration_completed
      case 'nomination':
        return !election.nomination_completed
      case 'voting':
        return (
          !election.voting_locked &&
          (!election.voting_starts_at || now < election.voting_starts_at)
        )
      case 'results_pending':
      case 'results':
        return false
      default:
        return false
    }
  }
}

/**
 * Phase Timeline Rules (DDD Query Model)
 * Calculates time-based metrics for phases.
 */
export class PhaseTimelineRules {
  static isPhaseActive(
    phase: ElectionPhase,
    election: Election,
    stateMachine: StateMachine,
    clock: Clock
  ): boolean {
    const now = clock.now()

    if (phase === 'voting') {
      return (
        election.voting_starts_at &&
        election.voting_ends_at &&
        now >= election.voting_starts_at &&
        now < election.voting_ends_at
      )
    }
    return phase === stateMachine.currentState
  }

  /**
   * Return countdown in MILLISECONDS (not formatted string)
   * Formatting is presentation concern, not domain
   */
  static getCountdownMs(election: Election, clock: Clock): number {
    if (!election.voting_ends_at) return 0

    const now = clock.now()
    const diffMs = election.voting_ends_at.getTime() - now.getTime()

    return Math.max(0, diffMs)
  }
}

/**
 * Phase For (CQRS Projection Function)
 * Maps constitutional lifecycle state to UI phase projection
 * Returns null phase for overlay states (ARCHIVED, SUSPENDED)
 */
export function phaseFor(lifecycleStateOrString: string): ElectionPhaseProjection {
  const state = lifecycleStateOrString.toUpperCase()

  // Administration phase (all approval/setup states before nomination)
  if (
    state === ElectionLifecycleStates.DRAFT.toUpperCase() ||
    state === ElectionLifecycleStates.SUBMITTED_FOR_APPROVAL.toUpperCase() ||
    state === ElectionLifecycleStates.APPROVED.toUpperCase() ||
    state === ElectionLifecycleStates.REJECTED.toUpperCase() ||
    state === ElectionLifecycleStates.SETUP_ADMINISTRATION.toUpperCase()
  ) {
    return {
      phase: 'administration',
      lifecycleState: lifecycleStateOrString,
      isOverlay: false,
    }
  }

  // Nomination phase
  if (state === ElectionLifecycleStates.SETUP_NOMINATION.toUpperCase()) {
    return {
      phase: 'nomination',
      lifecycleState: lifecycleStateOrString,
      isOverlay: false,
    }
  }

  // Voting phase
  if (
    state === ElectionLifecycleStates.READY_FOR_VOTING.toUpperCase() ||
    state === ElectionLifecycleStates.VOTING_ACTIVE.toUpperCase()
  ) {
    return {
      phase: 'voting',
      lifecycleState: lifecycleStateOrString,
      isOverlay: false,
    }
  }

  // Results pending phase
  if (state === ElectionLifecycleStates.COUNTING.toUpperCase()) {
    return {
      phase: 'results_pending',
      lifecycleState: lifecycleStateOrString,
      isOverlay: false,
    }
  }

  // Results phase
  if (state === ElectionLifecycleStates.RESULTS_PUBLISHED.toUpperCase()) {
    return {
      phase: 'results',
      lifecycleState: lifecycleStateOrString,
      isOverlay: false,
    }
  }

  // Overlay states (ARCHIVED, SUSPENDED) — return null phase
  if (
    state === ElectionLifecycleStates.ARCHIVED.toUpperCase() ||
    state === ElectionLifecycleStates.SUSPENDED.toUpperCase()
  ) {
    return {
      phase: null,
      lifecycleState: lifecycleStateOrString,
      isOverlay: true,
    }
  }

  // Fallback: unknown state
  return {
    phase: null,
    lifecycleState: lifecycleStateOrString,
    isOverlay: false,
  }
}
