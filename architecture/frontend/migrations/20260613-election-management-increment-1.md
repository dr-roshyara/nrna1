# Migration Plan: Election/Management.vue — Increment 1

**Date:** 2026-06-13  
**Status:** Draft — awaiting investigation go-ahead  
**ADR Reference:** [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)  
**Discovery References:** [Management Analysis](../discoveries/20260613-election-management-analysis.md), [Actions Assessment](../discoveries/20260613-election-actions-assessment.md)

## Original Goal (Revised)

**Do NOT refactor Management.vue directly yet.**

The original goal was: *Refactor Management.vue to use existing `useElectionActions` composable.*

The discovery proved this migration is **not currently safe** — the composable uses raw `fetch` while Management.vue depends on Inertia `router` semantics.

**Revised goal:** Investigate whether `useElectionActions.executeAction(handler)` can accept Inertia `router.post` as a callable strategy, making the composable transport-agnostic. If so, refactor Management.vue to use it. If not, document the architectural boundary issue and create an ADR.

## Scope

| Item | In scope | Out of scope |
|------|----------|--------------|
| `useElectionActions` investigation | ✅ | |
| Management.vue refactoring | | ✅ (blocked by investigation) |
| Creating new composable | | ✅ (would violate ADR-001) |
| New UseCase files | | ✅ (would violate ADR-001) |
| Architecture documentation | ✅ | |

## Investigation Plan

### Step 1: Analyse `useElectionActions.executeAction`

Read the full composable implementation. Determine:
- Does `executeAction(context, handler)` accept an arbitrary async function?
- Does it require a `Promise<ActionResult>` return type?
- Can `router.post(...)` fulfil that contract?

### Step 2: Prototype the adapter

```ts
// Hypothetical adapter — does this compile and work?
import { router } from '@inertiajs/vue3'

const result = await useElectionActions().executeAction(
  { electionId: slug, userId: '', action: 'publish_results' },
  async () => {
    return new Promise((resolve) => {
      router.post(route('elections.publish', { election: slug }), {}, {
        preserveScroll: true,
        onSuccess: () => resolve({ success: true, timestamp: new Date() }),
        onError: (errors) => resolve({ success: false, error: Object.values(errors).join(', '), timestamp: new Date() }),
      })
    })
  }
)
```

### Step 3: Evaluate the result

| Criterion | Pass/Fail |
|-----------|-----------|
| Behaviour preserved? | |
| Scroll position preserved? | |
| Error handling works? | |
| Loading state tracks correctly? | |
| No breaking changes? | |
| Still honours ADR-001? | |

### Step 4: Decide

| Outcome | Action |
|---------|--------|
| ✅ Adapter works cleanly | Refactor Management.vue to use `useElectionActions` |
| ⚠️ Adapter works but is awkward | Document gap, consider minor composable extension |
| ❌ Adapter is not feasible | Preserve current implementation. Create ADR documenting the architectural boundary |

## Files Affected

| File | Change | Risk |
|------|--------|------|
| `Composables/useElectionActions.ts` | None during investigation. Possibly minor extension later. | Low |
| `Pages/Election/Management.vue` | None during investigation. Refactoring later if adapter works. | Low (no code changed yet) |
| `architecture/frontend/` | Discovery docs already created. | None |

## Rollback Strategy

No code changes will be made during the investigation phase. Rollback is not applicable.

If the investigation leads to a refactoring increment, the rollback is: revert the Management.vue changes. The existing patterns are already well-understood.

## Validation

After any implementation:
- `npm run verify` — all 6 gates pass
- `npm run design-check` — no token regressions
- Manual test of each migrated action (publish, unpublish, open/close voting, resume, begin setup)

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Discovery: Election Management Analysis](../discoveries/20260613-election-management-analysis.md)
- [Discovery: Election Actions Assessment](../discoveries/20260613-election-actions-assessment.md)
