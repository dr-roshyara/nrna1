# Frontend Decisions

**Architecture Decision Record (ADR) for Public Digit Frontend**

This document records the **why** behind critical frontend architectural choices, preventing future contributors (and AI agents) from re-litigating decisions and re-introducing solved problems.

---

## FD-001: Button.vue is the canonical button component

**Date:** May 29, 2026  
**Status:** Active  
**Owner:** Design System Lead

### Decision

`resources/js/Components/Button.vue` is the canonical, universal button component for all domains (Voting, Election, Membership, Governance). No `AppButton.vue` or other variant components should be created.

### Reasoning

1. **Already existed before governance layer** — Button.vue was already fully built with comprehensive API
2. **Full variant/size coverage** — supports variants (primary, secondary, outline, ghost, danger, accent, success, warning) and sizes (sm, md, lg)
3. **Eliminates more than 3 variants** — our audit found 5 different button styling patterns; Button.vue unifies them
4. **Avoids duplication paradox** — creating AppButton would duplicate exactly what we're trying to eliminate

### Impact

- Don't create `AppButton.vue`, `PrimaryButton.vue`, `ElectionButton.vue`, etc.
- All new pages use `<Button>` component
- All button styling goes through `Button.vue` props, never inline classes

### Usage

```vue
<Button variant="primary" size="lg">Create Election</Button>
<Button variant="secondary" size="md" :loading="saving">Save</Button>
<Button variant="danger" size="sm" :disabled="!canDelete">Delete</Button>
<Button variant="outline" size="md">Cancel</Button>
```

### How This Was Violated in the Past

Before this decision:
- Different pages used `bg-primary-600`, `bg-blue-600`, `bg-indigo-600` for "primary" buttons
- Five different padding patterns: `px-4 py-2`, `px-6 py-3`, `px-4 py-2.5`, etc.
- Hover states inconsistent or missing
- Focus states inconsistent or missing
- Loading states hardcoded with spinners in some pages, missing in others

---

## FD-002: WorkflowLayout owns topology only (not styling, not forms, not validation)

**Date:** May 29, 2026  
**Status:** Active  
**Owner:** Design System Lead

### Decision

`WorkflowLayout.vue` is a **topology-only** component. It owns:
- ✅ Page structure (header, progress, content, actions, feedback zones)
- ✅ Step progress display
- ✅ Visual zones and flow

It explicitly does NOT own:
- ❌ Form styling or field components (use standard HTML inputs)
- ❌ Button styling (use `<Button>`)
- ❌ Validation rendering (components handle this)
- ❌ Status display (use `<StatusBadge>`)
- ❌ Business logic

### Reasoning

**Prevents "God Component" growth:**

The audit found 80+ layout patterns. Our instinct is to create a component that "fixes" layout once and for all. But if WorkflowLayout tried to own forms, buttons, validation, and status display, it would become:

```
Month 1:  WorkflowLayout (100 lines)
Month 3:  WorkflowLayout (250 lines) — growing past limit
Month 6:  WorkflowLayoutV2 (new component to handle forms)
          WorkflowLayoutElection (special version for elections)
          WorkflowLayoutMembership (special version for members)
Month 12: Back to the fragmentation we started with
```

By keeping WorkflowLayout as a **pure topology component**, it scales indefinitely:
- Voting workflow? Use WorkflowLayout
- Membership workflow? Use WorkflowLayout
- NGO board workflow? Use WorkflowLayout
- Union assembly workflow? Use WorkflowLayout

No variants needed. The topology is universal.

### Enforcement Rule

- If WorkflowLayout grows past 200 lines, it's a sign responsibilities are conflated
- Split before it reaches 300 lines
- Always ask: "Is this component about WHERE things go (topology) or WHAT they look like (styling)?"

### Current Structure

```vue
<WorkflowLayout
  title="..."
  subtitle="..."
  :currentStep="3"
  :totalSteps="5"
  :stepLabels="[...]"
  domain="voting"
>
  <!-- Main content — responsibility of parent/caller -->
  <form class="space-y-6">
    <FormField label="..."> <!-- Caller's responsibility -->
      <input ... /> <!-- Standard HTML -->
    </FormField>
  </form>

  <template #feedback>
    <!-- Errors/success — caller's responsibility -->
    <p v-if="error" class="text-danger-600">{{ error }}</p>
  </template>

  <template #actions>
    <!-- Buttons — caller uses <Button> component -->
    <Button variant="outline">Previous</Button>
    <Button variant="primary">Next</Button>
  </template>
</WorkflowLayout>
```

