# UI Guidelines & Design System

## ⚠️ Preamble: This Is NOT the Source of Truth

**This is a reference guide that describes how to use the actual sources of truth.**

The real sources of truth are:
- **`tokens.json`** — canonical color values
- **`tailwind.config.js`** — semantic token → Tailwind mappings
- **`resources/js/Components/*.vue`** — component contracts and APIs
- **`.eslintrc.json`** — technical enforcement rules
- **`design-system.exceptions.json`** — approved deviations with governance

This document tells you **how to use those sources correctly** and **what tools enforce what rules.**

---

## For Claude Code CLI

**Before editing any Vue file, follow this order:**

1. Read the **Rule Levels** section (MUST/SHOULD/MAY)
2. Check `design-system.exceptions.json` — some violations are approved
3. Run metrics:
   ```bash
   npm run design-check
   npm run build
   ```
4. Make your changes, following the rule levels
5. Report results in your response:
   ```
   Violations before: X → after: Y
   Build: ✅ Success
   Files changed: Z
   ```

**Never convert** (even if they show violations):
- Gradient backgrounds (`from-blue-50 to-cyan-50` in Management.vue)
- Emerald/amber colors (already semantic)
- Admin slate `bg-slate-900` sidebar theme
- Files marked as permanent exceptions in `design-system.exceptions.json`

---

## Rule Levels

**Not all rules are created equal.** The system has three tiers:

### 🔴 MUST (Non-Negotiable)

These are enforced by tools. Breaking them requires approval.

- **Use semantic color tokens** in Pages layer
  - ✅ `bg-primary-600`, `text-danger-700`, `border-neutral-200`
  - ❌ `bg-blue-600`, `text-red-700`, `border-gray-200`
  
- **Run design-check before commit**
  ```bash
  npm run design-check
  ```
  If violations > threshold, commit requires justification

- **Document exceptions** in `design-system.exceptions.json`
  - File + line
  - Reason
  - Approved by (Design System Lead)
  - Expiry date (or "permanent")

### 🟡 SHOULD (Strongly Encouraged)

These are best practices. Violations require code review justification.

- **Use canonical components** instead of raw HTML
  - ✅ `<Button>`, `<Card>`, `<Badge>`
  - ⚠️ Raw `<button>` (ESLint warns, allowed with justification)

- **Use `electionUiMapper`** to derive UI state from domain state
  - ✅ `const { badge } = mapElectionStateToUI(election.state)`
  - ⚠️ Manual color logic (works, but harder to maintain)

- **Follow accessibility checklist** before merging

### 🟢 MAY (With Justification)

These are allowed escape hatches. Document inline.

- **Prototype with raw classes** (migrate before merge)
- **Use raw HTML** in third-party integrations (wrap in adapter)
- **Performance edge cases** (document with comment)
- **Temporary styling** (add TODO + expiry date)

---

## How Rules Are Enforced

Each rule is enforced by one or more tools. Understand the chain:

| Rule | Tool | Enforcement | Bypass Method | Who Can Bypass |
|------|------|------------|---|---|
| Use semantic tokens | ESLint + `design-check` | Warns on `bg-blue-*`, `text-red-*` | `exceptions.json` | Design System Lead |
| Use `<Button>` component | ESLint `vue/no-restricted-html-elements` | Warns on raw `<button>` | Code comment: `<!-- eslint-disable -->` | Code review approval |
| Accessibility WCAG AA | Manual checklist (+ axe-core later) | Manual review during PR | None | N/A |
| Commit design-check | Pre-commit hook (Phase 5) | Blocks push if violations > 100 | `git commit --no-verify` | Emergency only |
| CI/CD enforcement | GitHub Actions (Phase 5) | Blocks PR merge if violations increased | N/A | Requires approver override |

**Phase 5** (when violations < 50) hardens enforcement. For now, design-check is advisory.

---

## Escape Hatch Policy

Rules are important. Escape hatches are also important. Use them responsibly.

