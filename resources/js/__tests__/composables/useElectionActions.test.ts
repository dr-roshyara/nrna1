/**
 * S6 regression guard — completePhase must use Inertia router.post, not raw fetch.
 *
 * Root cause (useElectionActions.ts:90-97):
 *   completePhase() uses raw fetch() with no CSRF token and no Inertia headers.
 *   On an Inertia 2.0 page the server issues a 302 redirect; fetch follows it and
 *   returns HTML, then response.json() throws a SyntaxError — phase transitions
 *   never complete.
 *
 * Fix: replace fetch() with router.post() from @inertiajs/vue3.
 *   - CSRF token handled automatically via Inertia meta-tag mechanism.
 *   - Redirects handled correctly by Inertia protocol.
 *   - Success / error callbacks replace the fetch response-parsing logic.
 */

import { vi, describe, it, expect, beforeEach } from 'vitest'

// ── Mock Inertia router ──────────────────────────────────────────────────────
// Must be hoisted before any import that transitively loads the module.
const routerPostMock = vi.fn()

vi.mock('@inertiajs/vue3', () => ({
  router: {
    post: routerPostMock,
  },
}))

// ── Import composable AFTER mocks are in place ───────────────────────────────
import { useElectionActions } from '@/composables/useElectionActions'

// ── Helpers ──────────────────────────────────────────────────────────────────

/** Make router.post() resolve via onSuccess. */
function routerPostSucceeds() {
  routerPostMock.mockImplementation(
    (_url: string, _data: unknown, callbacks?: Record<string, Function>) => {
      callbacks?.onSuccess?.({})
    }
  )
}

/** Make router.post() resolve via onError with Laravel validation errors. */
function routerPostFails(errors: Record<string, string> = { error: 'Server error' }) {
  routerPostMock.mockImplementation(
    (_url: string, _data: unknown, callbacks?: Record<string, Function>) => {
      callbacks?.onError?.(errors)
    }
  )
}

// ── Test suite ───────────────────────────────────────────────────────────────

describe('useElectionActions – completePhase', () => {
  let fetchSpy: ReturnType<typeof vi.spyOn>

  beforeEach(() => {
    vi.clearAllMocks()
    // Spy on fetch to assert it is NOT called after the fix.
    fetchSpy = vi.spyOn(globalThis, 'fetch').mockResolvedValue(
      new Response(JSON.stringify({ message: 'unexpected fetch call' }), { status: 200 })
    )
  })

  // ── S6-a: RED — router.post must be called instead of fetch ──────────────

  it('calls router.post for completePhase, not raw fetch', async () => {
    routerPostSucceeds()

    const { completePhase } = useElectionActions()
    await completePhase('election-abc', 'administration')

    // FAILS currently: fetch() is used; routerPostMock is never called.
    expect(routerPostMock).toHaveBeenCalledOnce()
    expect(fetchSpy).not.toHaveBeenCalled()
  })

  // ── S6-b: router.post receives correct URL and payload ───────────────────

  it('passes correct URL and phase payload to router.post', async () => {
    routerPostSucceeds()

    const { completePhase } = useElectionActions()
    await completePhase('election-xyz', 'nomination')

    expect(routerPostMock).toHaveBeenCalledWith(
      '/elections/election-xyz/complete-phase',
      { phase: 'nomination' },
      expect.objectContaining({
        onSuccess: expect.any(Function),
        onError: expect.any(Function),
      })
    )
  })

  // ── S6-c: onSuccess path resolves ActionResult with success: true ─────────

  it('resolves with success:true when router.post calls onSuccess', async () => {
    routerPostSucceeds()

    const { completePhase } = useElectionActions()
    const result = await completePhase('election-abc', 'administration')

    expect(result.success).toBe(true)
    expect(result.error).toBeUndefined()
  })

  // ── S6-d: onError path resolves ActionResult with success: false ──────────

  it('resolves with success:false when router.post calls onError', async () => {
    routerPostFails({ phase: 'Phase precondition not met' })

    const { completePhase } = useElectionActions()
    const result = await completePhase('election-abc', 'administration')

    expect(result.success).toBe(false)
    expect(result.error).toBeTruthy()
  })

  // ── S6-e: composable loading state transitions correctly ──────────────────

  it('sets state to loading then success on happy path', async () => {
    routerPostSucceeds()

    const { completePhase, state } = useElectionActions()

    const promise = completePhase('election-abc', 'administration')
    // State is 'loading' while awaiting (synchronous check before microtask resolves)
    // After resolution it becomes 'success'
    await promise

    expect(state.value).toBe('success')
  })
})
