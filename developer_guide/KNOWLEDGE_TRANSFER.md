# Design System Knowledge Transfer

**Purpose:** Bridge between governance, design tokens, and migration strategy. This document is the **institutional memory** of why we built the system this way and how to evolve it.

---

## 1. Design Token System

### Canonical Color Values

All colors are defined in `resources/js/design-tokens/tokens.json`:

```json
{
  "primary":       "#2563eb",       // blue-600 (main actions, CTAs)
  "primary-dark":  "#1d4ed8",       // blue-700 (hover, focus)
  "accent":        "#f59e0b",       // amber-500 (secondary emphasis)
  "accent-dark":   "#b45309",       // amber-700 (hover)
  "success":       "#16a34a",       // green-600 (approvals, active)
  "danger":        "#dc2626",       // red-600 (errors, destructive)
  "warning":       "#f59e0b",       // amber-500 (same as accent)
  "neutral":       "#slate-*",      // slate scale (backgrounds, text)
}
```

### Why These Values?

| Color | Why | Changed From |
|-------|-----|--------------|
| **Primary (#2563eb)** | High contrast, professional, election theme | Raw `blue-600` scattered across 15 files |
| **Accent (#f59e0b)** | Warm secondary, good accessibility | Raw `amber-500`, sometimes `yellow-400` |
| **Success (#16a34a)** | Clear positive signal, accessible | Raw `green-600`, sometimes `emerald-500` |
| **Danger (#dc2626)** | High alert, accessible for colorblind | Raw `red-600`, sometimes `red-500` |
| **Neutral (slate)** | Professional, readable, all backgrounds | Mixed `gray-*`, `slate-*`, `zinc-*` |

### Mapping to Tailwind

`tailwind.config.js` maps semantic tokens to Tailwind scales:

```javascript
const colors = require('tailwindcss/colors')

module.exports = {
  theme: {
    extend: {
      colors: {
        primary:   colors.blue,    // primary-600, primary-700, etc.
        accent:    colors.amber,   // accent-500, accent-600, etc.
        success:   colors.green,   // success-600, success-700, etc.
        danger:    colors.red,     // danger-600, danger-700, etc.
        warning:   colors.amber,   // same as accent (intentional)
        neutral:   colors.slate,   // neutral-50, neutral-200, etc.
      },
    },
  },
}
```

**Key insight:** Each semantic token maps to a Tailwind color SCALE, not a single value. This lets us use `primary-50` for light backgrounds, `primary-600` for buttons, `primary-900` for dark text.

---

## 2. Migration Rules

### Phase 4: Pages Layer (170 files, 56% complete)

**Baseline:** 613 violations (2026-04-30)  
**Current:** 268 violations (2026-05-01)  
**Target:** < 50 violations  
**Status:** Phase 4 Iterations 1-2 complete, bulk migration applied

#### Conversion Rules

| Raw Color | Convert To | Use Case |
|-----------|-----------|----------|
| `bg-blue-*` | `bg-primary-*` | Action buttons, primary backgrounds |
| `text-blue-*` | `text-primary-*` | Links, primary text |
| `border-blue-*` | `border-primary-*` | Primary borders |
| `bg-red-*` | `bg-danger-*` | Error backgrounds, destructive actions |
| `text-red-*` | `text-danger-*` | Error text messages |
| `text-gray-*` | `text-neutral-*` | Secondary text, UI chrome |
| `bg-gray-*` | `bg-neutral-*` | Neutral backgrounds |
| `border-gray-*` | `border-neutral-*` | Neutral dividers |
| `<button>` | `<Button variant="...">` | Raw HTML buttons → components |

#### Migration Priority (High to Low Violations)

Completed:
- ✅ Phase 4 Iter 1-2: Commission/Dashboard, Election/Management, Vote/Create, VotingStart
- ✅ Bulk migration: 170 files (bulk regex replacements)

In Progress:
- 🔄 Phase 4 Iter 3-6: Remaining files by violation count

Exceptions (DO NOT convert):
- Gradient buttons (approved in exceptions.json)
- Emerald/amber semantic colors (already correct)
- Admin slate `bg-slate-900` theme (intentional)

#### Why This Order?

1. **High-violation files first** (biggest impact, fastest ROI)
2. **Pages layer first** (widest reach, most visible)
3. **Components later** (lower count, safer to defer)
4. **Layouts last** (slot mismatch risk, most careful review)

---

## 3. Governance & Decision Process

### Design System Decision Authority

| Decision Type | Who Decides | Review Time | Approval |
|---|---|---|---|
| **Use existing tokens** (primary, success, etc.) | Anyone | None | Self-approved |
| **New exception** (2-week temporary) | Anyone | PR review | 1 approver (DSL*) |
| **New permanent exception** | Anyone | PR review | 2+ approvers (DSL + team) |
| **New semantic token** | Design System Lead | Architecture review | 2+ approvers (DSL + stakeholders) |
| **Component API change** | Design System Lead | Full testing | 2+ approvers (DSL + eng lead) |
| **Color value change** | Design System Lead | Accessibility audit | 2+ approvers (DSL + accessibility) |

*DSL = Design System Lead (nab.raj.sharma)

### Exception Lifecycle

**Why exceptions exist:** Real systems have edge cases. Exceptions are governed (not blanket).

```
┌─────────────────────────────────────────────────────┐
│  1. IDENTIFY EDGE CASE                              │
│  (third-party component, performance hack, etc.)    │
└──────────────┬──────────────────────────────────────┘
               │
┌──────────────▼──────────────────────────────────────┐
│  2. PROPOSE EXCEPTION (via PR)                      │
│  - File path                                        │
│  - Reason (why can't you use design system)         │
│  - Temporary or permanent                           │
│  - Expiry date (if temporary)                       │
└──────────────┬──────────────────────────────────────┘
               │
┌──────────────▼──────────────────────────────────────┐
│  3. REVIEW & APPROVE                                │
│  - Design System Lead checks: justified?            │
│  - If temporary: expiry date OK?                    │
│  - If permanent: can this be solved differently?    │
│  - 1 approver (temporary) or 2+ (permanent)        │
└──────────────┬──────────────────────────────────────┘
               │
┌──────────────▼──────────────────────────────────────┐
│  4. ADD TO design-system.exceptions.json            │
│  - File, reason, approved_by, expires               │
│  - Commit with reference to PR                      │
└──────────────┬──────────────────────────────────────┘
               │
┌──────────────▼──────────────────────────────────────┐
│  5. QUARTERLY AUDIT                                 │
│  - Temporary exceptions: expired? Remove.           │
│  - Permanent exceptions: still justified?           │
│  - Unused exceptions: delete                        │
│  - Report to team                                   │
└─────────────────────────────────────────────────────┘
```

### Current Approved Exceptions

Located in `design-system.exceptions.json`:

| Pattern | File | Reason | Approved | Expires |
|---------|------|--------|----------|---------|
| Gradient buttons | `Election/Management.vue:60` | Decorative help link | 2026-04-30 | permanent |
| Emerald colors | Various | Already semantic (success) | 2026-04-30 | permanent |
| Amber colors | Various | Already semantic (warning) | 2026-04-30 | permanent |
| Admin slate theme | `AdminLayout.vue` | Intentional professional feel | 2026-04-30 | permanent |
| Marketing colors | `VotingStart.vue` | Public-facing, brand flexibility | 2026-04-30 | 2026-08-01 |

---

## 4. Why This System?

### Problem We Solved

**Before:**
```
- 200+ files using raw Tailwind colors (bg-blue-*, text-green-*, etc.)
- 5 different button systems
- No semantic meaning (is blue a primary action or just... blue?)
- Hard to change branding (blue-600 in 50 places)
- No governance (anyone could add any color anywhere)
```

**After:**
```
- Semantic tokens (primary means "main action")
- 1 canonical Button component
- Centralized colors (change primary-600 once, affects everywhere)
- Governed evolution (proposal → review → approval → migration)
- Accessible by default (tokens chosen for WCAG AA)
```

### Design Philosophy

**Constraint:** UI design should be a deterministic projection of domain state.

Just like in the backend:
- Domain: `election.state = 'draft'`
- Projection: Show as `bg-neutral-50` with `BadgeStatus status="draft"`

The UI mapper (`electionUiMapper.js`) makes this explicit:

```javascript
mapElectionStateToUI('draft')
// Returns: { badge: 'neutral', label: 'Draft', color: 'neutral-600' }
```

No manual color logic. No "if approved, use green". The mapper is the contract.

---

## 5. Tools & Enforcement

### ESLint Rules

```javascript
// .eslintrc.json
{
  "vue/no-restricted-html-elements": [
    "warn",
    {
      "element": "button",
      "message": "Use <Button> component instead. See .claude/UI_GUIDELINES.md"
    }
  ]
}
```

Catches raw `<button>` elements. Warns developers at edit time.

### CLI Tools

```bash
npm run design-check       # Report violations by type
npm run migration-progress # Show Phase progress
npm run design:fix         # Auto-fix ESLint issues
npm run build              # Verify no regressions
```

### Git Hooks (Phase 5)

When violations drop below 50:
- **Pre-commit hook:** Checks violations count
- **GitHub Actions:** Blocks PR merge if violations increased

---

## 6. Evolution Process Example

**Scenario:** We need a "secondary success" token for softer confirmations.

### Step 1: Propose (PR)

```markdown
**Title:** Design System: Add secondary-success token

**Type:** Design System Change

**Problem:**
Currently using `bg-success-50` for secondary success states,
but need stronger semantic distinction from primary success.

**Solution:**
Add `secondary-success-*` token and Button variant.

**Files to Update:**
- tokens.json (add values)
- tailwind.config.js (add mapping)
- Button.vue (add variant)
- UI_GUIDELINES.md (document)
```

### Step 2: Review

Design System Lead checks:
- ✅ Solves real problem?
- ✅ Aligns with existing tokens?
- ✅ Backwards compatible?
- ✅ Test coverage?

### Step 3: Update

1. Update `tokens.json`:
```json
{
  "secondary-success": "#d1fae5",
  "secondary-success-dark": "#86efac"
}
```

2. Update `tailwind.config.js`:
```javascript
'secondary-success': colors.emerald
```

3. Update `Button.vue`:
```javascript
const variantClasses = {
  'secondary-success': 'bg-secondary-success-50 text-secondary-success-700'
}
```

4. Update `UI_GUIDELINES.md`:
```markdown
### Secondary Success
Use for softer confirmations that don't need primary emphasis.
<Button variant="secondary-success">Confirm</Button>
```

### Step 4: Migrate

```bash
# Find files using old pattern
grep -r "bg-success-50" resources/js/Pages/

# Update to new variant
# (8 files found → update manually for correctness)
```

### Step 5: Commit

```
feat: add secondary-success token

Add new token for softer success confirmations.

Tokens:
- secondary-success: #d1fae5 (light background)
- secondary-success-dark: #86efac (dark text)

Components:
- Button: added variant="secondary-success"
- UI_GUIDELINES.md: documented usage

Migration:
- 8 files updated
- Before: 324 violations → After: 310 violations

Approved by: @nab.raj.sharma (Design System Lead)
```

---

## 7. FAQ & Decision Guide

### Q: Can I use a raw color for prototyping?

**A:** Yes, if you migrate it before merge.

```
Temporary exception → Allowed (self-approved)
But: Must migrate before PR merge
```

### Q: What if a component needs a custom color?

**A:** Add a variant prop.

```vue
<!-- BAD: bypasses system -->
<Button class="bg-custom-color">

<!-- GOOD: extends system -->
<Button variant="custom">
  <!-- Button internally uses design system -->
```

### Q: When do I need approval to add an exception?

**A:** Always when it's permanent or > 2 weeks.

```
Temporary (< 2 weeks):     1 approver (DSL)
Permanent:                 2+ approvers (DSL + team)
New token:                 2+ approvers (DSL + stakeholders)
Component API change:      2+ approvers (DSL + eng lead)
```

### Q: What if I need a color that's not a token?

**A:** Don't. Add a token first.

Process:
1. Propose token
2. Get approval
3. Add to tokens.json + tailwind.config.js
4. Update components
5. Migrate code
6. Commit with before/after metrics

---

## 8. Roadmap & Phases

### Phase 4: Pages Layer (Current)

- **Goal:** Reduce violations from 613 → < 50
- **Status:** 56% complete (268 violations remain)
- **Timeline:** +2-3 days at current pace
- **What:** Migrate raw colors to semantic tokens in all Page files

### Phase 5: Hard Enforcement

- **Goal:** Activate pre-commit hook + CI/CD
- **Status:** Planned (after Phase 4)
- **What:**
  - Pre-commit hook blocks if violations > 50
  - GitHub Actions blocks PR merge if violations increased
  - Manual overrides require approval

### Phase 6: Layout Consolidation

- **Goal:** Consolidate duplicate layout structures
- **Status:** Planned (after Phase 5)
- **What:**
  - Merge `NrnaLayout` + `LoginLayout` → `PublicLayout`
  - Delete backup files
  - Verify no slot mismatches

---

## 9. Contacts & Ownership

| Role | Person | Responsibilities |
|------|--------|------------------|
| **Design System Lead** | nab.raj.sharma | Approval authority, evolution decisions |
| **Design System Contributors** | (team) | Propose changes, implement migrations |
| **Code Reviewers** | (team) | Enforce rules in PRs |

---

## 10. Tools & References

### Files That Define the System

| File | Purpose |
|------|---------|
| `.claude/UI_GUIDELINES.md` | Reference guide for Claude Code CLI |
| `.claude/CLAUDE.md` | Entry point (references UI_GUIDELINES.md) |
| `resources/js/design-tokens/tokens.json` | Canonical token values |
| `tailwind.config.js` | Token → Tailwind mapping |
| `design-system.exceptions.json` | Approved deviations (with governance) |
| `.eslintrc.json` | ESLint rules (catches raw buttons) |
| `scripts/design-check.sh` | Reports violations by type |
| `scripts/migration-progress.sh` | Shows phase progress |

### Running the System

```bash
# Check current state
npm run design-check

# Show progress
npm run migration-progress

# Auto-fix what's possible
npm run design:fix

# Verify no regressions
npm run build
```

---

## 11. Historical Context (Why We Built This Way)

### April 2026: The Problem

After 6 months of development, the codebase had:
- 200+ Vue files with inconsistent styling
- 5 different button implementations
- Colors chosen ad-hoc (raw Tailwind, hardcoded hex)
- No way to change branding (blue-600 in 50 places)
- Accessibility wasn't verified centrally

### The Solution

**Design as a System, Not a Collection:**

1. **Define semantics** — primary, success, danger (not blue, green, red)
2. **Use components** — Button, Card, Badge (not raw HTML)
3. **Govern evolution** — proposal → review → approval → migration
4. **Enforce technically** — ESLint, design-check, pre-commit hooks

Result: UI is now a **deterministic projection of domain state**, just like the backend.

---

**Last Updated:** 2026-05-01  
**Maintained By:** Design System Lead (nab.raj.sharma)  
**Status:** Phase 4 in progress (268 violations, target < 50)
