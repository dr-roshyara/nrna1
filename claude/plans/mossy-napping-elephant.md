# Management.vue UI/UX Improvement Plan

## Context

The Election Management page (`resources/js/Pages/Election/Management.vue`) has several UX pain points identified by a Senior UI/UX Designer review: the suspension banner is too visually aggressive, action buttons lack visual grouping/hierarchy, and the empty state is generic. This plan covers the targeted improvements to address these issues.

**Already completed:** Added `danger-outline` variant to `Button.vue` component.

---

## Changes

### 1. Suspension Banner — Softer, More Informative (Management.vue lines 76–98)

Replace the full-red aggressive banner with an amber warning-style banner:
- `bg-amber-50 border-l-4 border-amber-500 rounded-xl p-5`
- Icon in a circle container (warning triangle SVG, no emoji)
- Title "Election Suspended", description text, optional reason display
- Resume button styled amber (matches banner)
- Remove emoji (`🚫`), use SVG icon instead (aligns with design system)

### 2. Action Grouping with Section Dividers (Management.vue lines 130–263)

Restructure the action zone from a single flat list into three visually grouped sections:

| Section | Label | Buttons | When shown |
|---------|-------|---------|------------|
| **Governance Actions** | `h3` label | Submit for Approval, Begin Setup | Draft/Approved states |
| **Phase Controls** | `h3` label, border-top divider | Complete Admin, Open Voting, Close Voting | Setup/Nomination/Ready/Voting states |
| **Governance** | `h3` label, border-top divider | Suspend Election | Any non-terminal state |

Each section has:
- `text-xs font-semibold text-slate-400 uppercase tracking-wider` heading
- A `border-t border-slate-200` divider above (except the first section)
- `space-y-3` for vertical rhythm within

### 3. Button Size Adjustments (Management.vue lines 130–263)

| Action | Size | Rationale |
|--------|------|-----------|
| Submit for Approval | `lg` | Primary governance transition |
| Begin Setup | `lg` | Primary governance transition |
| Complete Administration | `md` | Phase step, not a primary action |
| Open Voting | `md` | Phase step |
| Close Voting | `md` | Phase step |
| Suspend | `md` | Governance action |

Gradient classes remain unchanged for primary actions (`bg-gradient-to-r from-emerald-500...`).

### 4. Improved Empty State (Management.vue lines 257–261)

Replace the generic locked message with an educational empty state:
- Lock icon in a circle container
- "No actions available in current state" heading
- "Complete previous phases to unlock next steps" subtext

### 5. Suspend Button — Use `danger-outline` Variant

The suspend button changes from `variant="danger"` to `variant="danger-outline"` for lower visual weight — it's a governance tool, not a destructive action on data.

---

## Files Modified

| File | Changes |
|------|---------|
| `resources/js/Components/Button.vue` | ✅ Already done — added `danger-outline` variant |
| `resources/js/Pages/Election/Management.vue` | Suspension banner, action grouping, button sizes, empty state, suspend variant |

---

## Verification

1. Open Management.vue in the browser at various lifecycle states
2. Verify suspension banner shows amber (not red) with reason text
3. Verify action buttons are grouped under "Governance Actions", "Phase Controls", "Governance"
4. Verify button sizes differ by importance (lg vs md)
5. Verify empty state shows lock icon and educational text
6. Verify suspend button uses `danger-outline` variant
7. Run `npm run dev` and check for compilation errors