### Approved Escapes (No Approval Needed)

- **Prototyping** — use raw colors during development, migrate before merge
- **Third-party components** — wrap external UI that can't be restyled
- **Performance debugging** — inline styles for temporary testing
- **Comments only** — raw color references in comments/docs

### Conditional Escapes (Need Approval)

Must be added to `design-system.exceptions.json` with:

```json
{
  "pattern_name": {
    "files": ["resources/js/Pages/Example.vue:42"],
    "reason": "Brief explanation",
    "approved_by": ["nab.raj.sharma"],
    "approved_date": "2026-05-01",
    "expires": "permanent" | "2026-08-01"
  }
}
```

**Current approved exceptions:**
- Gradient buttons in `Election/Management.vue:60` (permanent)
- Emerald/amber semantic colors throughout (permanent)
- Admin slate `bg-slate-900` sidebar (permanent)
- Marketing page brand colors in `VotingStart.vue` (expires 2026-08-01)

### Never Allowed (No Escape)

- Inline styles (`style="color: red"`)
- Hard-coded hex colors in component files
- Styles that duplicate component functionality
- Raw colors without governance in exceptions.json

---

## Color Token System

### Semantic Tokens Reference

| Token | Tailwind Scale | Usage | Example Classes |
|-------|---|---|---|
| **primary** | `primary-*` | Main actions, brand identity, CTAs | `bg-primary-600`, `text-primary-500`, `border-primary-200` |
| **success** | `success-*` | Confirmations, approval, active states | `bg-success-50`, `text-success-700`, `border-success-300` |
| **danger** | `danger-*` | Errors, destructive actions, rejections | `bg-danger-50`, `text-danger-600`, `border-danger-200` |
| **warning** | `warning-*` | Caution, pending approval, alerts | `bg-warning-100`, `text-warning-700` |
| **neutral** | `neutral-*` | Backgrounds, secondary text, dividers | `bg-neutral-50`, `text-neutral-600`, `border-neutral-200` |
| **accent** | `accent-*` | Secondary emphasis, highlights | `bg-accent-100`, `text-accent-600` |

### Color Shade Reference

- `*-50` / `*-100`: Very light backgrounds, disabled states
- `*-200` / `*-300`: Borders, dividers, hover backgrounds
- `*-400` / `*-500`: Secondary text, secondary buttons
- `*-600` / `*-700`: Primary text, main buttons, active states
- `*-800` / `*-900`: Dark text, strong emphasis

### Examples

```vue
<!-- ✅ CORRECT -->
<div class="bg-primary-600 text-white">Main Action</div>
<span class="text-success-600 font-medium">✓ Approved</span>
<div class="bg-danger-50 border border-danger-200 text-danger-700">
  Error message
</div>
<button class="bg-warning-100 text-warning-700 hover:bg-warning-200">
  Pending Review
</button>
<div class="border-neutral-200 bg-neutral-50 p-4">
  Sidebar section
</div>

<!-- ❌ WRONG — Raw Tailwind colors -->
<div class="bg-blue-600 text-white">Main Action</div>
<span class="text-green-600">✓ Approved</span>
<div class="bg-red-50 border border-red-200 text-red-700">Error</div>

<!-- ❌ WRONG — Hard-coded hex -->
<div style="background-color: #2563eb; color: white">Action</div>
```

---

## Component Usage Guidelines

### Button Component

**Location:** `resources/js/Components/Button.vue`

**Props:**
- `variant`: `primary` | `secondary` | `danger` | `success` | `ghost` (default: `primary`)
- `size`: `sm` | `md` | `lg` (default: `md`)
- `type`: `submit` | `button` | `reset` (default: `button`)
- `disabled`: boolean
- `loading`: boolean

**MUST: Use variants, not class overrides**

