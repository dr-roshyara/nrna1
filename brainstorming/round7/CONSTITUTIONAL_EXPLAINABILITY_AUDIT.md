# Round 7: Constitutional Explainability Audit

**Date:** 2026-06-14  
**Purpose:** Trace every constitutional action from backend denial → frontend display. Identify Governance Transparency Debts.

## Architecture

```
Backend CapabilityDecision
    → reason (machine enum) + detail (human string)
    ↓
ElectionManagementController::resolveCapabilities()
    → denial_reason (string) + denial_detail (string|null)
    ↓
StateMachineContract (frontend type)
    → denial_reason: string | null
    → denial_detail: string | null
    ↓
useElectionCapabilities()
    → denialReason(action)  → returns reason string
    → denialDetail(action)  → returns detail string  ← EXISTS but UNUSED in pages
    → denialLabel(action)   → returns hardcoded label map (ignores detail)
    ↓
Vue Pages
    → Management.vue: denialLabel() shown for open_voting + close_voting only
    → All other actions: no denial information displayed
```

## Gap Analysis

| Layer | Detail Available? | Status |
|-------|-------------------|--------|
| Backend `CapabilityDecision` | ✅ `detail` field populated by policy chain | PASS |
| Controller `resolveCapabilities()` | ✅ `denial_detail` sent to frontend | PASS |
| `StateMachineContract` type | ✅ `denial_detail: string \| null` defined | PASS |
| `ElectionCapabilitySnapshot.denialDetail()` | ✅ Method exists | PASS |
| `useElectionCapabilities.denialDetail()` | ❌ Not exposed — the composable has `denialReason()` and `denialLabel()` but no `denialDetail()` | **FAIL** |
| Vue pages display denial | ❌ `denialLabel()` used for 2/15 actions only. `denial_detail` never read. | **FAIL** |

## Action-by-Action Audit

| Action | denial_reason received? | denial_detail received? | Displayed to user? | User can act on explanation? | Status |
|--------|------------------------|------------------------|-------------------|------------------------------|--------|
| `submit_for_approval` | ✅ | ✅ (when denied) | ❌ Button hidden silently | ❌ | **FAIL** |
| `approve` | ✅ | ✅ | ❌ Admin only, hidden | ❌ | **FAIL** |
| `reject` | ✅ | ✅ | ❌ Admin only, hidden | ❌ | **FAIL** |
| `auto_submit` | ✅ | ✅ | ❌ System action, no UI | ❌ | **FAIL** |
| `begin_setup` | ✅ | ✅ | ❌ Button hidden silently | ❌ | **FAIL** |
| `revise_and_resubmit` | ✅ | ✅ | ❌ Button hidden silently | ❌ | **FAIL** |
| `complete_administration` | ✅ | ✅ | ❌ Button hidden silently | ❌ | **FAIL** |
| `complete_nomination` | ✅ | ✅ | ❌ Modal-based, no denial | ❌ | **FAIL** |
| `apply_candidacy` | ✅ | ✅ | ❌ Route guard, no denial | ❌ | **FAIL** |
| `open_voting` | ✅ | ✅ | ✅ `denialLabel()` shown | ⚠️ Generic label | **WARNING** |
| `close_voting` | ✅ | ✅ | ✅ `denialLabel()` shown | ⚠️ Generic label | **WARNING** |
| `publish_results` | ✅ | ✅ | ❌ Button hidden silently | ❌ | **FAIL** |
| `archive` | ✅ | ✅ | ❌ Button hidden silently | ❌ | **FAIL** |
| `suspend` | ✅ | ✅ | ❌ Modal-based, no denial | ❌ | **FAIL** |
| `resume` | ✅ | ✅ | ❌ Hidden in suspension banner | ❌ | **FAIL** |

## Governance Transparency Debts

### GT-01: denialDetail convenience method missing from composable (WARNING — not broken chain)

`useElectionCapabilities` exposes `denialReason()` and `denialLabel()` but not `denialDetail()`. The backend sends `denial_detail` via `$decision->detail` and it reaches the frontend snapshot. Pages can access it via `stateMachine.value?.capabilities?.[action]?.denial_detail` directly, but no convenience method exists.

**Classification:** Governance Transparency Debt — unused capability, not broken chain. The data arrives correctly; the composable API is incomplete.

### GT-02: 13 of 15 actions have no denial explanation

Only `open_voting` and `close_voting` show any denial information. For all other actions, the button is simply hidden when the action is unavailable. Users cannot distinguish between "you lack the role," "the election is in the wrong state," "a precondition is unmet," or "trust verification failed."

**Fix:** For every `<ActionButton>` that is conditionally rendered based on capability, add a companion explanation paragraph using `denialDetail(action)` (or `denialLabel(action)` as fallback).

### GT-03: denialLabel is generic, not contextual

Current frontend labels:
- "Invalid Lifecycle State"
- "Missing Required Role"
- "Unmet Requirements"

These describe the denial class but not the specific precondition. For example, when `open_voting` is denied because "voting window not defined," the user sees "Invalid Lifecycle State" instead of "Voting window must be set before opening voting."

**Fix:** Prefer `denialDetail()` over `denialLabel()` for user-facing explanations.

## Summary

| Metric | Count |
|--------|-------|
| Actions where denial is explained to user | 2 of 15 (13%) |
| Actions where denial is completely opaque | 13 of 15 (87%) |
| Governance Transparency Debts identified | 3 |
| High-priority fixes needed | 2 (GT-01, GT-02) |

## Recommendations

| Priority | Defect | Fix |
|----------|--------|-----|
| **P1** | GT-01 | Expose `denialDetail()` from `useElectionCapabilities` composable |
| **P1** | GT-02 | Show denial explanation for all 13 opaque actions in Management.vue |
| **P2** | GT-03 | Prefer `denialDetail()` over hardcoded `DENIAL_LABELS` map |
