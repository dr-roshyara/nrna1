# Management.vue Governance UX Architectural Improvements

## Context

The Election Management UI (Phase 1 of Management.vue improvements) was completed in tasks #39–43: amber suspension banner, action grouping, button size hierarchy, empty state, and `danger-outline` suspend variant.

A senior architect reviewed the resulting design and confirmed it as architecturally sound — correctly treating the UI as a projection layer, reducing escalation semantics, and applying proper action grouping. The architect also identified 8 improvement areas to elevate the UI from a visually improved admin tool into a **constitutionally explainable lifecycle governance projection**.

This plan addresses the immediate tactical improvements (1–3 below) while documenting the medium/long-term improvements (4–8) for future phases.

---

## Current Architecture (from Explore)

- **Section headings** (already implemented): "Governance Actions", "Phase Controls", "Governance"
- **Capability resolution**: `ElectionCapabilityResolver` (backend) returns `allowed`, `denial_reason`, `denial_detail` per action
- **Frontend adapter**: `useElectionCapabilities` composable exposes `canDo(action)`, `denialReason(action)`, `isDenied(action)` for all 14 constitutional actions
- **Denial explanation**: Currently only `openVotingBlockedReason` is surfaced in the UI — all other actions silently disable without explanation
- **Projection layer**: `ElectionLifecycleProjection` exists (`app/Domain/Election/Projection/ElectionLifecycleProjection.php`) — handles linear progression
- **No ManagementProjection DTO**: Props passed raw from controller

---

## Immediate Implementation (This Session)

### 1. Rename Section Headings — Ubiquitous Language Cleanup

**Problem**: "Governance Actions", "Phase Controls", "Governance" — overloaded terminology, semantic overlap.

**Rename**:
| Current | Proposed |
|---------|----------|
| Governance Actions | Lifecycle Actions |
| Phase Controls | Phase Transitions |
| Governance | Administrative Controls |

**Files**:
- `resources/js/Pages/Election/Management.vue` — template heading bindings (already use `t.governance_actions.title`, `t.phase_controls.title`, `t.governance_section.title`)
- `resources/lang/en.json` (or equivalent i18n file) — update the three translation values

**Pattern** (repeat for all 3 sections):
```vue
<!-- Before -->
{{ t.governance_actions.title }}  → "Governance Actions"
<!-- After -->
{{ t.lifecycle_actions.title }}   → "Lifecycle Actions"
```

Check whether translations live in a Vue-side i18n file (e.g., `resources/js/Pages/Election/Management.vue` inline or `resources/js/i18n/`) or in PHP lang files. Update accordingly.

### 2. Universal Transition Explainability

**Problem**: When actions are disabled, the UI is silent — users don't know why or what to do next. Only `OPEN_VOTING` currently shows its denial reason.

**The `denialReason(action)` composable method already exists** in `useElectionCapabilities.ts` — it reads `capabilities[action].denial_reason` from the backend. We just need to wire it to every disabled action.

**Pattern** (apply to each action button group):
```vue
<!-- Apply to: Submit for Approval, Begin Setup, Complete Administration, Open Voting, Close Voting, Suspend -->
<p v-if="isDenied(ElectionActions.SUBMIT_FOR_APPROVAL) && denialReason(ElectionActions.SUBMIT_FOR_APPROVAL)"
   class="mt-2 text-xs text-amber-600 font-medium">
  {{ denialReason(ElectionActions.SUBMIT_FOR_APPROVAL) }}
</p>
```

**Actions to add denial reasons to** (currently missing):
- `SUBMIT_FOR_APPROVAL`
- `BEGIN_SETUP`
- `COMPLETE_ADMINISTRATION`
- `CLOSE_VOTING`
- `SUSPEND` (only show if action exists but is blocked)

`OPEN_VOTING` already has this pattern — use it as the template.

### 3. Clarify Suspension Modal Framing

**Problem**: The suspension modal warning says "governance intervention" but doesn't distinguish between:
- Temporary operational pause (admin needs to fix something)
- Governance freeze (dispute, complaint)
- Emergency administrative halt (fraud detection)

The modal currently has `suspendCategory` dropdown (optional). The architect wants the modal copy to make the semantic nature explicit.

**Change**: In the modal:
- Title: Keep "Suspend Election"
- Warning text: Change from "governance intervention" framing to "This temporarily pauses all election operations. Voters cannot cast votes while suspended. Use the reason field to record why this action was taken."
- Category dropdown: Make required (not optional), with explicit options: "Operational Pause", "Administrative Review", "Dispute Hold", "Technical Issue"

---

## Medium-Term (Future Phase)

### 4. ElectionManagementProjection DTO
Create `App\Application\Election\Projection\ElectionManagementProjection` to:
- Wrap raw stateMachine props into a typed, presentation-safe DTO
- Expose grouped actions with explainability metadata
- Remove raw controller object from Vue props

### 5. Interaction Topology Neutrality Documentation
Add comment block to Management.vue establishing invariants:
- Visual prominence ≠ constitutional authority
- Section ordering ≠ governance precedence  
- Button size ≠ legitimacy rank

### 6. Accessibility Governance Audit
- Keyboard traversal order audit (Tab key follows visual grouping)
- Screen-reader heading hierarchy (h3 sections, aria-labels)
- Focus ring visibility on disabled states
- Reduced-motion compliance for loading states

### 7. Suspension Semantic Domain Clarification
In the domain layer, formalize whether `SUSPENDED` means:
- Operational pause (can be resumed by admin)
- Constitutional freeze (requires governance unlock)
- Emergency halt (requires committee review)
This affects both UI framing and backend capability rules.

---

## Files to Modify

| File | Change |
|------|--------|
| `resources/js/Pages/Election/Management.vue` | Section heading key renames (3), denial reason `<p>` tags per action (5), suspension modal copy |
| `resources/js/i18n/en.json` or inline Management.vue translations | Rename 3 section translation keys and update 3 values |

---

## Verification

1. `npm run dev` — confirm no compilation errors
2. Open Management.vue in browser at different lifecycle states:
   - Draft: "Lifecycle Actions" section shows, "Submit for Approval" enabled, other sections empty
   - Setup: "Phase Transitions" section shows, disabled actions show denial reasons
   - Suspended: "Administrative Controls" still shows Resume; denial reason shown on other blocked actions
3. Confirm suspend modal shows updated copy and required category
4. Run `npm run design-check` for design system compliance
5. Run `php artisan test --env=testing --filter="ElectionManagement|ElectionSuspension"` — no regressions