```vue
<!-- ✅ CORRECT -->
<Button variant="primary" @click="submit">Submit</Button>
<Button variant="danger" @click="delete">Delete</Button>
<Button variant="secondary" @click="cancel">Cancel</Button>
<Button variant="ghost" size="sm">Skip</Button>

<!-- ❌ NEVER override with class -->
<Button class="bg-red-500">Delete</Button>
```

**Why:** Buttons have predefined color/sizing/padding logic. Overriding breaks consistency.

---

### Card Component

**Location:** `resources/js/Components/Card.vue`

**Props:**
- `variant`: `default` | `elevated` | `outlined` (default: `default`)
- `padding`: `none` | `sm` | `md` | `lg` (default: `md`)

```vue
<!-- ✅ CORRECT -->
<Card variant="elevated">
  <template #header>
    <h2 class="text-lg font-bold">Section Title</h2>
  </template>
  <!-- Content -->
</Card>

<!-- ❌ NEVER use raw div -->
<div class="rounded-lg border border-neutral-200 shadow p-6">
  <h2>Section Title</h2>
</div>
```

---

### BadgeStatus Component

**Location:** `resources/js/Components/BadgeStatus.vue`

**Props:**
- `status`: `approved` | `pending` | `rejected` | `active` | `draft` | `completed`
- `size`: `sm` | `md` (default: `md`)

```vue
<!-- ✅ Use for election/vote status -->
<BadgeStatus status="approved">Approved</BadgeStatus>
<BadgeStatus status="pending">Pending Approval</BadgeStatus>
<BadgeStatus status="rejected">Rejected</BadgeStatus>
<BadgeStatus status="active">Active Voting</BadgeStatus>

<!-- ❌ Don't create inline status divs -->
<span class="bg-green-100 text-green-700 px-3 py-1 rounded">Approved</span>
```

---

## Component API Safety Rules

### MUST: Components Use Props, Not class Overrides

```javascript
// In component definition, DO NOT:
// - Accept arbitrary `class` prop
// - Allow style overrides

// If override is needed (rare), MERGE don't replace:
<div :class="['base-styles', customClass]">
```

**Rationale:** If someone can override colors, your design system doesn't exist.

### SHOULD: Components Document Their Intent

```javascript
// ✅ Clear what component does
<Button variant="danger">Delete</Button>

// ⚠️ Confusing what "danger" means
<Button class="bg-red-500">Delete</Button>
```

### Component Audit (Before Phase 5)

Audit all components in `resources/js/Components/`:
- [ ] No `class` props that override design
- [ ] All color customization via props/variants
- [ ] Props documented with examples
- [ ] Exceptions documented

---

## Pages Layer Rules

### MUST

- Use semantic color tokens (no `bg-blue-*`, `text-red-*`, etc.)
- Use canonical components (`<Button>`, `<Card>`, etc.)
- Import utilities from `electionUiMapper` for state colors

### SHOULD

- Structure with semantic HTML (`<form>`, `<label>`, etc.)
- Use grid/flex for layout (layout utilities only)
- Follow accessibility checklist before merge

### MAY NOT

- Raw `<button>` (ESLint warns — use `<Button>`)
- Hard-coded colors
- Inline styles
- Duplicate component functionality

### Page Structure Template

```vue
<template>
  <AdminLayout>
    <div class="space-y-6">
      <!-- Hero Section -->
      <div>
        <h1 class="text-3xl font-bold text-neutral-900">Page Title</h1>
        <p class="mt-2 text-neutral-600">Subtitle</p>
      </div>

      <!-- Content Cards -->
      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <Card variant="elevated">
          <template #header>
            <h2 class="text-lg font-semibold">Section 1</h2>
          </template>
          <!-- Form fields, data, etc. -->
        </Card>
      </div>

      <!-- Actions -->
      <div class="flex gap-3 pt-6 border-t border-neutral-200">
        <Button variant="primary" @click="submit">Save</Button>
        <Button variant="secondary" @click="cancel">Cancel</Button>
      </div>
    </div>
  </AdminLayout>
</template>
```

---

## State-to-UI Mapping (Critical Pattern)

