# Plan: Frontend Governance — Option C (Governance-Driven Convergence)

## Context

The UI consistency audit revealed 225 pages with fragmented patterns: 15+ status badge variants, 5 button styles, inconsistent focus states, misaligned forms. Rather than a big-bang migration, the goal is **governance-driven convergence**: create governance rules first, then a minimal set of canonical components, then freeze future divergence while old pages converge naturally through normal feature work.

Build order: **Rules → Components → Backlog** (not Components → Guess Rules Later).

---

## Key Discovery: What Already Exists

Before building, the codebase already has:

| Component | Path | Status |
|---|---|---|
| `Button.vue` | `resources/js/Components/Button.vue` | ✅ Already canonical — full variant/size API |
| `StatusBadge.vue` | `resources/js/Components/StatusBadge.vue` | ✅ Exists but needs general statuses added |
| `WorkflowStepIndicator.vue` | `resources/js/Components/Workflow/WorkflowStepIndicator.vue` | ✅ Fully built, accessible, responsive |
| `WorkflowProgress.vue` | `resources/js/Components/Workflow/WorkflowProgress.vue` | ✅ Exists |
| `WorkflowLayout.vue` | — | ❌ Missing — create this |
| `AppButton.vue` | — | ❌ Not needed — `Button.vue` already covers this |

**Critical implication:** `AppButton.vue` should NOT be created. `Button.vue` already has the canonical API (variant: primary/secondary/outline/ghost/danger/accent/success/warning, size: sm/md/lg, loading, disabled). The governance rules should document `Button.vue` as canonical.

---

## Deliverable 1: Update `.claude/UI_GUIDELINES.md`

The file already exists (795 lines) with color tokens, component APIs, and enforcement tools. Add a **Governance Model** section at the top without removing existing content.

**Add these sections:**

```
## Governance Model

### Preferred (all new pages use this)
- <Button> for all buttons
- <StatusBadge> for all status display
- <WorkflowLayout> for multi-step workflows
- focus:ring-2 focus:ring-primary-500 focus:ring-offset-2

### Allowed
- Existing implementations in untouched pages
- Documented exceptions in design-system.exceptions.json

### Forbidden (new code only)
- New UI variants without documented justification
- Raw <button> elements (use <Button>)
- Hardcoded status badges (use <StatusBadge>)
- New components that don't eliminate 3+ existing variants

## Component Justification Rule
New component approved only if it eliminates ≥ 3 existing variants.
Example: StatusBadge eliminates 15+ variants ✅
Example: ElectionSpecialButton used in 1 page only ❌

## Before Creating a Component (Claude Pre-Check)
1. Search resources/js/Components/
2. Can existing component be extended?
3. Justify why new component is needed
4. Does it eliminate 3+ existing variants?
5. Will it stay under 200 lines?

## Migration Strategy
- Never refactor untouched pages solely for consistency
- When a page is modified for business work: bring it to canonical patterns
- Priority: Voting → Election → Membership
- No global color replacements (semantic risk)
- Track work in UI_GOVERNANCE_BACKLOG.md
```

---

## Deliverable 2: Extend `resources/js/Components/StatusBadge.vue`

Current StatusBadge handles election lifecycle states only. The audit found 50+ hardcoded badges for general statuses (voted, active, pending, inactive, verified) that aren't in the current map.

**Architecture change: rename `map` to `defaultStatuses`** and add fallback for unknown statuses.

**Add to `defaultStatuses` object:**
```js
voted:    { label: 'Voted',    classes: 'bg-green-50 text-green-700 border-green-200',   dot: 'bg-green-400' },
verified: { label: 'Verified', classes: 'bg-green-50 text-green-700 border-green-200',   dot: 'bg-green-400' },
active:   { label: 'Active',   classes: 'bg-primary-50 text-primary-700 border-primary-200', dot: 'bg-primary-400' },
pending:  { label: 'Pending',  classes: 'bg-amber-50 text-amber-700 border-amber-200',   dot: 'bg-amber-400' },
inactive: { label: 'Inactive', classes: 'bg-neutral-100 text-neutral-500 border-neutral-200', dot: 'bg-neutral-400' },
warning:  { label: 'Warning',  classes: 'bg-amber-50 text-amber-700 border-amber-200',   dot: 'bg-amber-400' },
```

**Add extensibility: `label` override + custom fallback:**
```js
// Props
label: { type: String, default: undefined }  // allows override of default label

// Computed fallback: unknown statuses render as neutral with caller's label
const config = computed(() =>
  defaultStatuses[props.status] ?? {
    label: props.label ?? props.status,
    classes: 'bg-neutral-100 text-neutral-600 border-neutral-200',
    dot: 'bg-neutral-400',
  }
)
const displayLabel = computed(() => props.label ?? config.value.label)
```