---

## FD-003: `domain` prop is open-ended string, not enumerated type

**Date:** May 29, 2026  
**Status:** Active  
**Owner:** Design System Lead

### Decision

The `domain` prop in `WorkflowLayout` is declared as `String`, not a union type like `'voting' | 'election' | 'membership' | 'governance'`.

It accepts ANY string: `'voting'`, `'election'`, `'membership'`, `'governance'`, `'regional-assembly'`, `'cooperative-board'`, `'union-chapter'`, etc.

It is NEVER validated or branched on. It's used only for `aria-label` context.

### Reasoning

**Public Digit's vision is to serve multiple organization types:**

- Political parties (current)
- NGOs
- Unions
- Associations
- Communities
- Cooperatives
- Professional societies

If we enumerated domains, we'd be forced to update the component every time a new organization type arrives. This creates forced V2 migrations:

```
2026: enum Domain = 'voting' | 'election' | 'membership'
2027: NGO wants 'ngo-board-election' → update enum
2028: Union wants 'union-assembly-voting' → update enum
2029: Cooperative wants 'cooperative-harvest-decision' → update enum

vs.

2026-2029: domain="anything" just works
```

The prop is intentionally vague and extensible.

### How to Use

```vue
<!-- Current domains -->
<WorkflowLayout domain="voting">...</WorkflowLayout>
<WorkflowLayout domain="election">...</WorkflowLayout>
<WorkflowLayout domain="membership">...</WorkflowLayout>

<!-- Future domains (no code changes needed) -->
<WorkflowLayout domain="ngo-board-election">...</WorkflowLayout>
<WorkflowLayout domain="union-assembly">...</WorkflowLayout>
<WorkflowLayout domain="regional-delegate-voting">...</WorkflowLayout>

<!-- Or omit it entirely if domain context isn't needed -->
<WorkflowLayout>...</WorkflowLayout>
```

---

## FD-004: StatusBadge supports unknown statuses with neutral fallback

**Date:** May 29, 2026  
**Status:** Active  
**Owner:** Design System Lead

### Decision

`StatusBadge.vue` maintains a `defaultStatuses` map of known statuses (voted, verified, active, pending, etc.). For unknown statuses, it renders as neutral (`bg-neutral-100`) with the caller-supplied label.

This allows custom organization-specific statuses without editing the component.

### Reasoning

**Prevents maintenance bottleneck:**

Each organization type might invent domain-specific statuses. Without this pattern:

```
2026: "voted", "verified", "active" statuses → edit StatusBadge
2027: Union invents "chapter-approved" status → edit StatusBadge
2028: NGO invents "regional-delegate" status → edit StatusBadge
2029: Cooperative invents "harvest-decision-pending" → edit StatusBadge
```

With unknown status fallback, no edits are needed:

```vue
<!-- Known statuses (rendering optimized) -->
<StatusBadge status="voted" />

<!-- Unknown statuses (rendering with fallback) -->
<StatusBadge status="chapter-approved" label="Chapter Approved" />
<StatusBadge status="harvest-decision-pending" label="Harvest Decision Pending" />
```

### Architecture

```js
// Known statuses — optimized color + styling
const defaultStatuses = {
  voted: { label: 'Voted', classes: 'bg-green-50 text-green-700 border-green-200', dot: 'bg-green-400' },
  // ...
}

// Unknown statuses — neutral fallback + caller's label
const config = computed(() =>
  defaultStatuses[props.status] ?? {
    label:   props.label ?? props.status,
    classes: 'bg-neutral-100 text-neutral-600 border-neutral-200',
    dot:     'bg-neutral-400',
  }
)
```

### How to Use

```vue
<!-- Predefined statuses (1 prop) -->
<StatusBadge status="voted" />

<!-- Custom statuses (2 props) -->
<StatusBadge status="regional-delegate" label="Regional Delegate" />
<StatusBadge status="chapter-approved" label="Chapter Approved" />

<!-- Future org types (no component edits) -->
<StatusBadge status="cooperative-consensus" label="Consensus Reached" />
```