### MUST: Use `electionUiMapper` for Domain State

**File:** `resources/js/Utils/electionUiMapper.js`

Never derive UI color from state manually. Always use the mapper.

```javascript
import { mapElectionStateToUI } from '@/Utils/electionUiMapper'

// Get UI state from domain state
const state = election.state // 'draft', 'approved', 'voting_open', etc.
const { badge, label, color } = mapElectionStateToUI(state)

// Returns: { badge: 'neutral', label: 'Draft', color: 'neutral-600' }
```

### State Mapping Reference

| Domain State | Badge Color | Label | Usage |
|---|---|---|---|
| `draft` | neutral | Draft | Election in creation |
| `pending_approval` | warning | Pending Approval | Awaiting admin approval |
| `approved` | success | Approved | Ready for voting |
| `active` | primary | Active | Currently voting |
| `voting_open` | success | Voting Open | Voters can cast votes |
| `voting_closed` | neutral | Voting Closed | Voting period ended |
| `published` | success | Results Published | Results visible |
| `rejected` | danger | Rejected | Admin rejected |

### Example Usage

```vue
<template>
  <Card>
    <div class="flex items-center justify-between">
      <h3>{{ election.name }}</h3>
      <BadgeStatus :status="mapState(election.state)">
        {{ mapLabel(election.state) }}
      </BadgeStatus>
    </div>
  </Card>
</template>

<script setup>
import { mapElectionStateToUI } from '@/Utils/electionUiMapper'

const mapState = (state) => mapElectionStateToUI(state).badge
const mapLabel = (state) => mapElectionStateToUI(state).label
</script>
```

---

## Anti-Patterns (What NOT to Do)

### ❌ Pattern 1: Mixing Semantic + Raw Colors

```vue
<!-- BAD: inconsistent color systems -->
<div class="bg-primary-600 text-red-700 border-gray-200">
  Mixing token + raw colors
</div>

<!-- GOOD: consistent semantic -->
<div class="bg-primary-600 text-danger-700 border-neutral-200">
  All semantic tokens
</div>
```

### ❌ Pattern 2: Copy-Paste UI Blocks

```vue
<!-- BAD: repeated structure -->
<div v-for="item in items" class="bg-white p-4 rounded-lg shadow">
  <h3>{{ item.name }}</h3>
  <p>{{ item.description }}</p>
  <div class="flex gap-2 mt-4">
    <button>Edit</button>
    <button>Delete</button>
  </div>
</div>

<!-- GOOD: extract component -->
<ItemCard v-for="item in items" :key="item.id" :item="item" />
```

### ❌ Pattern 3: Inline Styles or Magic Values

```vue
<!-- BAD -->
<div style="color: #2563eb; padding: 16px; margin: 12px;">

<!-- GOOD -->
<div class="text-primary-600 p-4 m-3">
```

### ❌ Pattern 4: Component + Utility Overrides

```vue
<!-- BAD: Button component + custom color -->
<Button class="bg-green-500 text-white">
  (overrides Button's design)

<!-- GOOD: use variant -->
<Button variant="success">
  (uses Button's designed variant)
```

### ❌ Pattern 5: Manual State-to-Color Logic

```javascript
// BAD: derive color yourself
const color = election.state === 'draft' 
  ? 'neutral-500' 
  : election.state === 'approved'
    ? 'primary-600'
    : 'warning-600'

// GOOD: use mapper
const { color } = mapElectionStateToUI(election.state)
```

### ❌ Pattern 6: Color-Only Status Indication

```vue
<!-- BAD: color alone -->
<span class="text-danger-600">Error</span>

<!-- GOOD: icon + color + text -->
<span class="flex items-center gap-2 text-danger-600">
  <ExclamationIcon class="w-4 h-4" />
  Error occurred
</span>
```

### ❌ Pattern 7: Ignoring Accessibility

