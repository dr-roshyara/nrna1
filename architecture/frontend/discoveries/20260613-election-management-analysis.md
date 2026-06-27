# Discovery: Election/Management.vue Analysis

**Date:** 2026-06-13  
**Context:** First frontend DDD migration target evaluation  
**Status:** Complete — ready for Increment 1 planning

## 1. File Overview

| Metric | Value |
|--------|-------|
| Total lines | 1598 |
| Template | ~1023 lines (64%) |
| Script | ~461 lines (29%) |
| Styles | ~112 lines (7%) |
| Imports | 18 |
| Reactive refs | 13 |
| Computed properties | 16 |
| Functions/methods | 16 |
| API calls (router.*) | 12 |
| Form submissions | 7 |

## 2. Layout

Uses `ElectionLayout` — correct for election flow pages.

## 3. All Imports

| Import | Category |
|--------|----------|
| `ElectionLayout` | Layout (Presentation) |
| `ref`, `computed` from `vue` | Vue API (Presentation) |
| `router`, `usePage` from `@inertiajs/vue3` | Infrastructure |
| `useI18n` from `vue-i18n` | Infrastructure |
| `Button`, `Card`, `StatusBadge`, `ActionButton`, `SectionCard`, `EmptyState` | Design System (Presentation) |
| `StateMachinePanel`, `StateBadge`, `StateProgress` | Child Components (Presentation) |
| `useElectionCapabilities` | Composable (Application) |
| `ElectionActions` | Constants (Domain) |
| `ElectionLifecycleStates` | Constants (Domain) |

## 4. Reactive State

| Ref | Type | Purpose |
|-----|------|---------|
| `isLoading` | boolean | Global loading state |
| `isUploadingLogo` | boolean | Logo upload loading |
| `logoFile` | File\|null | Selected logo file |
| `showCompletionModal` | boolean | Phase completion modal |
| `selectedPhase` | string\|null | Which phase being completed |
| `completionReason` | string | Reason for phase completion |
| `reasonError` | string | Validation error |
| `showSuspendModal` | boolean | Suspend modal |
| `suspendReason` | string | Suspension reason |
| `suspendCategory` | string | Suspension category |
| `expectedVoterCount` | number | Voter count input |
| `saveStatus` | string | Save indicator |

## 5. Computed Properties

| Computed | Classification |
|----------|---------------|
| `t` (i18n) | Presentation |
| `currentState` | Presentation |
| `capabilities` | Application (from composable) |
| `saveButtonClass`, `saveButtonText` | Presentation |
| `canSubmitForApproval` through `canSuspend` | Application (delegated to composable) |
| `isPendingApproval`, `isVotingActive` | Presentation |
| `phaseInfo` | Presentation |
| `settingsUrl` through `candidaciesUrl` (7 URL builders) | Presentation |

## 6. Methods — Full Inventory

| Method | Logic | Classification | Reuse Candidate? |
|--------|-------|----------------|-----------------|
| `onLogoFileChange` | Validates file size + type | Presentation | No |
| `uploadLogo` | FormData → router.post | Infrastructure | No |
| `toDatetimeLocal` | Date formatting | Presentation | No |
| `saveExpectedVoterCount` | Validates → PATCH | Application | Yes |
| `publishResults` | Confirm → router.post | Application | Yes |
| `unpublishResults` | Confirm → router.post | Application | Yes |
| `openVoting` | Confirm → router.post | Application | Yes |
| `closeVoting` | Confirm → router.post | Application | Yes |
| `handleResume` | Confirm → router.post → reload | Application | Yes |
| `handleSuspend` | Opens modal | Presentation | No |
| `handleSuspendConfirm` | Validates → router.post | Application | Yes |
| `handleSubmitForApproval` | Business rule (≤40 vs >40) | **Domain + Application** | Yes (highest value) |
| `handleBeginSetup` | router.post | Application | Yes |
| `handlePhaseCompleted` | Opens modal | Presentation | No |
| `submitPhaseCompletion` | Validates → maps route → POST | Application | Yes |
| `closeCompletionModal` | Resets state | Presentation | No |
| `handleDatesUpdated` | Phase→column mapping → PATCH | **Domain + Application** | Yes |

## 7. Repeated Pattern — Duplicated Orchestration

The following methods share an identical pattern (34 lines total):

```js
isLoading.value = true
router.post(route('elections.X', { election: slug }), {}, {
  preserveScroll: true,
  onFinish: () => { isLoading.value = false },
})
```

Affected methods: `publishResults`, `unpublishResults`, `openVoting`, `closeVoting`, `handleResume`, `handleBeginSetup`.

The existing `useElectionActions` composable already encapsulates this pattern. However, it uses raw `fetch` instead of Inertia `router` — migration feasibility requires further assessment.

## 8. Business Rules Inventory