---

## FD-005: No global color replacements (slate-* → neutral-*, blue-* → primary-*)

**Date:** May 29, 2026  
**Status:** Active  
**Owner:** Design System Lead

### Decision

Do NOT perform global find-replace color substitutions like:
- `slate-*` → `neutral-*` (across 225 files)
- `blue-*` → `primary-*` (across 225 files)
- `emerald-*` → `green-*` (across 225 files)

Color standardization happens **only during feature work** on pages already being modified.

### Reasoning

**High regression risk, semantic loss:**

1. **Regression risk:** One missed edge case in 225 files breaks a critical workflow
2. **Semantic loss:** Colors may be intentional:
   - `emerald` (active) vs `green` (completed) might represent different states
   - `slate` (admin theme) vs `neutral` (standard UI) might be intentional
   - `blue-600` vs `primary-600` might differ in shade (value) not just naming
3. **No observability:** A global replace can introduce visual regressions that tests don't catch

### The Safe Alternative

When a page is modified for feature work, bring its color choices to standard. This is:
- Low risk (only 1 file affected at a time)
- Observable (reviewer can see color diffs)
- Purposeful (aligned with business work)

### Example

```
Feature request: "Update election results display"
   ↓
Modify: resources/js/Pages/Election/ElectionResult.vue
   ↓
During modification: bring color choices to standard
   ↓
PR includes color cleanup as part of feature delivery
```

---

## FD-006: Governance layer prevents new inconsistencies while old code converges gradually

**Date:** May 29, 2026  
**Status:** Active  
**Owner:** Frontend governance

### Decision

The governance layer (UI_GUIDELINES.md, UI_GOVERNANCE_BACKLOG.md) enforces consistency for NEW pages while allowing old pages to converge gradually through normal feature work.

### Mechanisms

1. **Preferred/Allowed/Forbidden rules** in UI_GUIDELINES.md — define what's required for new pages
2. **Component justification rule** — every new component must eliminate 3+ existing variants
3. **Backlog tracking** — UI_GOVERNANCE_BACKLOG.md makes consistency work visible
4. **ADR documentation** — FRONTEND_DECISIONS.md explains the why, preventing future re-litigation

### Expected Timeline

- **Month 1-2:** New pages use canonical components, governance active
- **Month 3-4:** High-priority workflows (Voting, Election) converge through feature work
- **Month 6:** 70% of newly touched pages follow canonical patterns
- **Month 12:** Most active workflows naturally converge as they're maintained

---

## FD-007: AppTable creation is deferred to Phase 2 (deferred decision)

**Date:** May 29, 2026  
**Status:** Pending  
**Owner:** Design System Lead

### Decision

Do NOT create `AppTable.vue` as part of Phase 1 (May 2026). Defer to Phase 2 (August+ 2026) after `WorkflowLayout` stabilizes.

### Reasoning

1. **User-facing workflows first** — Voting, Election, Membership pages (Phase 1 Tiers 1-3) directly impact voters and members. Admin table consistency is lower priority.
2. **Learning from WorkflowLayout** — Table architecture should learn from how WorkflowLayout evolves. Building both simultaneously creates duplication risk.
3. **Tables are 80% consistent already** — The audit found table patterns 80% consistent. Small effort to complete Phase 1 first.

### When to Revisit

- After UI-001 through UI-010 are complete
- When admin pages start getting feature work
- Or explicitly during a dedicated Phase 2 planning session

### Table Tracking

See `UI_GOVERNANCE_BACKLOG.md` item UI-013 for full scope.

---

## How to Add a New Decision

When you discover a recurring decision question (e.g., "should we use local state or store?", "should form validation be in component or DTO?"):

1. **Document the decision** — title, date, status, owner
2. **Explain the reasoning** — why this choice over alternatives
3. **Show how it was violated** — the problem this prevents
4. **Link related decisions** — FD-001 relates to FD-002, etc.
5. **Add to version control** — commit with the code that implements it

This prevents future contributors from rediscovering the same solution twice.

---

**Last Updated:** May 29, 2026  
**Governance:** Linked to `.claude/UI_GUIDELINES.md` and `UI_GOVERNANCE_BACKLOG.md`
