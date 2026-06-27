# Discovery: Election Actions Assessment

**Date:** 2026-06-13  
**Status:** Draft — migration feasibility not yet validated  
**ADR Reference:** [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)

## Purpose

Compare Management.vue's orchestration patterns against the existing `useElectionActions` composable to determine whether migration is feasible, safe, and equivalent.

This is an **implementation hypothesis** — the ADR principle (Reuse Before Create) is already validated, but the specific migration path for `useElectionActions` still requires equivalence proving.

## 1. Current Pattern in Management.vue

### Pattern A — Simple POST (appears 6 times)

```js
isLoading.value = true
router.post(route('elections.X', { election: slug }), {}, {
  preserveScroll: true,
  onFinish: () => { isLoading.value = false },
})
```

Used by: `publishResults`, `unpublishResults`, `openVoting`, `closeVoting`, `handleResume`, `handleBeginSetup`

### Pattern B — POST with redirect/reload

```js
isLoading.value = true
router.post(route('elections.Y', { election: slug }), {}, {
  preserveScroll: true,
  onSuccess: () => { router.reload() },
  onFinish: () => { isLoading.value = false },
})
```

Used by: `handleResume` (with `onSuccess`), `handleSuspendConfirm` (with `onSuccess`)

### Pattern C — POST with error handling

```js
isLoading.value = true
router.post(route('elections.Z', { election: slug }), {}, {
  preserveScroll: true,
  onSuccess: () => { router.reload(...) },
  onError: (errors) => { ... },
  onFinish: () => { isLoading.value = false },
})
```

Used by: `handleSubmitForApproval`, `submitPhaseCompletion`

## 2. Existing `useElectionActions` Composable

```js
// Current implementation (Composables/useElectionActions.ts):
const isLoading = computed(() => state.value === 'loading')

async function executeAction(context, handler) {
  state.value = 'loading'
  try {
    const result = await handler()
    state.value = result.success ? 'success' : 'error'
    return result
  } catch (e) {
    state.value = 'error'
    return { success: false, error: e.message }
  }
}
```

## 3. Behaviour Comparison

| Concern | Management.vue (Current) | useElectionActions (Target) | Equivalent? |
|---------|--------------------------|----------------------------|-------------|
| HTTP transport | `router.post` (Inertia) | `fetch` (raw) | ❌ **Gap** |
| Preserve scroll | `preserveScroll: true` | No equivalent | ❌ **Gap** |
| Page reload | `router.reload()` | No equivalent | ❌ **Gap** |
| Loading state | `isLoading` ref | `state` ref (idle/loading/success/error) | ⚠️ Different shape |
| Error handling | `onError` callback | `catch` → error state | ✅ Similar |
| Loading indicator | `isLoading.value = true/false` | `state.value = 'loading'` | ⚠️ Different variable |
| Confirmation dialog | Caller handles before calling | Not included | ✅ Unchanged |

## 4. Identified Gaps

### Gap 1: Inertia `router` vs raw `fetch`

`useElectionActions` uses raw `fetch()` for its `completePhase` method. Management.vue uses Inertia `router.post()` for all its flows.

**Impact:** If we switch to `useElectionActions.executeAction()`, we'd need to either:
- Adapt the composable to accept an Inertia router call as the handler (breaking its abstraction)
- Continue using `router.post` outside the composable (missing the reuse opportunity)
- Create an adapter that preserves Inertia's page management

### Gap 2: `preserveScroll` and page reload

Management.vue relies on Inertia's `preserveScroll: true` and `router.reload()` after certain actions. The existing composable doesn't support Inertia page lifecycle management.

**Impact:** Migration would require either:
- Adding Inertia lifecycle options to the composable
- Keeping page-reload logic outside the composable
- Accepting scroll reset on actions

### Gap 3: Loading state variable naming

Management.vue uses a single `isLoading` ref shared across all actions. `useElectionActions` has its own `state` ref with `idle/loading/success/error` states.

**Impact:** Management.vue's conditional UI (`:loading="isLoading"`, `:disabled="isLoading"`) expects a boolean. The composable provides `isLoading` via computed. These are compatible, but the shared `isLoading` would change behavior — one action's loading state would affect all buttons.

## 5. Risk Assessment

| Risk | Severity | Mitigation |
|------|----------|------------|
| Scroll reset on actions | Low | Add `preserveScroll` option to composable |
| Loading state conflict | Low | Keep `isLoading` in Management.vue, use composable for state tracking only |
| Inertia page reload missing | Low | Add `onSuccess`/`onReload` callback to executeAction |
| Behaviour regression | Medium | Test each action after migration — compare exact route, payload, and response handling |

## 6. Migration Feasibility

| Method | Can use useElectionActions? | Adaptations needed |
|--------|---------------------------|-------------------|
| `publishResults` | ⚠️ Partial | Needs Inertia adapter for `router.post` |
| `unpublishResults` | ⚠️ Partial | Same |
| `openVoting` | ⚠️ Partial | Same |
| `closeVoting` | ⚠️ Partial | Same |
| `handleResume` | ⚠️ Partial | Needs `router.reload` support |
| `handleBeginSetup` | ⚠️ Partial | Needs Inertia adapter |
| `handleSuspendConfirm` | ⚠️ Partial | Needs `onSuccess` callback |
| `submitPhaseCompletion` | ⚠️ Partial | Needs `onError` callback |
| `handleSubmitForApproval` | ❌ Not suitable | Contains business rule branching (≤40 vs >40) |

## 7. Conclusion

**Migration to `useElectionActions` is not currently safe without adaptations.**

The composable uses raw `fetch` while Management.vue relies on Inertia `router` for:
- Page-level loading states
- Scroll preservation
- Page reload after state transitions
- Inertia error handling

## 8. Architectural Conclusion

**Discovery Result:** Migration deferred.

**Reason:** Existing abstraction (`useElectionActions`) is not behaviourally equivalent. It uses raw `fetch` while the consumer depends on Inertia `router` semantics (scroll preservation, page reload, error bags).

**Decision:** Preserve current implementation until equivalence can be demonstrated.

**Next Investigation:** Determine whether `useElectionActions` can become transport-agnostic without violating ADR-001. If `executeAction(handler)` can accept `router.post(...)` as a callable strategy, no second composable is needed.

## 9. Recommended Path

1. Keep the current duplicated orchestration pattern in Management.vue for now.
2. Investigate whether `useElectionActions` can be made transport-agnostic:
   - Can `executeAction(handler)` accept a callable wrapping `router.post()`?
   - If yes, adapt the composable without creating a second abstraction.
   - If no, the composable has an architectural boundary issue worth its own ADR.
3. Only after this investigation, create an `ADR-004` documenting the migration decision.

**Constraint:** Do NOT create `useInertiaElectionActions` — that would violate ADR-001 (Reuse Before Create).

## Critical Strategic Insight

NRNA does not currently have a DDD problem. NRNA has an application-service adoption problem.

The architecture already exists (`ElectionPhaseService`, `ElectionActions`, `ElectionLifecycleStates`, `useElectionCapabilities`, `useElectionActions`). The pages are simply not consuming it consistently. That is a much healthier situation than discovering no architecture at all.

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Discovery: Election Management Analysis](20260613-election-management-analysis.md)