| Rule | Location | Candidate Layer |
|------|----------|----------------|
| ≤40 voters → simplified self-approval path | `handleSubmitForApproval` | Domain |
| >40 voters → governance approval review path | `handleSubmitForApproval` | Domain |
| Phase completion requires a written reason (min 5 chars) | `submitPhaseCompletion` | Domain/Application |
| Suspension requires category + reason (min 10 chars) | `handleSuspendConfirm` | Domain/Application |
| Result publication requires `confirm()` dialog | `publishResults` | Presentation |
| Logo file must be ≤2MB and image type | `onLogoFileChange` | Presentation |
| Dates are stored in UTC ISO format (converted from local) | `handleDatesUpdated` | Infrastructure |

## 9. Dependency Direction

```
Current dependency flow:

Management.vue
    ↓
useElectionCapabilities (Composables/)
    ↓
ElectionActions (Constants/)
    ↓
ElectionLifecycleStates (Constants/)
    ↓
StateMachineContract (types/)

Observed violations:
None — capability checks go through the composable correctly.

Potential improvements:
- Consume ElectionPhaseService for phase mapping instead of inline phaseInfo computed
- Consume useElectionActions for orchestration instead of inline router.post
```

## 10. Technical Debt

| Debt | Severity | Candidate Resolution |
|------|----------|---------------------|
| 1598-line monolithic page | High | Incremental extraction over multiple increments |
| Duplicated `router.post` orchestration (6× identical pattern) | Medium | Evaluate `useElectionActions` after equivalence validation |
| Raw `<button>` usage (5 instances) | Low | Replace with `<Button variant="...">` |
| Raw `<input>` / `<textarea>` / `<select>` (5 instances) | Low | Replace with `<Input />` and `<Dropdown>` |
| `phaseInfo` computed duplicates ElectionPhaseService logic | Low | Migrate to use `phaseFor()` from ElectionPhaseService |
| GitHub: `./architecture/frontend/pages/ElectionManagement.md` not yet created | Low | Create page documentation |

## 11. Existing Abstractions Available for Reuse

| Artifact | Used? | Notes |
|----------|-------|-------|
| `useElectionCapabilities` | ✅ Yes (destructured) | Covers all capability checks |
| `ElectionActions` | ✅ Yes (imported) | Used for `denialLabel(ElectionActions.OPEN_VOTING)` |
| `ElectionLifecycleStates` | ✅ Yes (imported) | Used for `currentState === ElectionLifecycleStates.SUSPENDED` |
| `useElectionActions` | ❌ **Not used** | Exists but 0 consumers in Pages/ |
| `ElectionPhaseService` | ❌ **Not used** | Exists but not consumed by this page |

## 12. Design System Adoption

| Component | Usage |
|-----------|-------|
| `<Button>` | 1 (confirm modal) |
| `<Card>` | 1 (header) |
| `<StatusBadge>` | 1 (state badge) |
| `<ActionButton>` | 10+ (all action buttons) |
| `<SectionCard>` | 8+ (content sections) |
| `<EmptyState>` | 1 (no voters) |
| Raw `<button>` | 5 (modal cancel/confirm, FAQ, language) |
| Raw `<input>` | 3 (voter count, file, file) |
| Raw `<textarea>` | 1 (completion reason) |
| Raw `<select>` | 1 (suspend category) |

## 13. Recommendations

1. **Highest priority:** Assess whether `useElectionActions` is behaviourally equivalent to the existing Inertia router orchestration. Only migrate after equivalence is proven.
2. **Future extraction:** `handleSubmitForApproval` (business rule: ≤40 vs >40) is the strongest UseCase candidate.
3. **Future extraction:** `handleDatesUpdated` (phase→column mapping) contains domain knowledge that belongs in Domain layer.
4. **No extraction needed:** Modals, loading flags, formatting helpers are pure presentation.

## 14. Architectural Conclusion

The Election context already contains emerging Domain and Application layers:

| Layer | Artifacts |
|-------|-----------|
| Domain | `ElectionPhaseService`, `ElectionActions`, `ElectionLifecycleStates`, `StateMachineContract` |
| Application | `useElectionCapabilities`, `useElectionActions` |
| Presentation | `Management.vue`, child components, design system components |

The primary architectural challenge is **not** creating new abstractions — it is **increasing adoption of existing abstractions** while preserving behaviour.

This page (Management.vue) is a valid first migration target because it already imports some abstractions (`useElectionCapabilities`) but not others (`useElectionActions`, `ElectionPhaseService`). Closing this consumption gap is higher value than introducing new DDD artifacts.

Future refactoring should prioritize:
1. **Reuse before create** — evaluate existing abstractions first
2. **Consolidation** — reduce duplicated orchestration
3. **Extraction only when justified** by domain value

## Related

- [ADR-001: Reuse Before Create](../decisions/ADR-001-Reuse-Before-Create.md)
- [Discovery: Election Actions Assessment](20260613-election-actions-assessment.md)
