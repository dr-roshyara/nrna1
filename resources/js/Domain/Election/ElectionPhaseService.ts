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

export type PhaseState = 'administration' | 'nomination' | 'voting' | 'results_pending' | 'results'

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
 * State Machine (Single Source of Truth for current state)
 * This is the ONLY place that knows what state we're in
 */
export interface StateMachine {
  currentState: PhaseState
  allowedActions: string[]
  completedStates?: PhaseState[]
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
 * Phase Completion Rules (DDD Aggregate)
 * Encapsulates what makes a phase "complete" based on business rules.
 */
export class PhaseCompletionRules {
  static isCompleted(phase: PhaseState, election: Election, clock: Clock): boolean {
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
  static isLockedFromEdit(phase: PhaseState, election: Election, clock: Clock): boolean {
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

  static getLockReason(phase: PhaseState, election: Election, clock: Clock): LockReasonCode {
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
  static canLockVoting(phase: PhaseState, stateMachine: StateMachine, election: Election): boolean {
    const hasPermission = (stateMachine.allowedActions || []).includes('lock_voting')
    return (
      phase === 'voting' &&
      stateMachine.currentState === 'voting' &&
      !election.voting_locked &&
      hasPermission
    )
  }

  static canComplete(
    phase: PhaseState,
    stateMachine: StateMachine,
    election: Election
  ): boolean {
    const actions = stateMachine.allowedActions || []
    if (phase === 'administration') return actions.includes('complete_administration')
    if (phase === 'nomination') return actions.includes('open_voting')
    return false
  }

  static canUpdateDates(phase: PhaseState, election: Election, clock: Clock): boolean {
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
    phase: PhaseState,
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
 * Phase Progress Tracker (DDD Calculation)
 * Tracks overall election progress.
 */
export class PhaseProgressTracker {
  private static readonly TOTAL_PHASES = 5

  static getCompletedCount(election: Election, clock: Clock): number {
    const phases: PhaseState[] = ['administration', 'nomination', 'voting', 'results_pending', 'results']
    return phases.filter(phase => PhaseCompletionRules.isCompleted(phase, election, clock)).length
  }

  static getProgressPercentage(election: Election, clock: Clock): number {
    return (this.getCompletedCount(election, clock) / this.TOTAL_PHASES) * 100
  }
}

/**
 * Phase View Model (CQRS Read Model Output)
 * Pure data structure for UI rendering
 * No methods, no side effects, no strings (let Vue format)
 */
export interface PhaseViewModel {
  state: PhaseState
  isCompleted: boolean
  isActive: boolean
  isUpcoming: boolean
  isLockedFromEdit: boolean
  lockReasonCode: LockReasonCode // UI maps this to translated string
  canLockVoting: boolean
  canComplete: boolean
  canUpdateDates: boolean
  countdownMs: number // Raw milliseconds, Vue formats as HH:mm:ss
}

/**
 * Progress Summary (CQRS Read Model Output)
 */
export interface ProgressSummary {
  completedCount: number
  totalPhases: number
  progressPercentage: number
}

/**
 * Phase State Aggregator (CQRS Read Model)
 * Single entry point for all phase queries.
 * PURE FUNCTION - no side effects, no validation, no logging
 */
export class PhaseStateAggregator {
  /**
   * Get complete phase view model
   * This is the ONLY way Vue should get domain data
   */
  static getPhaseViewModel(
    phase: PhaseState,
    election: Election,
    stateMachine: StateMachine,
    clock: Clock
  ): PhaseViewModel {
    return {
      state: phase,
      isCompleted: PhaseCompletionRules.isCompleted(phase, election, clock),
      isActive: PhaseTimelineRules.isPhaseActive(phase, election, stateMachine, clock),
      isUpcoming:
        !PhaseCompletionRules.isCompleted(phase, election, clock) &&
        phase !== stateMachine.currentState,
      isLockedFromEdit: PhaseLockRules.isLockedFromEdit(phase, election, clock),
      lockReasonCode: PhaseLockRules.getLockReason(phase, election, clock),
      canLockVoting: ActionAuthorizationRules.canLockVoting(phase, stateMachine, election),
      canComplete: ActionAuthorizationRules.canComplete(phase, stateMachine, election),
      canUpdateDates: ActionAuthorizationRules.canUpdateDates(phase, election, clock),
      countdownMs: PhaseTimelineRules.getCountdownMs(election, clock),
    }
  }

  /**
   * Get all phases' view models at once
   */
  static getAllPhasesViewModels(
    phases: PhaseState[],
    election: Election,
    stateMachine: StateMachine,
    clock: Clock
  ): PhaseViewModel[] {
    return phases.map(phase => this.getPhaseViewModel(phase, election, stateMachine, clock))
  }

  /**
   * Get progress summary
   */
  static getProgressSummary(election: Election, clock: Clock): ProgressSummary {
    return {
      completedCount: PhaseProgressTracker.getCompletedCount(election, clock),
      totalPhases: 5,
      progressPercentage: PhaseProgressTracker.getProgressPercentage(election, clock),
    }
  }
}