```vue
<!-- BAD: unlabeled inputs -->
<input type="text" placeholder="Name" class="border border-neutral-300">

<!-- GOOD: labeled input -->
<div>
  <label for="name" class="block text-sm font-medium text-neutral-700">
    Name
  </label>
  <input id="name" type="text" class="border border-neutral-300">
</div>
```

---

## Design System Evolution Process

When you need a design system change (new token, new component, new pattern):

### Step 1: Propose (GitHub PR)

```markdown
**Title:** Design System: [Brief change description]

**Type:** Design System Change

**Proposal:**
Add `secondary-success` token for softer confirmations

**Problem:**
Currently using `bg-success-50` for soft confirmations, 
but need stronger semantic distinction

**Solution:**
Add new `secondary-success` token and component variant

**Impact:**
- 5 files would be updated
- No breaking changes
- Backward compatible

**Files to Update:**
- tokens.json (add values)
- tailwind.config.js (add mapping)
- Button.vue (add variant)
- UI_GUIDELINES.md (document usage)
```

### Step 2: Review (Design System Lead)

Design System Lead (@nab.raj.sharma) reviews:
- ✅ Solves real problem (not over-engineering)?
- ✅ Aligns with existing tokens?
- ✅ Backward compatible?
- ✅ Performance impact acceptable?
- ✅ Test coverage adequate?

### Step 3: Update & Document

1. Update `tokens.json` with new values
2. Update `tailwind.config.js` to map tokens
3. Update components if needed
4. Update `UI_GUIDELINES.md` with usage examples
5. Update `design-system.exceptions.json` if replacing old pattern

### Step 4: Migration (if tokens change)

```bash
npm run design-check    # see what needs changing
npm run design:fix      # auto-fix what's possible
# manual migration for rest
```

### Step 5: Commit with Full Context

```
feat: add secondary-success token

Add new semantic token for softer confirmations.

Before:
- Used `bg-success-50` text-success-600 for secondary success
- Not semantically distinct from primary success

After:
- New `secondary-success-*` scale available
- Components updated with variant
- 8 files migrated

Migration stats:
- Violations before: 324
- Violations after: 310
- Files changed: 8
- Build: ✅ Success

Approved by: @nab.raj.sharma (Design System Lead)
```

---

## Accessibility Checklist

**MUST complete before merging any UI change:**

- [ ] All text meets WCAG AA contrast ratio (4.5:1 for body, 3:1 for large)
- [ ] All buttons keyboard-accessible (Tab, Enter, Space)
- [ ] Form labels associated with inputs (`<label for="">`)
- [ ] Icons have `aria-label` or hidden text fallback
- [ ] Loading states use `aria-busy="true"`
- [ ] Modals have focus trapping and `role="dialog"`
- [ ] Error messages announced to screen readers
- [ ] Color not used alone to convey information

### Tools (Phase 5+)

```bash
npm run test:a11y  # axe-core automated checks
```

---

## Current Migration Status

**Baseline:** 613 violations (2026-04-30)  
**Current:** 268 violations (2026-05-01)  
**Progress:** 56% complete (345 violations removed)

| Phase | Target | Current | Status | ETA |
|-------|--------|---------|--------|-----|
| **Phase 4 Iter 1-2** | 592 → 580 | 268 | ✅ Complete + bulk migration | Done |
| **Phase 4 Iter 3-6** | 580 → 50 | 268 | 🔄 In Progress | +2-3 days |
| **Phase 5 (Hard Enforcement)** | 50 → 0 | — | ⏸️ Planned | After Phase 4 |

**Note:** Remaining 268 violations are mostly `bg-slate-*` (neutral) and `bg-indigo-*` (decorative), which are intentional per design system.

---

## Enforcement Tools

### Check Compliance

```bash
npm run design-check
```

Reports:
- Raw color violation count by type
- Approved exceptions
- Phase progress
- Recommended next steps

### Auto-Fix (Where Possible)

```bash
npm run design:fix
```