This means `<StatusBadge status="regional-delegate" label="Regional Delegate" />` works immediately without any edits. Stays well under 150 lines. Does not change existing election statuses.

---

## Deliverable 3: Create `resources/js/Components/WorkflowLayout.vue`

This is the only fully new file. Scope: **workflow topology only** — not styling, not forms, not validation.

**Structure (slots):**
- `#header` — Optional override for custom header content  
- `#default` — Main content area (required)
- `#actions` — Buttons / navigation row  
- `#feedback` — Error / success messages  

**Props:**
- `title` (String, required) — Page/workflow title
- `subtitle` (String, optional) — Subtitle / instruction line
- `currentStep` (Number, optional) — Current step (1-based)
- `totalSteps` (Number, optional, default 5) — Total steps
- `stepLabels` (Array, optional) — Labels for WorkflowStepIndicator
- `domain` (String, optional) — **Open-ended string** (not a union type). Used for aria-label context only. Any value accepted: 'voting', 'election', 'membership', 'governance', or future domains like 'regional-delegate', 'ngо-board', 'cooperative-assembly'. Never validated or branched on.

**Internally uses:**
- `resources/js/Components/Workflow/WorkflowStepIndicator.vue` (already built, accessible, responsive)

**Layout structure:**
```
<div role="main" :aria-label="domain workflow">
  <!-- Header zone -->
  <div> title + subtitle + slot#header </div>
  
  <!-- Progress zone (only if currentStep provided) -->
  <WorkflowStepIndicator v-if="currentStep" ... />
  
  <!-- Content zone -->
  <div> <slot /> </div>
  
  <!-- Feedback zone -->
  <div v-if="$slots.feedback"> <slot name="feedback" /> </div>
  
  <!-- Actions zone -->
  <div v-if="$slots.actions"> <slot name="actions" /> </div>
</div>
```

Budget: under 200 lines. Style: Composition API + `<script setup>`.

**Usage example:**
```vue
<WorkflowLayout
  title="Cast Your Vote"
  subtitle="Step 3 of 5"
  :currentStep="3"
  :totalSteps="5"
  :stepLabels="['Code', 'Agreement', 'Vote', 'Verify', 'Complete']"
  domain="voting"
>
  <!-- content -->
  
  <template #feedback>
    <p v-if="error" class="text-sm text-danger-600">{{ error }}</p>
  </template>
  
  <template #actions>
    <Button variant="outline" @click="back">Previous</Button>
    <Button variant="primary" @click="next">Continue</Button>
  </template>
</WorkflowLayout>
```

---

## Deliverable 4: Create `UI_GOVERNANCE_BACKLOG.md`

New file at project root. Populated from audit findings. Serves as a visible, manageable queue for consistency work — not immediate tasks, future items applied during normal feature delivery.

**Priority structure:**
```
Tier 1 — Voting workflow (highest user impact)
Tier 2 — Election workflow
Tier 3 — Membership workflow
Tier 4 — Admin / Internal (low priority)
```

**Backlog items from audit (with Priority / Business Value / Effort):**

```
UI-001
What:    Replace 50+ hardcoded voted/active/pending badges → use StatusBadge
Pages:   Vote/Create.vue, Vote/Verify.vue, Vote/Result.vue + DemoVote/*
Priority: High
Value:   High — voters see status in 1 consistent color instead of 4
Effort:  Small (4h) — component exists, swap inline classes

UI-002
What:    Adopt Button component in Voting workflow
Pages:   Vote/Create.vue, Vote/Verify.vue, Vote/CreateVotingPage.vue
Priority: High
Value:   High — primary user journey, high visibility
Effort:  Small (2h) — replace raw <button> with <Button variant="">

UI-003
What:    Apply WorkflowLayout to Voting workflow pages
Pages:   Vote/CreateVotingPage.vue, Vote/Create.vue, Vote/Verify.vue
Priority: High
Value:   High — users recognize consistent step-by-step pattern
Effort:  Medium (6h) — restructure page sections into slots

UI-004
What:    Apply WorkflowLayout to Election workflow pages
Pages:   Election/ElectionPage.vue, Election/Show.vue, Election/Management.vue
Priority: High
Value:   High — election managers recognize consistent structure
Effort:  Medium (6h)

UI-005
What:    Adopt Button component in Election workflow
Pages:   Election/Show.vue, Election/Management.vue, Election/ElectionIndex.vue
Priority: High
Value:   Medium — staff-facing, high frequency
Effort:  Small (2h)

UI-006
What:    StatusBadge adoption in Election pages
Pages:   Election/ElectionIndex.vue, Election/ElectionResult.vue
Priority: High
Value:   High — election states already use StatusBadge API
Effort:  Small (2h)

UI-007
What:    Apply WorkflowLayout to Membership workflow
Pages:   Membership pages (when modified for feature work)
Priority: Medium
Value:   Medium — convergence during normal delivery
Effort:  Medium (6h) — defer until membership touched

UI-008
What:    Standardize form focus states in Voting + Election pages
Pages:   Vote/* and Election/Posts/Partials/*
Priority: Medium
Value:   Medium — accessibility + keyboard navigation
Effort:  Small (3h) — add focus:ring-2 focus:ring-primary-500

UI-009
What:    Consolidate table border colors in Members/Index.vue, Admin pages
Pages:   Members/Index.vue, Admin/GeoUnits.vue, Admin/GovernanceLevels.vue
Priority: Low
Value:   Low — admin-only, low user visibility
Effort:  Small (2h) — find-replace slate-200 → neutral-200 in table contexts

UI-010
What:    Consolidate modal patterns in Election/Candidacy pages
Pages:   Election/Candidacy/Applications.vue, Election/Candidacy/Index.vue
Priority: Low
Value:   Low — staff-facing
Effort:  Medium (4h) — defer to phase C (membership+)
```

