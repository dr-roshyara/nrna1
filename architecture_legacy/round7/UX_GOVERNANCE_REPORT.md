# Round 7: UX Governance Report

**Date:** 2026-06-14  
**Purpose:** Trace every constitutional denial reason from backend capability decisions to user-visible messages

## Backend → Frontend Denial Chain

```
CapabilityDenialReason (7 reasons)
    ↓
StateMachineContract.capabilities[action]
    → allowed: boolean
    → denial_reason: string | null
    → denial_detail: string | null
    ↓
useElectionCapabilities()
    → canDo(action): boolean
    → denialReason(action): string | null
    → denialLabel(action): string | null
    ↓
Vue Component
    → v-if="!canDo(action)"
    → {{ denialLabel(action) }}
```

## Denial Reason Inventory (Backend)

| Reason | Meaning | UX Label (Frontend) |
|--------|---------|---------------------|
| `suspended` | Election under governance hold | "Election Suspended" |
| `missing_role` | User lacks required role | "Missing Required Role" |
| `invalid_lifecycle` | Action not allowed in current state | "Invalid Lifecycle State" |
| `unmet_precondition` | Prerequisites not fulfilled | "Unmet Requirements" |
| `trust_denied` | Trust verification failed | "Trust Verification Failed" |
| `constitutional_review_pending` | Constitutional review in progress | "Constitutional Review Required" |
| `trust_evaluation_inconclusive` | Trust cannot be established | "Trust Cannot Be Established" |

## Action-by-Action UX Audit

| Action | denialLabel() shown in UI? | How? | Status |
|--------|---------------------------|------|--------|
| `submit_for_approval` | ❌ Not shown | Button is hidden when `!canSubmitForApproval`. No explanation rendered. | **FAIL** |
| `approve` | ❌ Not shown | Admin only — no denial explanation | **FAIL** |
| `reject` | ❌ Not shown | Admin only — no denial explanation | **FAIL** |
| `begin_setup` | ❌ Not shown | Button hidden when `!canBeginSetup`. No explanation. | **FAIL** |
| `revise_and_resubmit` | ❌ Not shown | Button hidden when rejected — no denial explanation | **FAIL** |
| `complete_administration` | ❌ Not shown | Button hidden when `!canCompleteAdministration` | **FAIL** |
| `complete_nomination` | ❌ Not shown | Handled via modal phase completion, not capability | **WARNING** |
| `apply_candidacy` | ❌ Not shown | Route-level guard, not capability-based | **WARNING** |
| `open_voting` | ✅ `denialLabel(OPEN_VOTING)` shown as muted text below button | `text-slate-400 font-medium` | **PASS** |
| `close_voting` | ✅ `denialLabel(CLOSE_VOTING)` shown as muted text below button | `text-slate-400 font-medium` | **PASS** |
| `publish_results` | ❌ Not shown | Button hidden when `!canPublishResults` | **FAIL** |
| `archive` | ❌ Not shown | Only appears in terminal state | **WARNING** |
| `suspend` | ❌ Not shown | Modal-based, not capability-based | **WARNING** |
| `resume` | ❌ Not shown | Button in suspension banner | **WARNING** |

## DP-FR-01: Hardcoded Denial Reason Strings

**Location:** `VoteDenied.vue:59` — branches on raw string `denial_reason === 'ip_rate_limit'`

**Problem:** Uses hardcoded denial reason strings that are not part of the canonical `CapabilityDenialReason` enum. This creates a separate, ungoverned denial language.

**Severity:** Medium — only affects the VoteDenied page, but uses a non-canonical denial language.

## Summary

| Metric | Count |
|--------|-------|
| Actions with visible denial explanation | 2 of 15 (13%) |
| Actions with silent denial (button hidden, no explanation) | 8 of 15 (53%) |
| Actions with modal/route-based handling (no capability explanation) | 5 of 15 (33%) |
| Separate non-canonical denial language | 1 (`VoteDenied.vue`) |

## Recommendations

| Priority | Fix |
|----------|-----|
| **P1** | Add `denialLabel(action)` to all 8 governance action buttons that currently hide without explanation. Users should always see WHY an action is unavailable. |
| **P2** | Migrate `VoteDenied.vue` to use the canonical `CapabilityDenialReason` enum values instead of hardcoded strings. |
| **P3** | Elevate `denial_detail` (the human-readable UX string from backend) through the capability chain to provide richer explanations than the fixed `DENIAL_LABELS` map. |