Runs ESLint `--fix` to:
- Add missing components
- Fix formatting
- Remove trailing commas

**Note:** Does NOT auto-migrate colors (must be manual for correctness).

### Track Progress

```bash
npm run migration-progress
```

Shows:
- Current violation count
- Phase targets
- Percentage complete
- Violations by file

---

## Common Patterns

### Form Section with Error

```vue
<Card>
  <template #header>
    <h3 class="font-semibold">Update Election</h3>
  </template>

  <form @submit.prevent="submit" class="space-y-6">
    <!-- Error Alert -->
    <div v-if="error" class="bg-danger-50 border border-danger-200 rounded-lg p-4">
      <div class="flex gap-3">
        <div class="text-danger-600 font-bold">⚠</div>
        <div>
          <p class="font-medium text-danger-900">{{ error.title }}</p>
          <p class="text-sm text-danger-700 mt-1">{{ error.message }}</p>
        </div>
      </div>
    </div>

    <!-- Form Field -->
    <div>
      <label for="name" class="block text-sm font-medium text-neutral-700 mb-2">
        Election Name
      </label>
      <input
        id="name"
        v-model="form.name"
        type="text"
        class="w-full px-3 py-2 border border-neutral-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-transparent"
      />
    </div>

    <!-- Actions -->
    <div class="flex gap-3 pt-6 border-t border-neutral-200">
      <Button variant="primary" type="submit">Save</Button>
      <Button variant="secondary" @click="cancel">Cancel</Button>
    </div>
  </form>
</Card>
```

### Status Indicator List

```vue
<div class="space-y-3">
  <div class="flex items-center gap-3 p-3 bg-neutral-50 rounded-lg">
    <div class="w-2.5 h-2.5 rounded-full bg-success-500"></div>
    <span class="text-sm text-neutral-700">Elections Approved</span>
    <span class="ml-auto font-semibold text-neutral-900">{{ count }}</span>
  </div>
</div>
```

### Election State Badge

```vue
<template>
  <Card>
    <div class="flex items-center justify-between">
      <h3 class="font-semibold">{{ election.name }}</h3>
      <BadgeStatus :status="stateMapping.badge">
        {{ stateMapping.label }}
      </BadgeStatus>
    </div>
  </Card>
</template>

<script setup>
import { mapElectionStateToUI } from '@/Utils/electionUiMapper'

const election = defineProps(['election'])
const stateMapping = computed(() => 
  mapElectionStateToUI(election.value.state)
)
</script>
```

---

## File Structure Reference

| File | Purpose | When to Edit |
|------|---------|--------------|
| `.claude/UI_GUIDELINES.md` | This document — reference guide | When patterns change |
| `.claude/OWNERS` | Governance (approval chain, responsibilities) | When adding new roles |
| `tokens.json` | Canonical token registry | When adding new semantic tokens |
| `tailwind.config.js` | Token → Tailwind mappings | Auto-generated from tokens.json |
| `resources/css/tokens.css` | CSS variables for tokens | Auto-generated from tokens.json |
| `design-system.exceptions.json` | Approved deviations + expiry | When granting exceptions (2+ approvers) |
| `resources/js/Components/*.vue` | Component contracts | When changing API or design |
| `resources/js/Utils/electionUiMapper.js` | Domain state → UI state | When adding new election states |
| `.eslintrc.json` | ESLint rules | When adding component linting rules |

---

## Questions? Next Steps?

- **Color questions:** Check `tokens.json` and `tailwind.config.js`
- **Component questions:** Read component file in `resources/js/Components/`
- **Exception questions:** See `design-system.exceptions.json`
- **Governance questions:** See `.claude/OWNERS`
- **Evolution questions:** See **Design System Evolution Process** section above

---

**Last Updated:** 2026-05-01  
**Maintained By:** Design System Lead (nab.raj.sharma)  
**Governance:** See `.claude/OWNERS` for approval workflow  
**Status:** Phase 4 in progress, 268 violations remaining (target: < 50)
