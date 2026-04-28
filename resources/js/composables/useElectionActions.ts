/**
 * useElectionActions - Application Service Composable
 *
 * Centralizes all election action handling:
 * - Phase transitions
 * - Error handling
 * - Loading states
 * - Audit/logging
 *
 * This is the bridge between UI (Vue) and Domain (business logic).
 */

import { ref, computed } from 'vue'

export type ActionState = 'idle' | 'loading' | 'success' | 'error'

interface ActionContext {
  electionId: string
  userId: string
  action: string
}

export interface ActionResult {
  success: boolean
  message?: string
  error?: string
  timestamp: Date
}

export function useElectionActions() {
  const state = ref<ActionState>('idle')
  const error = ref<string | null>(null)
  const lastResult = ref<ActionResult | null>(null)

  const isLoading = computed(() => state.value === 'loading')
  const hasError = computed(() => state.value === 'error')

  /**
   * Execute an election action with centralized handling
   */
  async function executeAction(
    context: ActionContext,
    handler: () => Promise<ActionResult>
  ): Promise<ActionResult> {
    state.value = 'loading'
    error.value = null

    try {
      const result = await handler()

      if (result.success) {
        state.value = 'success'
        lastResult.value = result
        console.log(`✅ Action "${context.action}" succeeded for election ${context.electionId}`)
      } else {
        state.value = 'error'
        error.value = result.error || 'Unknown error'
        console.error(`❌ Action "${context.action}" failed:`, result.error)
      }

      return result
    } catch (e) {
      const errorMsg = e instanceof Error ? e.message : String(e)
      state.value = 'error'
      error.value = errorMsg
      console.error(`💥 Action "${context.action}" crashed:`, e)

      return {
        success: false,
        error: errorMsg,
        timestamp: new Date(),
      }
    }
  }

  /**
   * Lock voting action
   */
  async function lockVoting(
    electionId: string,
    router: any
  ): Promise<ActionResult> {
    const context: ActionContext = {
      electionId,
      userId: '', // Will be set by caller if needed
      action: 'lock_voting',
    }

    return executeAction(context, async () => {
      const response = await fetch(`/elections/${electionId}/lock-voting`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
      })

      const data = await response.json()

      if (!response.ok) {
        return {
          success: false,
          error: data.message || 'Failed to lock voting',
          timestamp: new Date(),
        }
      }

      return {
        success: true,
        message: 'Voting locked successfully',
        timestamp: new Date(),
      }
    })
  }

  /**
   * Complete phase action
   */
  async function completePhase(
    electionId: string,
    phase: string
  ): Promise<ActionResult> {
    const context: ActionContext = {
      electionId,
      userId: '',
      action: `complete_${phase}`,
    }

    return executeAction(context, async () => {
      const response = await fetch(`/elections/${electionId}/complete-phase`, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify({ phase }),
      })

      const data = await response.json()

      if (!response.ok) {
        return {
          success: false,
          error: data.message || `Failed to complete ${phase} phase`,
          timestamp: new Date(),
        }
      }

      return {
        success: true,
        message: `${phase} phase completed`,
        timestamp: new Date(),
      }
    })
  }

  /**
   * Reset action state
   */
  function reset(): void {
    state.value = 'idle'
    error.value = null
  }

  return {
    // State
    state,
    error,
    lastResult,
    isLoading,
    hasError,

    // Actions
    lockVoting,
    completePhase,
    executeAction,
    reset,
  }
}