---

## Deliverable 5: Create `FRONTEND_DECISIONS.md`

Frontend ADR (Architecture Decision Record) repository. Records the **why** behind decisions so future contributors (and Claude) don't re-litigate them.

```markdown
# Frontend Decisions

FD-001
Decision:  Button.vue is the canonical button component
Date:      2026-05-29
Reason:    Already existed with full variant/size API before governance layer was added.
           Creating AppButton.vue would duplicate rather than eliminate.
Use:       <Button variant="primary|secondary|outline|ghost|danger|..." size="sm|md|lg">

FD-002
Decision:  WorkflowLayout owns topology only (not styling, not forms, not validation)
Date:      2026-05-29
Reason:    Prevents God Component growth (WorkflowLayoutV2, WorkflowLayoutElection, etc.)
           Components that own too much create the fragmentation they were built to solve.
Rule:      If WorkflowLayout grows past 200 lines, split responsibilities.

FD-003
Decision:  domain prop in WorkflowLayout is open-ended string, not union type
Date:      2026-05-29
Reason:    Public Digit will serve NGOs, unions, cooperatives, political parties.
           Enumerating domains creates forced V2 migration when new org types arrive.
Use:       domain="voting" or domain="regional-assembly" — any string accepted.

FD-004
Decision:  StatusBadge supports unknown statuses via neutral fallback
Date:      2026-05-29
Reason:    Each organization type may invent domain-specific statuses.
           Requiring edits to StatusBadge per new status is a maintenance bottleneck.
Use:       <StatusBadge status="regional-delegate" label="Regional Delegate" />

FD-005
Decision:  No global color replacements (slate-* → neutral-*, etc.)
Date:      2026-05-29
Reason:    High regression risk across 225 files. Semantic differences may be intentional
           (emerald vs green for active vs completed). Replace only during feature work.
Rule:      Bring pages to color standard only when already modified for business reasons.
```

---

## File Summary

| Action | File | Type |
|---|---|---|
| UPDATE (append sections) | `.claude/UI_GUIDELINES.md` | Governance rules |
| UPDATE (extend map + label prop) | `resources/js/Components/StatusBadge.vue` | Add 6 general statuses + extensibility |
| CREATE | `resources/js/Components/WorkflowLayout.vue` | New slot-based layout component |
| CREATE | `UI_GOVERNANCE_BACKLOG.md` | Backlog tracking file with Priority/Value/Effort |
| CREATE | `FRONTEND_DECISIONS.md` | Frontend ADR repository |

**Not created:**
- `AppButton.vue` — `Button.vue` already covers this (FD-001)
- `AppTable.vue` — Deferred (second wave)

---

## What NOT To Do

- No global color replacements (slate-* → neutral-*, blue-* → primary-*)
- No refactoring untouched pages
- Do not remove or alter existing election statuses in StatusBadge
- WorkflowLayout must not own forms, buttons, validation, or business logic

---

## Verification

After implementation:

1. `WorkflowLayout` renders correctly with all slot combinations (header only, actions only, feedback only, all slots)
2. `StatusBadge` renders new statuses: `voted`, `verified`, `active`, `pending`, `inactive`, `warning`
3. Existing StatusBadge statuses unchanged (draft, approved, voting_active, etc.)
4. `Button.vue` is documented as canonical in UI_GUIDELINES.md
5. `UI_GOVERNANCE_BACKLOG.md` has 12 items organized by tier
6. Run `npm run design-check` — no regression in violation count
